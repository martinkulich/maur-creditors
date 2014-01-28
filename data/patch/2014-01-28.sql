BEGIN;
    
insert into security_perm(code, "name", is_public) values 
('report-debtor-confirmation', 'Potvrzení pro dlužníky', true);

update security_perm set name = 'Potvrzení pro věřitele' where code ='report-creditor-confirmation';

insert into security_perm(code, "name", is_public) values
('report-to-receive', 'K obdržení tento měsíc', true);

insert into report(code, name) values('to_receive', 'K obržení');
COMMIT;