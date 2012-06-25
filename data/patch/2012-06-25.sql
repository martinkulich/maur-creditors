BEGIN;

CREATE TABLE ip_address
(
   id serial NOT NULL,
   ip_address character varying NOT NULL,
   CONSTRAINT ip_address_id_pkey PRIMARY KEY (id),
   CONSTRAINT ip_address_ip_address_uidx UNIQUE (ip_address)
)
WITH (OIDS = FALSE);


COMMIT;