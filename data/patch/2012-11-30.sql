BEGIN;
    
insert into security_perm(code, "name", is_public) values 
('report-unpaid', 'Nevyplacené úroky', true),
('report-balance', 'Jistiny', true),
('report-birthday', 'Narozeniny', true),
('report-creditor-revenue', 'Výnosy věřitelů', true);



COMMIT;