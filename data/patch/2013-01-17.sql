BEGIN;
  CREATE TABLE report
  (
    code character varying NOT NULL,
    name character varying NOT NULL,
    CONSTRAINT report_code_pkey PRIMARY KEY (code)
  )
  WITH (OIDS=FALSE);


CREATE TABLE contract_excluded_report
(
  contract_id integer,
  report_code character varying
)
WITH (OIDS=FALSE);

ALTER TABLE contract_excluded_report ALTER COLUMN contract_id SET NOT NULL;
ALTER TABLE contract_excluded_report ALTER COLUMN report_code SET NOT NULL;
ALTER TABLE contract_excluded_report ADD CONSTRAINT contract_excluded_report_pkey PRIMARY KEY (contract_id, report_code);
ALTER TABLE contract_excluded_report ADD CONSTRAINT contract_excluded_report_contract_id_fkey FOREIGN KEY (contract_id) REFERENCES contract (id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE contract_excluded_report ADD CONSTRAINT contract_excluded_report_report_code_fkey FOREIGN KEY (report_code) REFERENCES report (code) ON UPDATE CASCADE ON DELETE CASCADE;

insert into report(code, "name") values
('regulation', 'Předpisy'),
('creditor_revenue', 'Výnosy věřitelů'),
('unpaid', 'Nevyplacené úroky'),
('to_pay', 'K vyplacení');

to_pay
COMMIT;