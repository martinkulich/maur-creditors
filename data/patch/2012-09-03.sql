DROP VIEW regulation;
DROP FUNCTION regulation_for_year(double precision, integer);
DROP FUNCTION interest_to_end_of_year(integer);


CREATE OR REPLACE FUNCTION regulation_for_year(_year double precision, _contract_id integer)
  RETURNS numeric AS
$BODY$
  DECLARE
    _contract_activated_at date;
    _contract_year double precision;
    _date_from date;
    _date_to date;
    _regulation numeric(15,2) = NULL;
    _balance numeric(15,2);
    _interest_rate numeric(15,2);
    _last_settlement_of_previous_year integer;
    _closing_settlement_date date;
  BEGIN
    _contract_activated_at = (SELECT activated_at FROM contract WHERE id = _contract_id);
    _closing_settlement_date = (SELECT MAX("date") FROM settlement WHERE contract_id = _contract_id AND settlement_type = 'closing');

     _date_to = (_year || '_12_31')::DATE;
    IF _closing_settlement_date IS NOT NULL THEN
        IF  date_part('year'::text, _closing_settlement_date) = _year THEN
            _date_to = _closing_settlement_date;
        END IF;
    END IF;

    IF _contract_activated_at IS NOT NULL THEN
    _contract_year =date_part('year'::text, _contract_activated_at);
    _interest_rate = (SELECT interest_rate FROM contract WHERE id = _contract_id);
        IF _contract_year < _year THEN
            _date_from = (_year-1 || '_12_31')::DATE;
            _last_settlement_of_previous_year = (select id FROM last_settlement_of_year lsoy WHERE lsoy.year = (_year - 1) AND lsoy.contract_id = _contract_id);
            _balance = settlement_balance_after_settlement(_last_settlement_of_previous_year);
        ELSE
            _date_from = _contract_activated_at;
            _balance = (SELECT amount FROM contract WHERE id = _contract_id);
        END IF;

        _regulation = interest(_date_from, _date_to, _interest_rate , _balance);
    END IF;
  RETURN _regulation;
  END;
  $BODY$
  LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
  COST 100;


CREATE OR REPLACE FUNCTION interest_to_end_of_year(_settlement_id integer)
  RETURNS numeric AS
$BODY$
  DECLARE
    _interest numeric(15,2);
    _is_closing_settlement bool;
  BEGIN
    SELECT settlement_type = 'closing' FROM settlement WHERE id = _settlement_id INTO _is_closing_settlement;

    IF _is_closing_settlement THEN
        _interest = 0;
    ELSE
        select  ((settlement_year(s.id) || '-12-31' )::date - s.date)::numeric/365::numeric * c.interest_rate/100 * s.balance
        from settlement s JOIN contract c ON c.id = s.contract_id
        where s.id = _settlement_id INTO _interest;
     END IF;

  RETURN _interest;
  END;
  $BODY$
  LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
  COST 100;

CREATE OR REPLACE VIEW regulation AS
 SELECT (sy.year || '_'::text) || c.id AS id, (cr.lastname::text || ' '::text) || cr.firstname::text AS creditor_fullname, c.name AS contract_name, c.id AS contract_id, sy.year AS regulation_year,
        CASE date_part('year'::text, c.activated_at)
            WHEN sy.year THEN
            CASE c.activated_at
                WHEN (date_part('year'::text, c.activated_at) || '-01-01'::text)::date THEN c.amount
                ELSE NULL::numeric
            END
            ELSE ( SELECT settlement_balance_after_settlement(lsopy.id) AS settlement_balance_after_settlement)
        END AS start_balance,
        CASE date_part('year'::text, c.activated_at)
            WHEN sy.year THEN c.activated_at
            ELSE NULL::date
        END AS contract_activated_at,
        CASE date_part('year'::text, c.activated_at)
            WHEN sy.year THEN c.amount
            ELSE NULL::numeric
        END AS contract_balance, regulation_for_year(sy.year, c.id) AS regulation, sum(s.paid) AS paid, sum(s.paid) - interest_to_end_of_year(lsopy.id) AS paid_for_current_year, sum(s.capitalized) AS capitalized,
        CASE sum(s.interest - s.paid - s.capitalized) < 0::numeric
            WHEN true THEN 0::numeric
            ELSE sum(s.interest - s.paid - s.capitalized)
        END AS unpaid, interest_to_end_of_year(lsocy.id) AS teoretically_to_pay_in_current_year, ( SELECT settlement_balance_after_settlement(lsocy.id) AS settlement_balance_after_settlement) AS end_balance
   FROM settlement s
   JOIN contract c ON c.id = s.contract_id
   JOIN creditor cr ON cr.id = c.creditor_id
   JOIN settlement_year sy ON s.id = sy.id
   LEFT JOIN last_settlement_of_year lsopy ON lsopy.contract_id = c.id AND lsopy.year = (sy.year - 1::double precision)
   LEFT JOIN last_settlement_of_year lsocy ON lsocy.contract_id = c.id AND lsocy.year = sy.year
  GROUP BY s.contract_id, c.id, c.name, c.activated_at, c.amount, cr.firstname, cr.lastname, lsopy.id, lsopy.date, lsopy.year, c.interest_rate, lsopy.balance, lsocy.id, sy.year;

