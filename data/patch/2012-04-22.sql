BEGIN;

CREATE TABLE sale
(
   id serial NOT NULL,
   playground_id integer NOT NULL,
   "name" character varying NOT NULL,
   amount numeric(9,2) NOT NULL,
   curt_count integer NOT NULL,
    PRIMARY KEY (id),
   CONSTRAINT sale_playground_id_fkey FOREIGN KEY (playground_id) REFERENCES playground (id) ON UPDATE CASCADE ON DELETE CASCADE
)
WITH (OIDS = FALSE);

CREATE INDEX sale_playground_id_uidx
  ON sale
  USING btree
  (playground_id);

CREATE TABLE sale_price
(
   sale_id integer NOT NULL,
   price_id integer NOT NULL,
    PRIMARY KEY (sale_id, price_id),
   CONSTRAINT sale_price_sale_id_fkey FOREIGN KEY (sale_id) REFERENCES sale (id) ON UPDATE CASCADE ON DELETE CASCADE,
   CONSTRAINT sale_price_price_id_fkey FOREIGN KEY (price_id) REFERENCES price (id) ON UPDATE CASCADE ON DELETE CASCADE
)
WITH (OIDS = FALSE);

CREATE INDEX sale_price_sale_id_uidx
  ON sale_price
  USING btree
  (sale_id);

CREATE INDEX sale_price_price_id_uidx
  ON sale_price
  USING btree
  (price_id);

CREATE TABLE sale_price_category
(
   sale_id integer NOT NULL,
   price_category_id integer NOT NULL,
    PRIMARY KEY (sale_id, price_category_id),
   CONSTRAINT sale_price_category_sale_id_fkey FOREIGN KEY (sale_id) REFERENCES sale (id) ON UPDATE CASCADE ON DELETE CASCADE,
   CONSTRAINT sale_price_category_price_category_id_fkey FOREIGN KEY (price_category_id) REFERENCES price_category (id) ON UPDATE CASCADE ON DELETE CASCADE
)
WITH (OIDS = FALSE);

CREATE INDEX sale_price_category_sale_id_uidx
  ON sale_price_category
  USING btree
  (sale_id);

CREATE INDEX sale_price_category_price_category_id_uidx
  ON sale_price_category
  USING btree
  (price_category_id);


CREATE TABLE schedule_sale
(
   schedule_id integer NOT NULL,
   sale_id integer NOT NULL,
    PRIMARY KEY (schedule_id, sale_id),
   CONSTRAINT schedule_sale_schedule_id_fkey FOREIGN KEY (schedule_id) REFERENCES schedule (id) ON UPDATE CASCADE ON DELETE CASCADE,
   CONSTRAINT schedule_sale_sale_id_fkey FOREIGN KEY (sale_id) REFERENCES sale (id) ON UPDATE CASCADE ON DELETE CASCADE
)
WITH (OIDS = FALSE);

CREATE INDEX schedule_sale_schedule_id_uidx
  ON schedule_sale
  USING btree
  (schedule_id);

CREATE INDEX schedule_sale_sale_id_uidx
  ON schedule_sale
  USING btree
  (sale_id);


ALTER TABLE reservation ADD COLUMN schedule_id integer;

DELETE FROM reservation where id in (5004, 5005);

update
    reservation r
set
    schedule_id =
        (
            select tz.schedule_id
            from reservation_part rp
            join reservation_part_time_zone rptz on rp.id = rptz.reservation_part_id
            join time_zone tz on tz.id = rptz.time_zone_id
            where rp.reservation_id = r.id
            group by rp.reservation_id, tz.schedule_id
         );

delete from reservation where schedule_id is null;

ALTER TABLE reservation
  ADD CONSTRAINT reservation_schedule_id_fkey FOREIGN KEY (schedule_id)
      REFERENCES schedule (id) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE reservation ALTER COLUMN schedule_id SET NOT NULL;

CREATE INDEX reservation_schedule_id_uidx
  ON reservation
  USING btree
  (schedule_id);


INSERT INTO security_perm(code, "name") VALUES
('sale.admin', 'Sale administration');
SELECT SETVAL('security_perm_id_seq', (SELECT MAX(id) FROM security_perm));

COMMIT;