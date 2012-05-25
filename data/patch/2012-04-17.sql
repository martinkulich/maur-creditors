BEGIN;

CREATE TABLE schedule_user
(
  schedule_id integer NOT NULL,
  user_id integer NOT NULL,
  CONSTRAINT schedule_user_pkey PRIMARY KEY (schedule_id, user_id),
  CONSTRAINT schedule_user_schedule_id_fkey FOREIGN KEY (schedule_id)
      REFERENCES schedule (id) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT schedule_user_user_id_fkey FOREIGN KEY (user_id)
      REFERENCES security_user (id) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE CASCADE
)
WITH (OIDS=FALSE);

-- Index: schedule_user_schedule_id_uidx

-- DROP INDEX schedule_user_schedule_id_uidx;

CREATE INDEX schedule_user_schedule_id_uidx
  ON schedule_user
  USING btree
  (schedule_id);

-- Index: schedule_user_user_id_uidx

-- DROP INDEX schedule_user_user_id_uidx;

CREATE INDEX schedule_user_user_id_uidx
  ON schedule_user
  USING btree
  (user_id);



CREATE TABLE schedule_security_group
(
  schedule_id integer NOT NULL,
  group_id integer NOT NULL,
  CONSTRAINT schedule_security_group_pkey PRIMARY KEY (group_id, schedule_id),
  CONSTRAINT schedule_security_group_group_id_fkey FOREIGN KEY (group_id)
      REFERENCES security_group (id) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT schedule_security_group_schedule_id_fkey FOREIGN KEY (schedule_id)
      REFERENCES schedule (id) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE CASCADE
)
WITH (
  OIDS=FALSE
);


-- Index: schedule_security_group_group_id_uidx

-- DROP INDEX schedule_security_group_group_id_uidx;

CREATE INDEX schedule_security_group_group_id_uidx
  ON schedule_security_group
  USING btree
  (group_id);

-- Index: schedule_security_group_schedule_id_uidx

-- DROP INDEX schedule_security_group_schedule_id_uidx;

CREATE INDEX schedule_security_group_schedule_id_uidx
  ON schedule_security_group
  USING btree
  (schedule_id);


ALTER TABLE schedule RENAME active  TO is_public;


COMMIT;