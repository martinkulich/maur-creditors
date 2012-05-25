BEGIN;
ALTER TABLE reservation RENAME note  TO user_string;
ALTER TABLE reservation ADD COLUMN note text;

ALTER TABLE participation RENAME note  TO user_string;

ALTER TABLE coplayer RENAME note  TO user_string;

COMMIT;