BEGIN;

CREATE TABLE reservation_part_time_zone
(
   id serial NOT NULL,
   reservation_part_id integer NOT NULL,
   time_zone_id integer NOT NULL,
   price_id integer NOT NULL,
   amount integer NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (reservation_part_id) REFERENCES reservation_part (id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (time_zone_id) REFERENCES time_zone (id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (price_id) REFERENCES price (id) ON UPDATE CASCADE ON DELETE CASCADE
)
WITH (OIDS = FALSE);

INSERT INTO reservation_part_time_zone(reservation_part_id, time_zone_id, price_id, amount)
(SELECT id, time_zone_id, price_id, amount from reservation_part);

ALTER TABLE reservation_part
    DROP COLUMN price_id,
    DROP COLUMN time_zone_id,
    DROP COLUMN amount;
COMMIT;