BEGIN;

ALTER TABLE reservation_part_time_zone ADD COLUMN period integer;

UPDATE reservation_part_time_zone rptz set period = (select s.period from schedule s join time_zone tz on tz.schedule_id = s.id join reservation_part_time_zone rptz1 on rptz1.time_zone_id = tz.id and rptz.id = rptz1.id );
UPDATE reservation_part_time_zone rptz set amount = amount * period / 3600;

ALTER TABLE reservation_part_time_zone ALTER COLUMN period SET NOT NULL;


COMMIT;