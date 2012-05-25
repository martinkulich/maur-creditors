BEGIN;
INSERT INTO security_perm(code, "name") VALUES
('report.admin', 'Report administration');
SELECT SETVAL('security_perm_id_seq', (SELECT MAX(id) FROM security_perm));

COMMIT;