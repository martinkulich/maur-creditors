BEGIN;

ALTER TABLE contract ADD COLUMN debtor_id integer;
insert into creditor(lastname, identification_number, subject_type_code) values('VSDK a.s', '28990137', 'po');
update contract set debtor_id = (select id from creditor where identification_number = '28990137');

ALTER TABLE contract ALTER COLUMN debtor_id SET NOT NULL;

ALTER TABLE creditor RENAME TO subject;

ALTER SEQUENCE creditor_id_seq RENAME TO subject_id_seq;


ALTER TABLE contract ADD CONSTRAINT contract_debtor_id_fkey FOREIGN KEY (debtor_id) REFERENCES subject (id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE subject RENAME subject_type_code  TO subject_type_code;

update security_perm set code = 'subject.admin', name='Subjekty' where code = 'creditor.admin';


  DROP VIEW regulation;

  CREATE OR REPLACE VIEW regulation AS
   SELECT (ry.id || '_'::text) || c.id AS id,
      (cr.lastname::text || ' '::text) || cr.firstname::text AS creditor_fullname,
       (de.lastname::text || ' '::text) || de.firstname::text AS debtor_fullname,
      cr.id AS creditor_id,
      de.id AS debtor_id,
      c.name AS contract_name,
      c.id AS contract_id,
      ry.id AS regulation_year, c.currency_code AS contract_currency_code,
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
      contract_unpaid(c.id, last_day(12, ry.id - 1)) AS unpaid_in_past,
      contract_capitalized(c.id, ry.id) AS capitalized,
      contract_balance(c.id, last_day(12, ry.id), true) AS end_balance
     FROM contract c, regulation_year ry, subject cr, subject de
    WHERE cr.id = c.creditor_id
     and de.id = c.debtor_id
    AND (ry.id = year(c.activated_at) OR (ry.id IN ( SELECT DISTINCT year(s.date) AS year
             FROM settlement s
            WHERE s.contract_id = c.id)) OR (ry.id IN ( SELECT DISTINCT year(op.date) AS year
             FROM allocation a
        JOIN settlement s1 ON a.settlement_id = s1.id
     JOIN outgoing_payment op ON op.id = a.outgoing_payment_id
    WHERE s1.contract_id = c.id)) OR contract_unpaid(c.id, last_day(12, ry.id))::integer <> 0 AND ry.id <= year(now()::date));



COMMIT;