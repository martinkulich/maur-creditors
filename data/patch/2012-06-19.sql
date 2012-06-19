BEGIN;

CREATE OR REPLACE FUNCTION days_diff(_date_from date, _date_to date)
  RETURNS integer AS
$BODY$
  DECLARE
    _year_diff integer;
    _month_diff integer;
    _day_diff integer;
    _day_from integer;
    _day_to integer;
  BEGIN

	_year_diff =  (date_part('year', _date_to) - date_part('year', _date_from));
    _month_diff =  (date_part('month', _date_to) - date_part('month', _date_from));
    _day_from =  date_part('day', _date_from);
    IF _day_from = 31 THEN
    _day_from = 30;
    END IF;

    _day_to = date_part('day', _date_to);
    IF _day_to = 31 THEN
    _day_to = 30;
    END IF;

    _day_diff = _day_to - _day_from;

    RETURN  _year_diff * 360 + _month_diff * 30 + _day_diff;

  END;
  $BODY$
  LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
  COST 100;

CREATE OR REPLACE FUNCTION interest(_date_from date, _date_to date, _interest_rate numeric, _amount numeric)
  RETURNS numeric AS
$BODY$
  DECLARE
    _interest numeric(15,2);
  BEGIN

	_interest = days_diff(_date_from, _date_to)/360::numeric * _interest_rate/100 * _amount;

  RETURN _interest;
  END;
  $BODY$
  LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
  COST 100;



CREATE OR REPLACE VIEW regulation AS
    SELECT
        (sy.year || '_'::text) || c.id AS id, (cr.lastname::text || ' '::text) || cr.firstname::text AS creditor_fullname,
        c.name AS contract_name,
        c.id AS contract_id,
        sy.year AS regulation_year,
        CASE date_part('year'::text, c.activated_at)
            WHEN sy.year THEN
                CASE c.activated_at
                    WHEN (date_part('year'::text, c.activated_at) || '-01-01')::DATE THEN
                        c.amount
                    ELSE
                        NULL
                END
            ELSE
                ( SELECT settlement_balance_after_settlement(lsopf.id) )
        END AS start_balance,

        CASE date_part('year'::text, c.activated_at)
            WHEN sy.year THEN c.activated_at
            ELSE NULL::date
        END AS contract_activated_at,
        CASE date_part('year'::text, c.activated_at)
            WHEN sy.year THEN c.amount
            ELSE NULL::numeric
        END AS contract_balance, regulation_for_year(sy.year, c.id) AS regulation, sum(s.paid) AS paid, sum(s.paid) - interest_to_end_of_year(lsopf.id) AS paid_for_current_year, sum(s.capitalized) AS capitalized,
        CASE sum(s.interest - s.paid - s.capitalized) < 0::numeric
            WHEN true THEN 0::numeric
            ELSE sum(s.interest - s.paid - s.capitalized)
        END AS unpaid, interest_to_end_of_year(lsocf.id) AS teoretically_to_pay_in_current_year, ( SELECT settlement_balance_after_settlement(lsocf.id) AS settlement_balance_after_settlement) AS end_balance
   FROM settlement s
   JOIN contract c ON c.id = s.contract_id
   JOIN creditor cr ON cr.id = c.creditor_id
   JOIN settlement_year sy ON s.id = sy.id
   LEFT JOIN last_settlement_of_year lsopf ON lsopf.contract_id = c.id AND lsopf.year = (sy.year - 1::double precision)
   LEFT JOIN last_settlement_of_year lsocf ON lsocf.contract_id = c.id AND lsocf.year = sy.year
  GROUP BY s.contract_id, c.id, c.name, c.activated_at, c.amount, cr.firstname, cr.lastname, lsopf.id, lsopf.date, lsopf.year, c.interest_rate, lsopf.balance, lsocf.id, sy.year;




COMMIT;