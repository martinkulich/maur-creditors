BEGIN;
    
insert into security_perm(code, "name", is_public) values 
('report-regulation', 'Předpisy', true);


DROP VIEW regulation;

CREATE OR REPLACE VIEW regulation AS 
 SELECT (ry.id || '_'::text) || c.id AS id, 
    (cr.lastname::text || ' '::text) || cr.firstname::text AS creditor_fullname, 
    cr.id as creditor_id,
    c.name AS contract_name, c.id AS contract_id, ry.id AS regulation_year, 
    c.currency_code as contract_currency_code,
    contract_balance(c.id, first_day(1, ry.id), false) AS start_balance, 
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
    contract_balance(c.id, last_day(12, ry.id), true) AS end_balance
   FROM contract c, regulation_year ry, creditor cr
  WHERE cr.id = c.creditor_id AND (ry.id = year(c.activated_at) OR (ry.id IN ( SELECT DISTINCT year(s.date) AS year
           FROM settlement s
          WHERE s.contract_id = c.id)) OR (ry.id IN ( SELECT DISTINCT year(op.date) AS year
           FROM settlement s1
      JOIN outgoing_payment op ON op.id = s1.outgoing_payment_id
     WHERE s1.contract_id = c.id)) OR unpaid(c.id, ry.id)::integer <> 0 AND ry.id <= year(now()::date));

 

COMMIT;