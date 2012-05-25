BEGIN;

ALTER TABLE reservation_part_time_zone ALTER amount TYPE numeric(9,2);
ALTER TABLE reservation_part_time_zone ADD COLUMN sale_id integer;
ALTER TABLE reservation_part_time_zone ADD COLUMN sale_amount numeric(9,2);
ALTER TABLE reservation_part_time_zone ADD CONSTRAINT reservation_part_time_zone_sale_id_fkey FOREIGN KEY (sale_id) REFERENCES sale (id) ON UPDATE CASCADE ON DELETE SET NULL;


COMMIT;