BEGIN;

CREATE OR REPLACE FUNCTION interest(_date_from date, _date_to date, _interest_rate numeric, _amount numeric)
  RETURNS numeric AS
$BODY$
  DECLARE
    _interest numeric(15,2);
  BEGIN

	_interest = ((_date_to - _date_from)::numeric)/365::numeric * _interest_rate/100 * _amount;

  RETURN _interest;
  END;
  $BODY$
  LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
  COST 100;

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
  BEGIN
    _contract_activated_at = (SELECT activated_at FROM contract WHERE id = _contract_id);
    _date_to = (_year || '_12_31')::DATE;
    IF _contract_activated_at IS NOT NULL THEN
    _contract_year =date_part('year'::text, _contract_activated_at);
    _interest_rate = (SELECT interest_rate FROM contract WHERE id = _contract_id);
        IF _contract_year < _year THEN
            _date_from = (_year || '_01_01')::DATE;
            _last_settlement_of_previous_year = (select id FROM last_settlement_of_year lsoy WHERE lsoy.year = (_year - 1) AND lsoy.contract_id = _contract_id);
            _balance = settlement_balance_after_settlement(_last_settlement_of_previous_year);
            _regulation = _balance * _interest_rate / 100::numeric;
        ELSE
            _date_from = _contract_activated_at;
            _balance = (SELECT amount FROM contract WHERE id = _contract_id);
            _regulation = interest(_date_from, _date_to, _interest_rate , _balance);
        END IF;
    END IF;
  RETURN _regulation;
  END;
  $BODY$
  LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
  COST 100;

DROP VIEW regulation;
CREATE OR REPLACE VIEW regulation AS
 SELECT (sy.year || '_'::text) || c.id AS id,
 cr.lastname || ' ' || cr.firstname AS creditor_fullname,
 c.name AS contract_name,
 c.id AS contract_id,
 sy.year AS regulation_year,
 ( SELECT settlement_balance_after_settlement(lsopf.id) AS settlement_balance_after_settlement) AS start_balance,
        CASE date_part('year'::text, c.activated_at)
            WHEN sy.year THEN c.activated_at
            ELSE NULL::date
        END AS contract_activated_at,

        CASE date_part('year'::text, c.activated_at)
            WHEN sy.year THEN c.amount
            ELSE NULL::numeric
        END AS contract_balance,
        regulation_for_year(sy.year, c.id) AS regulation,
        sum(s.paid) AS paid,
        sum(s.paid) - interest_to_end_of_year(lsopf.id) AS paid_for_current_year,
        sum(s.capitalized) AS capitalized,
	sum(s.interest - s.paid - s.capitalized) as unpaid,
        interest_to_end_of_year(lsocf.id) AS teoretically_to_pay_in_current_year,
        ( SELECT settlement_balance_after_settlement(lsocf.id)) AS end_balance
   FROM settlement s
   JOIN contract c ON c.id = s.contract_id
   JOIN creditor cr ON cr.id = c.creditor_id
   JOIN settlement_year sy ON s.id = sy.id
   LEFT JOIN last_settlement_of_year lsopf ON lsopf.contract_id = c.id AND lsopf.year = (sy.year - 1::double precision)
   LEFT JOIN last_settlement_of_year lsocf ON lsocf.contract_id = c.id AND lsocf.year = sy.year
  GROUP BY s.contract_id, c.id, c.name, c.activated_at, c.amount, cr.firstname, cr.lastname, lsopf.id, lsopf.date, lsopf.year, c.interest_rate, lsopf.balance, lsocf.id, sy.year;



COMMIT;