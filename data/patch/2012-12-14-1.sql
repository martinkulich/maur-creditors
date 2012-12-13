BEGIN;
    
insert into security_perm(code, "name", is_public) values 
('login-as', 'Přihlásti se jako jiný uživatel', false);

COMMIT;