BEGIN;

DELETE FROM reservation_part;
DELETE FROM reservation;

CREATE TABLE price_category
(
   id serial NOT NULL,
   playground_id integer NOT NULL,
   "name" character varying NOT NULL,
   is_public boolean,
    PRIMARY KEY (id),
    FOREIGN KEY (playground_id) REFERENCES playground (id) ON UPDATE CASCADE ON DELETE RESTRICT
)
WITH (
  OIDS = FALSE
)
;


CREATE TABLE price_amount
(
   price_id integer NOT NULL,
   price_category_id integer NOT NULL,
   amount integer,
    PRIMARY KEY (price_id, price_category_id),
    FOREIGN KEY (price_id) REFERENCES price (id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (price_category_id) REFERENCES price_category (id) ON UPDATE CASCADE ON DELETE CASCADE
)
WITH (
  OIDS = FALSE
)
;


DROP TABLE schedule_price;

DROP TABLE price_user;

ALTER TABLE price DROP COLUMN amount;

ALTER TABLE reservation ADD COLUMN price_category_id integer NOT NULL;
ALTER TABLE reservation ADD FOREIGN KEY (price_category_id) REFERENCES price_category (id) ON UPDATE CASCADE ON DELETE CASCADE;


CREATE TABLE price_category_user
(
  price_category_id integer NOT NULL,
  user_id integer NOT NULL,
  CONSTRAINT price_category_user_pkey PRIMARY KEY (price_category_id, user_id),
  CONSTRAINT price_category_user_price_category_id_fkey FOREIGN KEY (price_category_id)
      REFERENCES price_category (id) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT price_category_user_user_id_fkey FOREIGN KEY (user_id)
      REFERENCES security_user (id) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE CASCADE
)
WITH (
  OIDS=FALSE
);



ALTER TABLE reservation_part DROP CONSTRAINT "Ref_18";
ALTER TABLE reservation_part
   ALTER COLUMN price_id SET NOT NULL;
ALTER TABLE reservation_part ADD FOREIGN KEY (price_id) REFERENCES price (id) ON UPDATE CASCADE ON DELETE CASCADE;


INSERT INTO security_perm(id, code, "name") VALUES
(6, 'price_category.admin', 'Price category administration');
SELECT SETVAL('security_perm_id_seq', (SELECT MAX(id) FROM security_perm));

COMMIT;