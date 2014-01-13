BEGIN;

ALTER TABLE settlement ADD COLUMN payment_id integer ;

ALTER TABLE settlement
  ADD CONSTRAINT settlement_payment_id_fkey FOREIGN KEY (payment_id)
      REFERENCES payment (id) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE SET NULL;




CREATE OR REPLACE FUNCTION settlement_balance_increase(_settlement_id integer)
  RETURNS numeric AS
$BODY$
        BEGIN

            RETURN coalesce(SUM(p.amount), 0) FROM payment p where p.id  in (select payment_id from settlement where id = _settlement_id);

        END;
    $BODY$
  LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
  COST 100;



  CREATE OR REPLACE FUNCTION contract_balance_increase(_contract_id integer, _month integer, _year integer)
  RETURNS numeric AS
$BODY$

        BEGIN

            return
                coalesce(SUM(p.amount), 0)
                FROM payment p
                JOIN settlement s on s.payment_id = p.id
                WHERE s.contract_id = _contract_id
                AND year(p.date) = _year
                AND month(p.date) = _month;

        END;
    $BODY$
  LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
  COST 100;


  CREATE OR REPLACE FUNCTION creditor_balance_increase(_creditor_id integer, _month integer, _year integer, _currency_code text)
  RETURNS numeric AS
$BODY$

        BEGIN

            return (select sum(coalesce(contract_balance_increase(c.id, _month, _year),0)) from contract c where c.creditor_id = _creditor_id and c.currency_code = _currency_code);

        END;
    $BODY$
  LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
  COST 100;



DROP VIEW regulation;
DROP FUNCTION contract_balance(integer, date, boolean);

CREATE OR REPLACE FUNCTION contract_balance(_contract_id integer, _date date, _with_capitalization_and_balance_reduction_and_balance_increase boolean)
  RETURNS numeric AS
$BODY$
        DECLARE
            _last_settlement record;
            _balance numeric(15,2);
            _contract record;
        BEGIN

            SELECT * FROM contract c WHERE c.id = _contract_id INTO _contract;
            IF (
                ( _contract.closed_at IS NULL OR (_contract.closed_at IS NOT NULL AND _contract.closed_at >= _date))
                AND _contract.activated_at is NOT NULL
                AND _contract.activated_at::DATE <= _date::DATE)
            THEN
                SELECT * FROM settlement s WHERE s.contract_id = _contract_id AND s.date <= _date ORDER BY s.date DESC LIMIT 1 INTO _last_settlement;
                IF _last_settlement.id <> 0 THEN
                    _balance = _last_settlement.balance;
                    IF _with_capitalization_and_balance_reduction_and_balance_increase = true OR _last_settlement.date < _date THEN
                        _balance = _balance + _last_settlement.capitalized - settlement_balance_reduction(_last_settlement.id);
                        _balance = _balance + settlement_balance_increase(_last_settlement.id);
                    END IF;
                ELSE
                    _balance = _contract.amount;
                END IF;
            ELSE
                _balance = 0;
            END IF;
        return _balance;
        END;
    $BODY$
  LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
  COST 100;


CREATE OR REPLACE VIEW regulation AS
 SELECT (ry.id || '_'::text) || c.id AS id,
    (cr.lastname::text || ' '::text) || cr.firstname::text AS creditor_fullname,
    cr.id AS creditor_id, c.name AS contract_name, c.id AS contract_id,
    ry.id AS regulation_year, c.currency_code AS contract_currency_code,
    contract_balance(c.id, first_day(1, ry.id), false) AS start_balance,
        CASE year(c.activated_at)
            WHEN ry.id THEN c.activated_at
            ELSE NULL::date
        END AS contract_activated_at,
        CASE year(c.activated_at)
            WHEN ry.id THEN c.amount
            ELSE NULL::numeric
        END AS contract_balance,
    contract_interest(c.id, ry.id) AS regulation,
    contract_paid(c.id, ry.id) AS paid,
    contract_paid_for_current_year(c.id, ry.id) AS paid_for_current_year,
    contract_unpaid(c.id, last_day(12, ry.id)) AS unpaid,
    contract_unpaid(c.id, last_day(12, ry.id - 1)) AS unpaid_in_past,
    contract_capitalized(c.id, ry.id) AS capitalized,
    contract_balance(c.id, last_day(12, ry.id), true) AS end_balance
   FROM contract c, regulation_year ry, creditor cr
  WHERE cr.id = c.creditor_id AND (ry.id = year(c.activated_at) OR (ry.id IN ( SELECT DISTINCT year(s.date) AS year
           FROM settlement s
          WHERE s.contract_id = c.id)) OR (ry.id IN ( SELECT DISTINCT year(op.date) AS year
           FROM allocation a
      JOIN settlement s1 ON a.settlement_id = s1.id
   JOIN outgoing_payment op ON op.id = a.outgoing_payment_id
  WHERE s1.contract_id = c.id)) OR contract_unpaid(c.id, last_day(12, ry.id))::integer <> 0 AND ry.id <= year(now()::date));


CREATE OR REPLACE FUNCTION contract_balance_increase(_contract_id integer, _till_date date)
  RETURNS numeric AS
$BODY$
        BEGIN
            return
              coalesce(SUM(p.amount), 0)
              FROM payment p
              join settlement s on s.payment_id = p.id
              WHERE s.contract_id = _contract_id AND p.date <= _till_date;

        END;
    $BODY$
  LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
  COST 100;

CREATE OR REPLACE FUNCTION creditor_balance_increase(_creditor_id integer, _till_date date)
  RETURNS numeric AS
$BODY$
        BEGIN
            return coalesce(SUM(contract_balance_increase(c.id, _till_date)), 0) FROM contract c
                    WHERE c.creditor_id = _creditor_id;

        END;
    $BODY$
  LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
  COST 100;

CREATE OR REPLACE FUNCTION contract_balance_increase(_contract_id integer, _year integer)
  RETURNS numeric AS
$BODY$
        BEGIN
            return
              coalesce(SUM(p.amount), 0)
              FROM payment p
              join settlement s on p.id = s.payment_id
              WHERE s.contract_id = _contract_id AND year(p.date) = _year;

        END;
    $BODY$
  LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
  COST 100;

COMMIT;