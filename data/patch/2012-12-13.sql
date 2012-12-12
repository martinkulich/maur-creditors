BEGIN;

CREATE OR REPLACE FUNCTION first_day(_month integer, _year integer)
  RETURNS date AS
$BODY$
  SELECT ($2 || '-' || $1 || '-1')::DATE;
$BODY$
  LANGUAGE sql IMMUTABLE STRICT
  COST 100;

CREATE OR REPLACE FUNCTION last_day(_month integer, _year integer)
  RETURNS date AS
$BODY$
  SELECT (date_trunc('MONTH', first_day($1, $2)) + INTERVAL '1 MONTH - 1 day')::date;
$BODY$
  LANGUAGE sql IMMUTABLE STRICT
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
                        _balance = _balance + _last_settlement.capitalized - _last_settlement.balance_reduction;
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

DROP FUNCTION if exists start_balance(integer, integer);

DROP VIEW regulation;

CREATE OR REPLACE VIEW regulation AS 
 SELECT (ry.id || '_'::text) || c.id AS id, 
    (cr.lastname::text || ' '::text) || cr.firstname::text AS creditor_fullname, 
    c.name AS contract_name, c.id AS contract_id, ry.id AS regulation_year, 
    contract_balance(c.id, first_day(1, ry.id), false) AS start_balance, 
        CASE year(c.activated_at)
            WHEN ry.id THEN c.activated_at
            ELSE NULL::date
        END AS contract_activated_at, 
        CASE year(c.activated_at)
            WHEN ry.id THEN c.amount
            ELSE NULL::numeric
        END AS contract_balance, 
    regulation_for_year(c.id, ry.id) AS regulation, paid(c.id, ry.id) AS paid, 
    paid_for_current_year(c.id, ry.id) AS paid_for_current_year, 
    unpaid(c.id, ry.id) AS unpaid, unpaid(c.id, ry.id - 1) AS unpaid_in_past, 
    capitalized(c.id, ry.id) AS capitalized, 
    contract_balance(c.id, last_day(12, ry.id), true) AS end_balance
   FROM contract c, regulation_year ry, creditor cr
  WHERE cr.id = c.creditor_id AND (ry.id = year(c.activated_at) OR (ry.id IN ( SELECT DISTINCT year(s.date) AS year
           FROM settlement s
          WHERE s.contract_id = c.id)) OR (ry.id IN ( SELECT DISTINCT year(s1.date_of_payment) AS year
           FROM settlement s1
          WHERE s1.contract_id = c.id)) OR unpaid(c.id, ry.id)::integer <> 0 AND ry.id <= year(now()::date));

COMMIT;