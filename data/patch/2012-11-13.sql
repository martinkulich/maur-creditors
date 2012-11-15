BEGIN;
    
CREATE OR REPLACE FUNCTION end_balance(_contract_id integer, _year integer)
  RETURNS numeric AS
$BODY$

        BEGIN

        RETURN (
            SELECT COALESCE(sum(lsoy.balance - lsoy.balance_reduction + lsoy.capitalized), 0)
            FROM last_settlement_of_year lsoy
            WHERE lsoy.contract_id = _contract_id
            AND year(lsoy.date) = _year
        );

        END;
    $BODY$
  LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
  COST 100;



CREATE OR REPLACE FUNCTION start_balance(_contract_id integer, _year integer)
  RETURNS numeric AS
$BODY$
        DECLARE
            _start_balance numeric(15,2);
            _contract record;

        BEGIN
            SELECT * FROM contract where id = _contract_id INTO _contract;
            IF year(_contract.closed_at) < _year THEN
                _start_balance = null;
            ELSEIF year(_contract.activated_at) = _year THEN
                IF (year(_contract.activated_at) || '-01-01'::text)::date  = _contract.activated_at THEN
                    _start_balance = _contract.amount;
                ELSE
                    _start_balance = NULL::NUMERIC;
                END IF;
            ELSE

                _start_balance = (
                    SELECT COALESCE(sum(lsoy.balance - lsoy.balance_reduction), 0)
                    FROM last_settlement_of_year lsoy
                    WHERE lsoy.contract_id = _contract_id
                    AND year(lsoy.date) = _year -1
                );
            END IF;

            RETURN _start_balance;
        END;
    $BODY$
  LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
  COST 100;

CREATE OR REPLACE VIEW regulation AS 
 SELECT (ry.id || '_'::text) || c.id AS id, 
    (cr.lastname::text || ' '::text) || cr.firstname::text AS creditor_fullname, 
    c.name AS contract_name, c.id AS contract_id, ry.id AS regulation_year, 
    start_balance(c.id, ry.id) AS start_balance, 
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
    end_balance(c.id, ry.id) AS end_balance
   FROM contract c, regulation_year ry, creditor cr
  WHERE cr.id = c.creditor_id AND (ry.id = year(c.activated_at) OR (ry.id IN ( SELECT DISTINCT year(s.date) AS year
           FROM settlement s
          WHERE s.contract_id = c.id)) OR (ry.id IN ( SELECT DISTINCT year(s1.date_of_payment) AS year
           FROM settlement s1
          WHERE s1.contract_id = c.id)) OR unpaid(c.id, ry.id)::integer <> 0 AND ry.id <= year(now()::date));




CREATE OR REPLACE FUNCTION contract_balance(_contract_id integer, _date date)
  RETURNS numeric AS
$BODY$
        DECLARE 
            _last_settlement record;
            _balance numeric(15,2);
        BEGIN
            select * from settlement s where s.contract_id = _contract_id and s.date <= _date order by s.date DESC limit 1 INTO _last_settlement;

            if _last_settlement.id <> 0 then 
            _balance = _last_settlement.balance + _last_settlement.capitalized - _last_settlement.balance_reduction;
 
            else
            _balance = (select amount from contract c where id = _contract_id);
     
            end if;
            
        return _balance;
        END;
    $BODY$
  LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
  COST 100;

COMMIT;