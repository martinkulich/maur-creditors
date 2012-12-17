BEGIN;

DROP VIEW IF EXISTS last_settlement_of_year;
DROP VIEW IF EXISTS regulation;

DROP FUNCTION IF EXISTS interest(date, date, numeric, numeric);
DROP FUNCTION IF EXISTS interest_to_end_of_year(integer);
DROP FUNCTION IF EXISTS last_contract_settelent_date_of_year(integer);
DROP FUNCTION IF EXISTS last_contract_settelent_date_of_year(double precision, integer);
DROP FUNCTION IF EXISTS multiplication(numeric, numeric);
DROP FUNCTION IF EXISTS regulation_years();
DROP FUNCTION IF EXISTS paid(integer, integer);
DROP FUNCTION IF EXISTS paid_for_current_year(integer, integer);
DROP FUNCTION IF EXISTS regulation_for_year(integer, integer);
DROP FUNCTION IF EXISTS unpaid(integer, integer);
DROP FUNCTION IF EXISTS amount_in_currency(numeric, text, text);
DROP FUNCTION IF EXISTS capitalized(integer, integer);
DROP FUNCTION IF EXISTS end_balance(integer, integer);
DROP FUNCTION IF EXISTS settlement_balance_after_settlement(integer);
DROP FUNCTION IF EXISTS settlement_unpaid(integer);
DROP FUNCTION IF EXISTS settlement_year(integer);
DROP FUNCTION IF EXISTS teoretically_to_pay_in_current_year(integer, integer);



CREATE OR REPLACE FUNCTION contract_unpaid(_contract_id integer, _till_date date)
  RETURNS numeric AS
$BODY$
        BEGIN
            return contract_interest(_contract_id, _till_date) - contract_capitalized(_contract_id, _till_date) - contract_paid(_contract_id, _till_date);
        END;
    $BODY$
  LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
  COST 100;


CREATE OR REPLACE FUNCTION contract_paid(_contract_id integer, _year integer)
  RETURNS numeric AS
$BODY$

        BEGIN

            RETURN (
                SELECT COALESCE(sum(s.paid), 0) 
                FROM settlement s
                JOIN outgoing_payment op ON op.id = s.outgoing_payment_id

                WHERE contract_id = _contract_id AND year(op.date) = _year);

        END;
    $BODY$
  LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
  COST 100;

CREATE OR REPLACE FUNCTION contract_paid_for_current_year(_contract_id integer, _year integer)
  RETURNS numeric AS
$BODY$
        DECLARE
            _paid_for_current_year numeric(15,2);
            _has_previous_year boolean;
        BEGIN

            SELECT count(s.id)>0 FROM settlement s WHERE year(s.date) = _year- 1 AND s.contract_id = _contract_id INTO _has_previous_year;

            SELECT contract_paid(_contract_id , _year) INTO _paid_for_current_year;

            IF _has_previous_year  THEN
                _paid_for_current_year = _paid_for_current_year - contract_unpaid(_contract_id , last_day(12, _year-1));
            END IF;

            IF _paid_for_current_year > 0 THEN
                RETURN _paid_for_current_year;
            ELSE
                RETURN 0;
            END IF;

        END;
    $BODY$
  LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
  COST 100;


CREATE OR REPLACE FUNCTION contract_interest(_contract_id integer, _year integer)
  RETURNS numeric AS
$BODY$
        BEGIN
            return coalesce(SUM(s.interest), 0) FROM settlement s WHERE s.contract_id = _contract_id AND year(s.date) = _year;
        END;
    $BODY$
  LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
  COST 100;

CREATE OR REPLACE FUNCTION contract_capitalized(_contract_id integer, _year integer)
  RETURNS numeric AS
    $BODY$

    BEGIN
        RETURN (SELECT COALESCE(sum(s.capitalized), 0) FROM settlement s WHERE contract_id = _contract_id AND year(s.date) = _year);
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
           FROM settlement s1
      JOIN outgoing_payment op ON op.id = s1.outgoing_payment_id
     WHERE s1.contract_id = c.id)) OR contract_unpaid(c.id, last_day(12, ry.id))::integer <> 0 AND ry.id <= year(now()::date));


COMMIT;