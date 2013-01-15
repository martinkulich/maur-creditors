BEGIN;

    drop view if exists last_settlement_of_year;
    drop view if exists regulation;

    CREATE TABLE public.allocation
    (
       id serial NOT NULL,
       paid numeric NOT NULL DEFAULT 0,
       balance_reduction numeric NOT NULL DEFAULT 0,
       outgoing_payment_id integer NOT NULL,
       settlement_id integer NOT NULL,
       CONSTRAINT allocation_id_pkey PRIMARY KEY (id),
       CONSTRAINT allocation_outgoing_payment_id_fkey FOREIGN KEY (outgoing_payment_id) REFERENCES outgoing_payment (id) ON UPDATE CASCADE ON DELETE RESTRICT,
       CONSTRAINT allocation_settlement_id_fkey FOREIGN KEY (settlement_id) REFERENCES settlement (id) ON UPDATE CASCADE ON DELETE CASCADE
    )
    WITH (OIDS = FALSE);

insert into security_perm(code, "name", is_public) values
('allocation.admin', 'Alokace', true);



delete from allocation;
insert into allocation(paid, outgoing_payment_id, settlement_id)
(
  select paid, outgoing_payment_id, id from settlement where outgoing_payment_id is not null
);

alter table settlement drop column paid;

update settlement set outgoing_payment_id = null;

alter table outgoing_payment add column balance_reduction boolean not null default false;

INSERT INTO outgoing_payment(bank_account_id, creditor_id, amount, date, currency_code, balance_reduction)
(
    SELECT
    1,
    cr.id,
    SUM(s.balance_reduction),
    date,
    co.currency_code,
    true
    FROM settlement s
    JOIN contract co ON co.id = s.contract_id
    JOIN creditor cr ON cr.id = co.creditor_id
    WHERE balance_reduction IS NOT NULL
    AND balance_reduction <> 0
    GROUP BY
    s.date,
    cr.id,
    co.currency_code
);

CREATE OR REPLACE FUNCTION migrate_otgoing_payments_balance_reduction() RETURNS void AS
$BODY$
DECLARE
    _contract RECORD;
BEGIN

    FOR _contract IN SELECT * FROM contract LOOP
        UPDATE settlement s SET outgoing_payment_id =
        (
            SELECT id FROM outgoing_payment op WHERE op.creditor_id = _contract.creditor_id AND op.date = s.date and op.balance_reduction = true
        )
        WHERE contract_id = _contract.id
        AND balance_reduction is not null
        and balance_reduction <> 0
        ;
    END LOOP;

END;
$BODY$
LANGUAGE 'plpgsql' VOLATILE;

select migrate_otgoing_payments_balance_reduction();

insert into allocation(balance_reduction, outgoing_payment_id, settlement_id)
(
  select balance_reduction, outgoing_payment_id, id from settlement where outgoing_payment_id is not null
);

alter table settlement drop column balance_reduction;

alter table outgoing_payment drop column balance_reduction;

alter table settlement drop column outgoing_payment_id;



DROP FUNCTION contract_balance_reduction(integer, date);

CREATE OR REPLACE FUNCTION contract_balance_reduction(_contract_id integer, _till_date date)
  RETURNS numeric AS
$BODY$
        BEGIN
            return
              coalesce(SUM(a.balance_reduction), 0)
              FROM allocation a
              join settlement s on s.id = a.settlement_id
              WHERE s.contract_id = _contract_id AND s.date <= _till_date;

        END;
    $BODY$
  LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
  COST 100;


DROP FUNCTION contract_paid(integer, date);

CREATE OR REPLACE FUNCTION contract_paid(_contract_id integer, _till_date date)
  RETURNS numeric AS
$BODY$

        BEGIN

            return
                coalesce(SUM(a.paid), 0)
                FROM allocation a
                JOIN settlement s on a.settlement_id = s.id
                JOIN outgoing_payment op ON op.id = a.outgoing_payment_id
                WHERE s.contract_id = _contract_id
                AND op.date <= _till_date;

        END;
    $BODY$
  LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
  COST 100;

DROP FUNCTION contract_paid(integer, integer, integer);

CREATE OR REPLACE FUNCTION contract_paid(_contract_id integer, _month integer, _year integer)
  RETURNS numeric AS
$BODY$

        BEGIN

            return
                coalesce(SUM(a.paid), 0)
                FROM allocation a
                JOIN settlement s on s.id = a.settlement_id
                JOIN outgoing_payment op ON op.id = a.outgoing_payment_id
                WHERE s.contract_id = _contract_id
                AND year(op.date) = _year
                AND month(op.date) = _month;

        END;
    $BODY$
  LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
  COST 100;

DROP FUNCTION contract_paid(integer, integer);

CREATE OR REPLACE FUNCTION contract_paid(_contract_id integer, _year integer)
  RETURNS numeric AS
$BODY$

        BEGIN

            RETURN (
                SELECT COALESCE(sum(a.paid), 0)
                FROM allocation a
                JOIN settlement s ON s.id = a.settlement_id
                JOIN outgoing_payment op ON op.id = a.outgoing_payment_id

                WHERE contract_id = _contract_id AND year(op.date) = _year);

        END;
    $BODY$
  LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
  COST 100;

DROP FUNCTION contract_paid(integer, date, boolean);

CREATE OR REPLACE FUNCTION contract_paid(_contract_id integer, _till_date date, _use_date_of_settlement boolean)
  RETURNS numeric AS
$BODY$

        BEGIN
            IF _use_date_of_settlement = false THEN
                RETURN contract_paid(_contract_id, _till_date);
            ELSE
                RETURN
                    sum(settlement_paid(s.id))
                    FROM settlement s
                    WHERE s.contract_id = _contract_id
                    AND s.date <= _till_date;
            end if;

        END;
    $BODY$
  LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
  COST 100;

CREATE OR REPLACE FUNCTION contract_unpaid(_contract_id integer, _till_date date)
  RETURNS numeric AS
$BODY$

        BEGIN

            return contract_interest(_contract_id, _till_date) - contract_capitalized(_contract_id, _till_date) -  contract_paid(_contract_id, _till_date);

        END;
    $BODY$
  LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
  COST 100;

CREATE OR REPLACE FUNCTION settlement_paid(_settlement_id integer)
  RETURNS numeric AS
$BODY$
        BEGIN

            RETURN coalesce(SUM(a.paid), 0) FROM allocation a where a.settlement_id = _settlement_id;

        END;
    $BODY$
  LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
  COST 100;

CREATE OR REPLACE FUNCTION settlement_balance_reduction(_settlement_id integer)
  RETURNS numeric AS
$BODY$
        BEGIN

            RETURN coalesce(SUM(a.balance_reduction), 0) FROM allocation a where a.settlement_id = _settlement_id;

        END;
    $BODY$
  LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
  COST 100;


CREATE OR REPLACE FUNCTION contract_balance(_contract_id integer, _date date)
  RETURNS numeric AS
$BODY$
        BEGIN

           return contract_balance(_contract_id, _date, true);
        END;
    $BODY$
  LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
  COST 100;

CREATE OR REPLACE FUNCTION contract_balance(_contract_id integer, _date date, _with_capitalization_and_balance_reduction boolean)
  RETURNS numeric AS
$BODY$
        DECLARE
            _last_settlement record;
            _balance numeric(15,2);
            _contract record;
        BEGIN

            SELECT * FROM contract c WHERE c.id = _contract_id INTO _contract;
            IF (
                ( _contract.closed_at IS NULL OR (_contract.closed_at IS NOT NULL AND _contract.closed_at > _date))
                AND _contract.activated_at is NOT NULL
                AND _contract.activated_at::DATE <= _date::DATE)
            THEN
                SELECT * FROM settlement s WHERE s.contract_id = _contract_id AND s.date <= _date ORDER BY s.date DESC LIMIT 1 INTO _last_settlement;
                IF _last_settlement.id <> 0 THEN
                    _balance = _last_settlement.balance;
                    IF _with_capitalization_and_balance_reduction = true THEN
                        _balance = _balance + _last_settlement.capitalized - settlement_balance_reduction(_last_settlement.id);
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
SELECT
    (ry.id || '_'::text) || c.id AS id,
    (cr.lastname::text || ' '::text) || cr.firstname::text AS creditor_fullname,
    cr.id AS creditor_id,
    c.name AS contract_name,
    c.id AS contract_id,
    ry.id AS regulation_year,
    c.currency_code AS contract_currency_code,
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
    contract_unpaid(c.id, last_day(12, ry.id-1)) AS unpaid_in_past,
    contract_capitalized(c.id, ry.id) AS capitalized,
    contract_balance(c.id, last_day(12, ry.id), true) AS end_balance
   FROM contract c, regulation_year ry, creditor cr
  WHERE cr.id = c.creditor_id AND (ry.id = year(c.activated_at) OR (ry.id IN ( SELECT DISTINCT year(s.date) AS year
           FROM settlement s
          WHERE s.contract_id = c.id)) OR (ry.id IN ( SELECT DISTINCT year(op.date) AS year
           FROM allocation a
           join settlement s1 ON a.settlement_id = s1.id
      JOIN outgoing_payment op ON op.id = a.outgoing_payment_id
     WHERE s1.contract_id = c.id)) OR contract_unpaid(c.id, last_day(12, ry.id))::integer <> 0 AND ry.id <= year(now()::date));


COMMIT;