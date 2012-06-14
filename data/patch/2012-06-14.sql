BEGIN;
DROP VIEW regulation;

CREATE OR REPLACE VIEW regulation AS
 SELECT (sy.year || '_'::text) || c.id AS id, (cr.lastname::text || ' '::text) || cr.firstname::text AS creditor_fullname, c.name AS contract_name, c.id AS contract_id, sy.year AS regulation_year, ( SELECT settlement_balance_after_settlement(lsopf.id) AS settlement_balance_after_settlement) AS start_balance,
        CASE date_part('year'::text, c.activated_at)
            WHEN sy.year THEN c.activated_at
            ELSE NULL::date
        END AS contract_activated_at,
        CASE date_part('year'::text, c.activated_at)
            WHEN sy.year THEN c.amount
            ELSE NULL::numeric
        END AS contract_balance,
        regulation_for_year(sy.year, c.id) AS regulation,
        sum(s.paid) AS paid, sum(s.paid) - interest_to_end_of_year(lsopf.id) AS paid_for_current_year,
        sum(s.capitalized) AS capitalized,
        CASE sum(s.interest - s.paid - s.capitalized) < 0 WHEN TRUE THEN 0 ELSE sum(s.interest - s.paid - s.capitalized) END AS unpaid,
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