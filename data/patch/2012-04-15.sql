BEGIN;

CREATE TABLE reservation_part_curt
(
   reservation_part_id integer NOT NULL,
   curt_id integer NOT NULL,
    PRIMARY KEY (reservation_part_id, curt_id),
   CONSTRAINT reservation_part_curt_reservation_part_id_fkey FOREIGN KEY (reservation_part_id) REFERENCES reservation_part (id) ON UPDATE CASCADE ON DELETE CASCADE,
   CONSTRAINT reservation_part_curt_curt_id_fkey FOREIGN KEY (curt_id) REFERENCES curt (id) ON UPDATE CASCADE ON DELETE CASCADE
)
WITH (OIDS = FALSE);

CREATE INDEX reservation_part_curt_reservation_part_id_uidx
  ON reservation_part_curt
  USING btree
  (reservation_part_id);

CREATE INDEX reservation_part_curt_curt_id_uidx
  ON reservation_part_curt
  USING btree
  (curt_id);

INSERT INTO reservation_part_curt(reservation_part_id, curt_id) select id, curt_id from reservation_part;

ALTER TABLE reservation_part DROP COLUMN curt_id;


COMMIT;