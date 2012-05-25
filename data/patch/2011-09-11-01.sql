BEGIN;

ALTER TABLE playground ADD COLUMN favorite_sport_id integer;
ALTER TABLE playground ADD FOREIGN KEY (favorite_sport_id) REFERENCES sport (id) ON UPDATE CASCADE ON DELETE RESTRICT;

UPDATE playground set favorite_sport_id = (select id from sport where slug = 'beach-volleyball');

ALTER TABLE playground ALTER COLUMN favorite_sport_id SET NOT NULL;


COMMIT;