BEGIN;

ALTER TABLE ip_address ADD COLUMN "name" character varying;

update ip_address set "name" = ip_address;
ALTER TABLE ip_address ALTER COLUMN "name" SET NOT NULL;
COMMIT;