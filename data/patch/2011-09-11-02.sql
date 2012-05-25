BEGIN;

ALTER TABLE mail ADD COLUMN sender character varying;

UPDATE mail set sender = 'noreplay@rezervuj.to';

ALTER TABLE mail ALTER COLUMN sender SET NOT NULL;

ALTER TABLE playground ADD COLUMN email character varying;


COMMIT;