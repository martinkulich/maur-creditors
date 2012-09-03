BEGIN;

    INSERT INTO security_perm(code, name, is_public) VALUES
    ('rights.admin', 'Práva', true),
    ('creditor.admin', 'Věřitelé', true),
    ('contract.admin', 'Smlouvy', true),
    ('payment.admin', 'Platby', true),
    ('settlement.admin', 'Výplaty', true),
    ('regulation.admin', 'Předpisy', true),
    ('security_user.admin', 'Uživatelé', true),
    ('ip_address.admin', 'Ip adresy', true),
    ('settlement_manual_change', 'Manuální úpravy výplat úroků', true);
COMMIT;