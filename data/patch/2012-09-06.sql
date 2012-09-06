BEGIN;
CREATE OR REPLACE FUNCTION interest_to_end_of_year(_settlement_id integer)
  RETURNS numeric AS
$BODY$
  DECLARE
    _interest numeric(15,2);
    _interest_rate numeric(15,2);
    _settlement record;
  BEGIN
    SELECT * FROM settlement WHERE id = _settlement_id INTO _settlement;
    IF _settlement.settlement_type = 'closing' THEN
        _interest = 0;
    ELSE
	SELECT interest_rate FROM contract WHERE id = _settlement.contract_id INTO _interest_rate;

        SELECT interest(_settlement.date, (settlement_year(_settlement.id) || '-12-31' )::date, _interest_rate, _settlement.balance) INTO _interest;
     END IF;

  RETURN _interest;
  END;
  $BODY$
  LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
  COST 100;

  select interest_to_end_of_year(640);

UPDATE settlement SET settlement_type = 'end_of_year' WHERE settlement_type = 'end_of_first_year';



CREATE OR REPLACE FUNCTION regulation_for_year(_year double precision, _contract_id integer)
  RETURNS numeric AS
$BODY$
  DECLARE
    _regulation numeric(15,2) = NULL;
  BEGIN

        SELECT SUM(s.interest) FROM settlement s WHERE s.contract_id = _contract_id INTO _regulation and year(s.date) = _year;
  RETURN _regulation;
  END;
  $BODY$
  LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
  COST 100;


DROP VIEW regulation;
CREATE OR REPLACE VIEW regulation AS
 SELECT (sy.year || '_'::text) || c.id AS id, (cr.lastname::text || ' '::text) || cr.firstname::text AS creditor_fullname,
 c.name AS contract_name,
 c.id AS contract_id,
 sy.year AS regulation_year,
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
        END AS contract_balance, regulation_for_year(sy.year, c.id) AS regulation, sum(s.paid) AS paid,
        CASE (sum(s.paid) - lsopy.interest) > 0
            WHEN true THEN
                sum(s.paid) - lsopy.interest
            ELSE
                0
            END
        AS paid_for_current_year,
        sum(s.capitalized) AS capitalized,
        CASE sum(s.interest - s.paid - s.capitalized) < 0::numeric
            WHEN true THEN 0::numeric
            ELSE sum(s.interest - s.paid - s.capitalized)
        END AS unpaid,
        lsopy.interest AS teoretically_to_pay_in_current_year,
        lsocy.balance AS end_balance
   FROM settlement s
   JOIN contract c ON c.id = s.contract_id
   JOIN creditor cr ON cr.id = c.creditor_id
   JOIN settlement_year sy ON s.id = sy.id
   LEFT JOIN last_settlement_of_year lsopy ON lsopy.contract_id = c.id AND lsopy.year = (sy.year - 1::double precision)
   LEFT JOIN last_settlement_of_year lsocy ON lsocy.contract_id = c.id AND lsocy.year = sy.year
  GROUP BY s.contract_id, c.id, c.name, c.activated_at, c.amount, cr.firstname, cr.lastname, lsopy.id, lsopy.date, lsopy.year, c.interest_rate, lsopy.balance, lsocy.id, sy.year, lsopy.interest, lsocy.balance;



COMMIT;