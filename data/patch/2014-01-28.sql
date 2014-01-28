BEGIN;
    
insert into security_perm(code, "name", is_public) values 
('report-debtor-confirmation', 'Potvrzení pro dlužníky', true);

update security_perm set name = 'Potvrzení pro věřitele' where code ='report-creditor-confirmation';

insert into security_perm(code, "name", is_public) values
('report-to-receive', 'K obdržení tento měsíc', true);

insert into report(code, name) values('to_receive', 'K obržení');

update security_perm set name = 'Předpisy věřitelů', code ='report-creditor-regulation' where code ='report-regulation';
insert into security_perm(code, "name", is_public) values ('report-debtor-regulation', 'Předpisy dlužníků', true);

update security_perm set name = 'Předpisy věřitelů v daném měsíci', code ='report-creditor-regulation-monthly' where code ='report-regulation-monthly';
insert into security_perm(code, "name", is_public) values ('report-debtor-regulation-monthly', 'Předpisy dlužníků v daném měsíci', true);

update security_perm set name = 'Jistiny po věřitelích',code ='report-creditor-balance' where code ='report-balance';
insert into security_perm(code, "name", is_public) values ('report-debtor-balance', 'Jistiny po dlužnících', true);

update security_perm set name = 'Měsíčně k zaúčtování - věřitelé' where code ='report-creditors';
insert into security_perm(code, "name", is_public) values ('report-debtors', 'Měsíčně k zaúčtování - dlužníci', true);
COMMIT;