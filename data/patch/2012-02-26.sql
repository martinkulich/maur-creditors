BEGIN;

    ALTER TABLE playground ADD COLUMN is_public boolean NOT NULL DEFAULT TRUE;
    ALTER TABLE security_user ADD COLUMN is_super_admin boolean NOT NULL DEFAULT false;


COMMIT;