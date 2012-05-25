BEGIN;
CREATE TABLE security_group
(
   id serial NOT NULL,
   playground_id integer NOT NULL,
   "name" character varying NOT NULL,
   is_public boolean NOT NULL DEFAULT true,
    PRIMARY KEY (id),
    FOREIGN KEY (playground_id) REFERENCES playground (id) ON UPDATE CASCADE ON DELETE RESTRICT
)
WITH (OIDS = FALSE);

INSERT INTO security_group(playground_id, name) SELECT id, 'Standartní uživatel' FROM playground;
SELECT SETVAL('security_group_id_seq', (SELECT MAX(id) FROM security_group));

CREATE TABLE security_user_group
(
   user_id integer NOT NULL,
   group_id integer NOT NULL,
    PRIMARY KEY (user_id, group_id),
    FOREIGN KEY (group_id) REFERENCES security_group (id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES security_user (id) ON UPDATE CASCADE ON DELETE CASCADE
)
WITH (OIDS = FALSE);


CREATE TABLE price_category_security_group
(
   price_category_id integer NOT NULL,
   group_id integer NOT NULL,
    PRIMARY KEY (group_id, price_category_id),
    FOREIGN KEY (price_category_id) REFERENCES price_category (id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (group_id) REFERENCES security_group (id) ON UPDATE CASCADE ON DELETE CASCADE
)
WITH (OIDS = FALSE);




CREATE TABLE mail
(
   id serial NOT NULL, 
   playground_id integer NOT NULL, 
   recipients text NOT NULL, 
   subject character varying NOT NULL, 
   message text,
   send boolean NOT NULL default false,
   created_at timestamp without time zone NOT NULL, 
   created_by_user_id integer NOT NULL, 
    PRIMARY KEY (id), 
    FOREIGN KEY (playground_id) REFERENCES playground (id) ON UPDATE CASCADE ON DELETE RESTRICT,
    FOREIGN KEY (created_by_user_id) REFERENCES security_user (id) ON UPDATE CASCADE ON DELETE RESTRICT
) 
WITH (
  OIDS = FALSE
)
;

ALTER TABLE security_perm ADD COLUMN is_public boolean NOT NULL DEFAULT false;

UPDATE security_perm SET is_public = true;


INSERT INTO security_perm(code, "name", is_public) VALUES
('show_non_public_perms', 'Zobrazit neveřejná práva', false),
('mail.admin', 'Mail administration', true),
('sport.admin', 'Sport administration', true),
('playground.admin', 'Playground administration', false),
('user.admin', 'User administration', true),
('group.admin', 'group administration', true);




COMMIT;