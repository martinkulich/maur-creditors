BEGIN;

ALTER TABLE reservation_part ADD COLUMN user_id integer;
ALTER TABLE reservation_part ADD COLUMN sport_id integer;
ALTER TABLE reservation_part ADD COLUMN price_category_id integer;
ALTER TABLE reservation_part ADD COLUMN reservation_repeating_id integer;
ALTER TABLE reservation_part ADD COLUMN date date;
ALTER TABLE reservation_part ADD COLUMN note text;
ALTER TABLE reservation_part ADD COLUMN paid boolean;
ALTER TABLE reservation_part ADD COLUMN created_by_user_id integer;
ALTER TABLE reservation_part ADD COLUMN created_at timestamp without time zone;
ALTER TABLE reservation_part ADD COLUMN schedule_id integer;

UPDATE reservation_part rp SET user_id = (SELECT user_id FROM reservation where id = rp.reservation_id);
UPDATE reservation_part rp SET sport_id = (SELECT sport_id FROM reservation where id = rp.reservation_id);
UPDATE reservation_part rp SET price_category_id = (SELECT price_category_id FROM reservation where id = rp.reservation_id);
UPDATE reservation_part rp SET reservation_repeating_id = (SELECT reservation_repeating_id FROM reservation where id = rp.reservation_id);
UPDATE reservation_part rp SET date = (SELECT date FROM reservation where id = rp.reservation_id);
UPDATE reservation_part rp SET note = (SELECT note FROM reservation where id = rp.reservation_id);
UPDATE reservation_part rp SET paid = (SELECT paid FROM reservation where id = rp.reservation_id);
UPDATE reservation_part rp SET created_by_user_id = (SELECT created_by_user_id FROM reservation where id = rp.reservation_id);
UPDATE reservation_part rp SET created_at = (SELECT created_at FROM reservation where id = rp.reservation_id);
UPDATE reservation_part rp SET schedule_id = (SELECT schedule_id FROM reservation where id = rp.reservation_id);

ALTER TABLE reservation_part ALTER COLUMN sport_id SET NOT NULL;
ALTER TABLE reservation_part ALTER COLUMN date SET NOT NULL;
ALTER TABLE reservation_part ALTER COLUMN created_by_user_id SET NOT NULL;
ALTER TABLE reservation_part ALTER COLUMN price_category_id SET NOT NULL;
ALTER TABLE reservation_part ALTER COLUMN schedule_id SET NOT NULL;

ALTER TABLE reservation_part DROP COLUMN reservation_id;

DROP TABLE reservation;

ALTER TABLE reservation_part RENAME TO reservation;
ALTER TABLE reservation_part_curt RENAME TO reservation_curt;
ALTER TABLE reservation_part_time_zone RENAME TO reservation_time_zone;

ALTER TABLE reservation_time_zone RENAME reservation_part_id  TO reservation_id;
ALTER TABLE reservation_curt RENAME reservation_part_id  TO reservation_id;

ALTER TABLE reservation_time_zone DROP CONSTRAINT reservation_part_time_zone_pkey;
ALTER TABLE reservation_time_zone ADD CONSTRAINT reservation_time_zone_pkey PRIMARY KEY(id);

ALTER TABLE reservation_time_zone DROP CONSTRAINT reservation_part_time_zone_price_id_fkey;
ALTER TABLE reservation_time_zone
  ADD CONSTRAINT reservation_time_zone_price_id_fkey FOREIGN KEY (price_id)
      REFERENCES price (id) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE reservation_time_zone DROP CONSTRAINT reservation_part_time_zone_reservation_part_id_fkey;
ALTER TABLE reservation_time_zone
  ADD CONSTRAINT reservation_time_zone_reservation_id_fkey FOREIGN KEY (reservation_id)
      REFERENCES reservation (id) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE reservation_time_zone DROP CONSTRAINT reservation_part_time_zone_sale_id_fkey;
ALTER TABLE reservation_time_zone
  ADD CONSTRAINT reservation_time_zone_sale_id_fkey FOREIGN KEY (sale_id)
      REFERENCES sale (id) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE SET NULL;

ALTER TABLE reservation_time_zone DROP CONSTRAINT reservation_part_time_zone_time_zone_id_fkey;
ALTER TABLE reservation_time_zone
  ADD CONSTRAINT reservation_time_zone_time_zone_id_fkey FOREIGN KEY (time_zone_id)
      REFERENCES time_zone (id) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE reservation_curt DROP CONSTRAINT reservation_part_curt_pkey;
ALTER TABLE reservation_curt
  ADD CONSTRAINT reservation_curt_pkey PRIMARY KEY(reservation_id, curt_id);

ALTER TABLE reservation_curt DROP CONSTRAINT reservation_part_curt_curt_id_fkey;
ALTER TABLE reservation_curt
  ADD CONSTRAINT reservation_curt_curt_id_fkey FOREIGN KEY (curt_id)
      REFERENCES curt (id) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE reservation_curt DROP CONSTRAINT reservation_part_curt_reservation_part_id_fkey;
ALTER TABLE reservation_curt
  ADD CONSTRAINT reservation_curt_reservation_id_fkey FOREIGN KEY (reservation_id)
      REFERENCES reservation (id) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE CASCADE;


ALTER TABLE reservation
  ADD CONSTRAINT "reservation_security_user_id_fkey" FOREIGN KEY (user_id)
      REFERENCES security_user (id) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE SET DEFAULT;

ALTER TABLE reservation
  ADD CONSTRAINT "reservation_reservation_repeating_id_fkey" FOREIGN KEY (reservation_repeating_id)
      REFERENCES reservation_repeating (id) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE SET DEFAULT;

ALTER TABLE reservation
  ADD CONSTRAINT "reservation_created_by_user_id_fkey" FOREIGN KEY (created_by_user_id)
      REFERENCES security_user (id) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE reservation
  ADD CONSTRAINT "reservation_sport_id_fkey" FOREIGN KEY (sport_id)
      REFERENCES sport (id) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE reservation
  ADD CONSTRAINT reservation_price_category_id_fkey FOREIGN KEY (price_category_id)
      REFERENCES price_category (id) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE reservation
  ADD CONSTRAINT reservation_schedule_id_fkey FOREIGN KEY (schedule_id)
      REFERENCES schedule (id) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE CASCADE;


DROP INDEX reservation_part_curt_curt_id_uidx;
CREATE INDEX reservation_curt_curt_id_uidx
  ON reservation_curt
  USING btree
  (curt_id);


DROP INDEX reservation_part_curt_reservation_part_id_uidx;
CREATE INDEX reservation_curt_reservation_id_uidx
  ON reservation_curt
  USING btree
  (reservation_id);


DROP INDEX reservation_part_time_zone_price_id_uidx;
CREATE INDEX reservationtime_zone_price_id_uidx
  ON reservation_time_zone
  USING btree
  (price_id);

DROP INDEX reservation_part_time_zone_reservation_part_id_uidx;
CREATE INDEX reservation_time_zone_reservation_id_uidx
  ON reservation_time_zone
  USING btree
  (reservation_id);

DROP INDEX reservation_part_time_zone_time_zone_id_uidx;
CREATE INDEX reservation_time_zone_time_zone_id_uidx
  ON reservation_time_zone
  USING btree
  (time_zone_id);


CREATE INDEX reservation_created_by_user_id_uidx
  ON reservation
  USING btree
  (created_by_user_id);

CREATE INDEX reservation_price_category_id_uidx
  ON reservation
  USING btree
  (price_category_id);

CREATE INDEX reservation_reservation_repeating_id_uidx
  ON reservation
  USING btree
  (reservation_repeating_id);

CREATE INDEX reservation_schedule_id_uidx
  ON reservation
  USING btree
  (schedule_id);

CREATE INDEX reservation_sport_id_uidx
  ON reservation
  USING btree
  (sport_id);


CREATE INDEX reservation_user_id_uidx
  ON reservation
  USING btree
  (user_id);

ALTER TABLE reservation_part_id_seq RENAME TO reservation_id_seq;
ALTER TABLE reservation_part_time_zone_id_seq RENAME TO reservation_time_zone_id_seq;


COMMIT;