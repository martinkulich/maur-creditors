BEGIN;
ALTER TABLE contract  ADD COLUMN first_settlement_date date;

DROP VIEW IF EXISTS regulation;
drop table IF EXISTS regulation_year;
CREATE OR REPLACE VIEW regulation_year AS
select distinct year(date) as id from settlement
union
select distinct year(activated_at) from contract
union
select distinct year(date_of_payment) from settlement where date_of_payment is not null;

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
    OR (unpaid(c.id, ry.id) != 0
)
AND ry.id <= year(now()::DATE));


COMMIT;