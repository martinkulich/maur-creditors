BEGIN;

CREATE TABLE event_type
(
   code character varying NOT NULL,
   "name" character varying NOT NULL,
    PRIMARY KEY (code)
)
WITH (OIDS = FALSE);

INSERT INTO event_type(code, name) VALUES
('tournament','Tournament'),
('training','Training');


CREATE TABLE event
(
  id serial NOT NULL,
  event_type_code character varying NOT NULL,
  sport_id integer NOT NULL,
  "name" character varying NOT NULL,
  date date NOT NULL,
  capacity integer NOT NULL,
  players_count integer NOT NULL,
  perex text,
  descrip text,
  playground_id integer NOT NULL,
  CONSTRAINT event_pkey PRIMARY KEY (id),
  CONSTRAINT event_event_type_code_fkey FOREIGN KEY (event_type_code)
      REFERENCES event_type (code) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT event_playground_id_fkey FOREIGN KEY (playground_id)
      REFERENCES playground (id) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT event_sport_id_fkey FOREIGN KEY (sport_id)
      REFERENCES sport (id) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE RESTRICT
)
WITH (
  OIDS=FALSE
);


CREATE TABLE participation
(
   id serial NOT NULL,
   event_id integer NOT NULL,
   user_id integer,
   note text,
   created_at timestamp without time zone NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (event_id) REFERENCES event (id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES security_user (id) ON UPDATE CASCADE ON DELETE CASCADE
)
WITH (  OIDS = FALSE);

CREATE TABLE coplayer
(
   id serial NOT NULL,
   participation_id integer NOT NULL,
   user_id integer ,
   note text,
    PRIMARY KEY (id),
    FOREIGN KEY (participation_id) REFERENCES participation (id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES security_user (id) ON UPDATE CASCADE ON DELETE SET NULL
)
WITH (OIDS = FALSE);

INSERT INTO security_perm(code, "name", is_public) VALUES
('tournament.admin', 'Tournament administration', true),
('training.admin', 'Training administration', true);


COMMIT;