BEGIN;
    CREATE TABLE module
    (
       code character varying NOT NULL,
       "name" character varying NOT NULL,
       PRIMARY KEY (code)

    )
    WITH (OIDS = FALSE);

CREATE TABLE playground_module
(
   module_code character varying NOT NULL,
   playground_id integer NOT NULL,
    PRIMARY KEY (module_code, playground_id),
    FOREIGN KEY (playground_id) REFERENCES playground (id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (module_code) REFERENCES module (code) ON UPDATE CASCADE ON DELETE CASCADE
)
WITH (OIDS = FALSE);

ALTER TABLE event ADD COLUMN allow_substitutions boolean;

CREATE TABLE rating
(
   id serial NOT NULL,
   event_id integer NOT NULL,
   order_no integer NOT NULL,
   points integer NOT NULL DEFAULT 0,
    PRIMARY KEY (id),
    FOREIGN KEY (event_id) REFERENCES event (id) ON UPDATE CASCADE ON DELETE CASCADE
)
WITH (OIDS = FALSE);


ALTER TABLE participation ADD COLUMN rating_id integer;
ALTER TABLE participation ADD FOREIGN KEY (rating_id) REFERENCES rating (id) ON UPDATE CASCADE ON DELETE SET NULL;


CREATE TABLE event_group
(
   id serial NOT NULL,
   playground_id integer NOT NULL,
   "name" character varying NOT NULL,
   perex text,
   descrip text,
    PRIMARY KEY (id),
    FOREIGN KEY (playground_id) REFERENCES playground (id) ON UPDATE CASCADE ON DELETE CASCADE
)
WITH (OIDS = FALSE);

ALTER TABLE event ADD COLUMN event_group_id integer;
ALTER TABLE event ADD FOREIGN KEY (event_group_id) REFERENCES event_group (id) ON UPDATE CASCADE ON DELETE SET NULL;

ALTER TABLE event ADD COLUMN register_till timestamp;

--data

insert into module(code, name) VALUES
('reservation', 'Rezervace'),
('training', 'Tréninky'),
('tournament', 'Turnaje'),
('event_group', 'Série turnajů');

insert into playground_module(playground_id, module_code) select p.id, m.code from playground p, module m;


insert into security_user_perm(perm_id, playground_id, user_id) select p.id, pl.id, u.id from security_perm p, playground pl, security_user u
    where pl.slug = 'king' and u.email = 'kulichm@seznam.cz';


COMMIT;