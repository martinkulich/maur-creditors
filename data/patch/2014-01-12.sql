BEGIN;

CREATE TABLE public.contract_type
(
   id serial,
   name character varying NOT NULL,
   CONSTRAINT contract_type_id_pkey PRIMARY KEY (id)
)
WITH (
  OIDS = FALSE
)
;


insert into contract_type(name) values
('Dluhopis'),
('Krátkodobá smlouva'),
('Dlouhodobá smlouva');


ALTER TABLE contract ADD COLUMN contract_type_id integer;

ALTER TABLE contract ADD CONSTRAINT contract_contract_type_id_fkey FOREIGN KEY (contract_type_id) REFERENCES contract_type (id) ON UPDATE CASCADE ON DELETE RESTRICT;

update contract set contract_type_id = (select id from contract_type where name = 'Dlouhodobá smlouva');

ALTER TABLE contract ALTER COLUMN contract_type_id SET NOT NULL;


insert into security_perm(code, "name", is_public) values
('contract_type.admin', 'Typy smluv', true);


--dokumenty smluv
ALTER TABLE contract ADD COLUMN file character varying;


COMMIT;