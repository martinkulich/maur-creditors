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

insert into security_perm(code, "name", is_public) values ('report-debtor-cost', 'Náklady dlužníků', true);
COMMIT;

BEGIN;

update security_perm set name = 'Nevyplacené úroky věřitelům', code ='report-creditor-unpaid' where code ='report-unpaid';
insert into security_perm(code, "name", is_public) values ('report-debtor-unpaid', 'Nevyplacené úroky od dlužníků', true);
COMMIT;

BEGIN;

update report set code = 'creditor_balance', name='Jistiny po věřitelích' where code = 'balance';
update report set code = 'creditor_regulation', name='Předpisy věřitelů' where code = 'regulation';
update report set code = 'creditor_unpaid', name='Nevyplacené úroky věřitelům' where code = 'unpaid';

insert into report(code, name) values
('debtor_balance', 'Jistiny po dlužnících'),
('debtor_cost', 'Náklady dlužníků'),
('debtor_regulation', 'Předpisy dlužníků'),
('debtor_unpaid', 'Nevyplacené úroky od dlužníků');

COMMIT;