BEGIN;


INSERT INTO security_user(id, email, password, firstname, surname) VALUES
(1, 'kulichm@seznam.cz', md5('heslo'), 'Martin', 'Kulich');
SELECT SETVAL('security_user_id_seq', (SELECT MAX(id) FROM security_user));



COMMIT;