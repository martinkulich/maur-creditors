BEGIN;
    update security_perm set is_public = false where "code" = 'ip_address.admin';
    delete from security_perm where "code" = 'unpaid.admin';

    insert into security_perm(code, "name") values ('report.admin', 'Reporty');
COMMIT;