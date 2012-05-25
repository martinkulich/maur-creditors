BEGIN;
DELETE FROM reservation;

DELETE FROM time_zone;
DELETE FROM price;
DELETE FROM schedule;
DELETE FROM currency;
DELETE FROM security_perm;
DELETE FROM security_role;
DELETE FROM security_user;

DELETE FROM curt;
DELETE FROM playground;

DELETE FROM sport;

DELETE FROM price;

INSERT INTO currency(code) VALUES ('CZK');


INSERT INTO security_user(id, email, password, firstname, surname) VALUES
(1, 'kulichm@seznam.cz', md5('heslo'), 'Martin', 'Kulich'),
(2, 'petr.svoboda@seznam.cz', md5('heslo'), 'Petr', 'Svoboda'),
(3, 'jan.novak@seznam.cz', md5('heslo'), 'Jan', 'Novák');
SELECT SETVAL('security_user_id_seq', (SELECT MAX(id) FROM security_user));


INSERT INTO security_perm(id, code, "name") VALUES
(1, 'curt.admin', 'Curt administration'),
(2, 'schedule.admin', 'Schedule administration'),
(3, 'price.admin', 'Price administration'),
(4, 'rights.admin', 'Rights administration'),
(5, 'reservation.admin', 'Reservation administration');
SELECT SETVAL('security_perm_id_seq', (SELECT MAX(id) FROM security_perm));

INSERT INTO playground(id, "name", slug) VALUES
(1, 'BEACH KLUB LADVI', 'beach-ladvi'),
(2, 'BEACH STRAHOV', 'beach-strahov');
SELECT SETVAL('playground_id_seq', (SELECT MAX(id) FROM playground));

INSERT INTO security_user_perm(user_id, perm_id, playground_id) SELECT 1, p.id, 1 FROM  security_perm p;




INSERT INTO sport(id, name, slug) VALUES
(1, 'Beach volleyball', 'beach-volleyball'),
(2, 'Tenis', 'tenis');
SELECT SETVAL('sport_id_seq', (SELECT MAX(id) FROM sport));

INSERT INTO curt(id, playground_id, "name", order_no) VALUES
(1, 1, '1.', 1),
(2, 1, '2.', 2),
(3, 1, '3.', 3),
(4, 1, '4.', 4),
(5, 1, '5.', 5),
(6, 1, '6.', 6),
(7, 1, '7.', 7),
(8, 2, '1-1.', 1);
SELECT SETVAL('curt_id_seq', (SELECT MAX(id) FROM curt));

INSERT INTO curt_sport(curt_id, sport_id) VALUES
(1, 1),
(2, 2),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 2);


INSERT INTO playground_user(playground_id, user_id) VALUES (1,1);
INSERT INTO playground_sport(playground_id, sport_id) VALUES
(1,1),
--(1,2),
(2,1),
(2,2);


INSERT INTO price(id, playground_id, amount, currency_code, "name", active, color) VALUES
(1, 1, 100, 'CZK', 'zakladni', true, '#42a1f5'),
(2, 1, 50, 'CZK', 'levna', true, '#f55842'),
(3, 2, 200, 'CZK', 'cizi', true, '#42f57d');
SELECT SETVAL('price_id_seq', (SELECT MAX(id) FROM price));


COMMIT;