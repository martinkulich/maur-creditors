BEGIN;

DROP VIEW IF EXISTS regulation;

CREATE TABLE regulation_year
(
  id integer NOT NULL,
  CONSTRAINT regulation_year_pkey PRIMARY KEY (id)
)
WITH (OIDS=FALSE);

insert INTO regulation_year(id) values
(2010),
(2011),
(2012),
(2013),
(2014);


-------------------------

DROP FUNCTION IF EXISTS regulation_for_year(double precision, integer);
CREATE OR REPLACE FUNCTION regulation_for_year(_contract_id integer, _year integer)
  RETURNS numeric AS
$BODY$
  DECLARE
    _regulation numeric(15,2) = NULL;
  BEGIN

        SELECT COALESCE(SUM(s.interest), 0) FROM settlement s WHERE s.contract_id = _contract_id and year(s.date) = _year INTO _regulation ;
  RETURN _regulation;
  END;
  $BODY$
  LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
  COST 100;


CREATE OR REPLACE FUNCTION paid(_contract_id integer, _year integer)
    RETURNS numeric AS
    $BODY$

        BEGIN

            RETURN (SELECT COALESCE(sum(s.paid), 0) FROM settlement s WHERE contract_id = _contract_id AND year(s.date_of_payment) = _year);

        END;
    $BODY$
    LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
    COST 100;

CREATE OR REPLACE FUNCTION paid_for_current_year(_contract_id integer, _year integer)
    RETURNS numeric AS
    $BODY$
        DECLARE
            _paid_for_current_year numeric(15,2);
            _has_previous_year boolean;
        BEGIN

            SELECT count(s.id)>0 FROM settlement s WHERE year(s.date) = _year- 1 AND s.contract_id = _contract_id INTO _has_previous_year;

            SELECT paid(_contract_id , _year) INTO _paid_for_current_year;

            IF _has_previous_year  THEN
                _paid_for_current_year = _paid_for_current_year - unpaid(_contract_id , _year-1);
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

CREATE OR REPLACE FUNCTION unpaid(_contract_id integer, _year integer)
    RETURNS numeric AS
    $BODY$
        DECLARE
            _unpaid numeric(15, 2);
            _paid_for_current_year numeric(15, 2);
            _regulation_for_year numeric(15, 2);
            _capitalized numeric(15, 2);
            _has_previous_year boolean;
            _has_current_year boolean;
        BEGIN
            SELECT count(s.id)>0 FROM settlement s WHERE year(s.date) = _year AND s.contract_id = _contract_id INTO _has_current_year;

            SELECT count(s.id)>0 FROM settlement s WHERE year(s.date) = _year- 1 AND s.contract_id = _contract_id INTO _has_previous_year;

            _unpaid = NULL::NUMERIC;
            IF _has_current_year  THEN
                SELECT COALESCE(regulation_for_year(_contract_id, _year) - capitalized(_contract_id, _year) - paid(_contract_id, _year),0) INTO _unpaid;
            END IF;

            IF _has_previous_year  THEN
                _unpaid = _unpaid + unpaid(_contract_id , _year-1);
            END IF;

           return _unpaid;


        END;
    $BODY$
    LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
    COST 100;


CREATE OR REPLACE FUNCTION capitalized(_contract_id integer, _year integer)
    RETURNS numeric AS
    $BODY$

        BEGIN

            RETURN (SELECT COALESCE(sum(s.capitalized), 0) FROM settlement s WHERE contract_id = _contract_id AND year(s.date) = _year);

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

            IF year(_contract.activated_at) = _year THEN
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


CREATE OR REPLACE FUNCTION end_balance(_contract_id integer, _year integer)
    RETURNS numeric AS
    $BODY$

        BEGIN

        RETURN (
            SELECT COALESCE(sum(lsoy.balance - lsoy.balance_reduction), 0)
            FROM last_settlement_of_year lsoy
            WHERE lsoy.contract_id = _contract_id
            AND year(lsoy.date) = _year
        );

        END;
    $BODY$
    LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
    COST 100;

CREATE OR REPLACE FUNCTION regulation_years()
    RETURNS numeric AS
    $BODY$

        BEGIN

        RETURN (
            SELECT COALESCE(sum(lsoy.balance - lsoy.balance_reduction), 0)
            FROM last_settlement_of_year lsoy
            WHERE lsoy.contract_id = _contract_id
            AND year(lsoy.date) = _year
        );

        END;
    $BODY$
    LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
    COST 100;

---------------------------------------------------
DROP VIEW IF EXISTS regulation;
CREATE OR REPLACE VIEW regulation AS
SELECT (ry.id || '_'::text) || c.id AS id,
    (cr.lastname::text || ' '::text) || cr.firstname::text AS creditor_fullname,
    c.name AS contract_name,
    c.id AS contract_id,
    ry.id AS regulation_year,
    start_balance(c.id, ry.id) as start_balance,
    CASE year(c.activated_at)
        WHEN ry.id THEN
            c.activated_at
        ELSE
            NULL::date
        END
        AS contract_activated_at,
    CASE year(c.activated_at)
        WHEN ry.id THEN
            c.amount
        ELSE
            NULL::numeric
        END AS contract_balance,
    regulation_for_year(c.id, ry.id) AS regulation,
    paid(c.id, ry.id) AS paid,
    paid_for_current_year(c.id, ry.id) AS paid_for_current_year,
    unpaid(c.id, ry.id) AS unpaid,
    unpaid(c.id, ry.id-1) AS unpaid_in_past,
    capitalized(c.id, ry.id) AS capitalized,
    end_balance(c.id, ry.id) as end_balance

FROM
    contract c,
    regulation_year ry,
    creditor cr

WHERE cr.id = c.creditor_id
--AND ry.id <= year(now()::DATE)
--AND
--(
--    (c.closed_at IS NOT NULL AND ry.id <= year(c.closed_at))
--    OR c.closed_at IS NULL
--)
AND
(
    ry.id = year(c.activated_at)
    OR ry.id IN (SELECT DISTINCT year(s.date) FROM settlement s WHERE s.contract_id = c.id)
    OR ry.id IN (SELECT DISTINCT year(s1.date_of_payment) FROM settlement s1 WHERE s1.contract_id = c.id)
);


COMMIT;