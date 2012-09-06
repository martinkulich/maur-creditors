BEGIN;

ALTER TABLE settlement DROP CONSTRAINT settlement_date_contract_id_uidx;

DROP VIEW regulation;
CREATE OR REPLACE VIEW regulation AS
    SELECT (sy.year || '_'::text) || c.id AS id,
    (cr.lastname::text || ' '::text) || cr.firstname::text AS creditor_fullname,
    c.name AS contract_name,
    c.id AS contract_id,
    sy.year AS regulation_year,
    CASE year(c.activated_at)
        WHEN sy.year THEN
            CASE c.activated_at
                WHEN (year(c.activated_at) || '-01-01'::text)::date THEN
                    c.amount
                ELSE
                    NULL::numeric
             END
        ELSE
            lsopy.balance
        END AS start_balance,
        CASE year(c.activated_at)
            WHEN sy.year THEN
                c.activated_at
            ELSE
                NULL::date
        END AS contract_activated_at,
        CASE year(c.activated_at)
            WHEN sy.year THEN
                c.amount
            ELSE
                NULL::numeric
        END AS contract_balance,
        regulation_for_year(sy.year, c.id) AS regulation,
        sum(s.paid) AS paid,
        CASE year(c.activated_at)
            WHEN sy.year THEN
                sum(s.paid)
            ELSE
                CASE (sum(s.paid) - lsopy.interest) > 0
                    WHEN true THEN
                        sum(s.paid) - lsopy.interest
                    ELSE
                        0
                END
        END
        AS paid_for_current_year,
        sum(s.capitalized) AS capitalized,
        CASE sum(s.interest - s.paid - s.capitalized) < 0::numeric
            WHEN true THEN
                0::numeric
            ELSE
                sum(s.interest - s.paid - s.capitalized)
        END AS unpaid,
        CASE year(c.activated_at)
            WHEN sy.year THEN
               0
            ELSE
               lsopy.interest
        END AS teoretically_to_pay_in_current_year,
        lsocy.balance - lsocy.balance_reduction AS end_balance
   FROM settlement s
   JOIN contract c ON c.id = s.contract_id
   JOIN creditor cr ON cr.id = c.creditor_id
   JOIN settlement_year sy ON s.id = sy.id
   LEFT JOIN last_settlement_of_year lsopy ON lsopy.contract_id = c.id AND lsopy.year = (sy.year - 1::double precision)
   LEFT JOIN last_settlement_of_year lsocy ON lsocy.contract_id = c.id AND lsocy.year = sy.year
  GROUP BY s.contract_id, c.id, c.name, c.activated_at, c.amount, cr.firstname, cr.lastname, lsopy.id, lsopy.date, lsopy.year, c.interest_rate, lsopy.balance, lsocy.id, sy.year, lsopy.interest, lsocy.balance, lsocy.balance_reduction;



COMMIT;