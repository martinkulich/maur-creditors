BEGIN;

CREATE OR REPLACE FUNCTION contract_unpaid(_contract_id integer, _till_date date)
  RETURNS numeric AS
$BODY$

        BEGIN

            return SUM(s.interest) - SUM(s.capitalized) - SUM(s.paid) FROM settlement s WHERE s.contract_id = _contract_id AND s.date <= _till_date;

        END;
    $BODY$
  LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
  COST 100;

CREATE OR REPLACE FUNCTION creditor_unpaid(_creditor_id integer, _till_date date)
  RETURNS numeric AS
$BODY$

        BEGIN

            return SUM(s.interest) - SUM(s.capitalized) - SUM(s.paid) FROM settlement s
            JOIN contract c on c.id = s.contract_id AND c.creditor_id = _creditor_id
            WHERE  s.date <= _till_date;

        END;
    $BODY$
  LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
  COST 100;

CREATE OR REPLACE VIEW unpaid AS
SELECT
    s.id as id,
    (cr.lastname::text || ' '::text) || cr.firstname::text AS creditor_fullname,
    cr.id as creditor_id,
    c.name as contract_name,
    c.id as contract_id,
    s.date as settlement_date,
    contract_unpaid(c.id, s.date) as contract_unpaid,
    creditor_unpaid(cr.id, s.date) as creditor_unpaid
FROM
    settlement s
join contract c ON c.id = s.contract_id
join creditor cr ON cr.id = c.creditor_id
order by s.date asc

INSERT INTO security_perm(code, name, is_public) VALUES
    ('unpaid.admin', 'Nevyplacené úroky', true);

COMMIT;