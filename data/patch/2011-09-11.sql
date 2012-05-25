BEGIN;

ALTER TABLE curt DROP CONSTRAINT "Ref_22";
ALTER TABLE curt ADD FOREIGN KEY (playground_id) REFERENCES playground (id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE security_group DROP CONSTRAINT security_group_playground_id_fkey;
ALTER TABLE security_group ADD FOREIGN KEY (playground_id) REFERENCES playground (id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE playground ADD COLUMN active boolean NOT NULL DEFAULT true;


COMMIT;