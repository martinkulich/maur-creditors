BEGIN;
   
CREATE TABLE bank_account
(
   id serial NOT NULL, 
   name character varying NOT NULL, 
   "number" character varying NOT NULL, 
   CONSTRAINT bank_account_id_pkey PRIMARY KEY (id)
) 
WITH (
  OIDS = FALSE
)
;


CREATE TABLE outgoing_payment
(
   id serial NOT NULL, 
   bank_account_id integer, 
   amount numeric(15,2) NOT NULL DEFAULT 0, 
   date date NOT NULL, 
   note text,
   CONSTRAINT outgoing_payment_id_pkey PRIMARY KEY (id), 
   CONSTRAINT outgouing_payment_bank_account_id_fkey FOREIGN KEY (bank_account_id) REFERENCES bank_account (id) ON UPDATE CASCADE ON DELETE SET NULL
) 
WITH (
  OIDS = FALSE
)
;

ALTER TABLE settlement ADD COLUMN outgoing_payment_id integer;
ALTER TABLE settlement ADD CONSTRAINT settlement_outgoing_payment_id_fkey FOREIGN KEY (outgoing_payment_id) REFERENCES outgoing_payment (id) ON UPDATE CASCADE ON DELETE SET NULL;


ALTER TABLE outgoing_payment ADD COLUMN currency_code character(3) NOT NULL;
ALTER TABLE outgoing_payment DROP CONSTRAINT outgouing_payment_bank_account_id_fkey;

ALTER TABLE outgoing_payment
  ADD CONSTRAINT outgouing_payment_bank_account_id_fkey FOREIGN KEY (bank_account_id) REFERENCES bank_account (id) MATCH SIMPLE ON UPDATE CASCADE ON DELETE SET NULL;

ALTER TABLE outgoing_payment
  ADD CONSTRAINT outgoing_payment_currency_code_fkey FOREIGN KEY (currency_code) REFERENCES currency (code) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE outgoing_payment
  ADD COLUMN creditor_id integer NOT NULL;
ALTER TABLE outgoing_payment
  DROP CONSTRAINT outgoing_payment_currency_code_fkey;
ALTER TABLE outgoing_payment
  DROP CONSTRAINT outgouing_payment_bank_account_id_fkey;
ALTER TABLE outgoing_payment
  ADD CONSTRAINT outgoing_payment_currency_code_fkey FOREIGN KEY (currency_code)
      REFERENCES currency (code) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE outgoing_payment
  ADD CONSTRAINT outgouing_payment_bank_account_id_fkey FOREIGN KEY (bank_account_id)
      REFERENCES bank_account (id) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE SET NULL;
ALTER TABLE outgoing_payment
  ADD CONSTRAINT outgoing_payment_creditor_id_fkey FOREIGN KEY (creditor_id) REFERENCES creditor (id) ON UPDATE CASCADE ON DELETE RESTRICT;



insert into security_perm(code, "name", is_public) values 
('bank_account.admin', 'Bankovní účty', true),
('outgoing_payment.admin', 'Odchozí platby', true);

DELETE FROM bank_account;
INSERT INTO bank_account(id, "name", "number") values
    (1, 'raiffeisenbank', 0),
    (2, 'CSOB', 1);

DELETE FROM outgoing_payment;
INSERT INTO outgoing_payment(bank_account_id, creditor_id, amount, date, currency_code) 
(
    SELECT 
    1,
    cr.id,
    SUM(s.paid),
    date_of_payment,
    co.currency_code
    FROM settlement s 
    JOIN contract co ON co.id = s.contract_id
    JOIN creditor cr ON cr.id = co.creditor_id
    WHERE paid IS NOT NULL
    AND paid <> 0 
    AND date_of_payment IS NOT NULL
    GROUP BY
    s.date_of_payment, 
    cr.id,
    co.currency_code
);

update settlement set date_of_payment = null where paid is null or paid = 0;
CREATE OR REPLACE FUNCTION migrate_otgoing_payments() RETURNS void AS
$BODY$
DECLARE
    _contract RECORD;
BEGIN

    FOR _contract IN SELECT * FROM contract LOOP
        UPDATE settlement SET outgoing_payment_id = 
        (
            SELECT id FROM outgoing_payment op WHERE op.creditor_id = _contract.creditor_id AND op.date = date_of_payment
        ) 
        WHERE contract_id = _contract.id
        AND date_of_payment is not null
        AND paid is not null
        and paid <> 0
        ;
    END LOOP;

END;
$BODY$
LANGUAGE 'plpgsql' VOLATILE;

select migrate_otgoing_payments();



CREATE OR REPLACE VIEW regulation_year AS 
(         
    SELECT DISTINCT year(settlement.date) AS id
    FROM settlement
    UNION 
    SELECT DISTINCT year(contract.activated_at) AS id
    FROM contract
    UNION 
    SELECT DISTINCT year(outgoing_payment.date) AS id
    FROM outgoing_payment
    WHERE outgoing_payment.date IS NOT NULL
);

DROP VIEW regulation;

CREATE OR REPLACE VIEW regulation AS 
 SELECT (ry.id || '_'::text) || c.id AS id, 
    (cr.lastname::text || ' '::text) || cr.firstname::text AS creditor_fullname, 
    c.name AS contract_name, c.id AS contract_id, ry.id AS regulation_year, 
    contract_balance(c.id, first_day(1, ry.id), false) AS start_balance, 
        CASE year(c.activated_at)
            WHEN ry.id THEN c.activated_at
            ELSE NULL::date
        END AS contract_activated_at, 
        CASE year(c.activated_at)
            WHEN ry.id THEN c.amount
            ELSE NULL::numeric
        END AS contract_balance, 
    regulation_for_year(c.id, ry.id) AS regulation, paid(c.id, ry.id) AS paid, 
    paid_for_current_year(c.id, ry.id) AS paid_for_current_year, 
    unpaid(c.id, ry.id) AS unpaid, unpaid(c.id, ry.id - 1) AS unpaid_in_past, 
    capitalized(c.id, ry.id) AS capitalized, 
    contract_balance(c.id, last_day(12, ry.id), true) AS end_balance
   FROM contract c, regulation_year ry, creditor cr
  WHERE cr.id = c.creditor_id 
    AND (ry.id = year(c.activated_at) OR (ry.id IN ( SELECT DISTINCT year(s.date) AS year
           FROM settlement s
          WHERE s.contract_id = c.id)) OR (ry.id IN ( SELECT DISTINCT year(op.date) AS year
           FROM settlement s1 
           JOIN outgoing_payment op ON op.id = s1.outgoing_payment_id
          WHERE s1.contract_id = c.id)) OR unpaid(c.id, ry.id)::integer <> 0 AND ry.id <= year(now()::date));


ALTER TABLE settlement DROP COLUMN date_of_payment;



CREATE OR REPLACE FUNCTION paid(_contract_id integer, _year integer)
  RETURNS numeric AS
$BODY$

        BEGIN

            RETURN (
                SELECT COALESCE(sum(s.paid), 0) 
                FROM settlement s
                JOIN outgoing_payment op ON op.id = s.outgoing_payment_id

                WHERE contract_id = _contract_id AND year(op.date) = _year);

        END;
    $BODY$
  LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
  COST 100;


CREATE OR REPLACE FUNCTION contract_paid(_contract_id integer, _till_date date)
  RETURNS numeric AS
$BODY$

        BEGIN

            return 
                coalesce(SUM(s.paid), 0) 
                FROM settlement s 
                JOIN outgoing_payment op ON op.id = s.outgoing_payment_id
                WHERE s.contract_id = _contract_id 
                AND op.date <= _till_date;

        END;
    $BODY$
  LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
  COST 100;


CREATE OR REPLACE FUNCTION contract_paid(_contract_id integer, _month integer, _year integer)
  RETURNS numeric AS
$BODY$

        BEGIN

            return 
                coalesce(SUM(s.paid), 0) 
                FROM settlement s 
                JOIN outgoing_payment op ON op.id = s.outgoing_payment_id
                WHERE s.contract_id = _contract_id 
                AND year(op.date) = _year
                AND month(op.date) = _month;

        END;
    $BODY$
  LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
  COST 100;

COMMIT;