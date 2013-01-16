BEGIN;

CREATE OR REPLACE FUNCTION contract_balance(_contract_id integer, _date date, _with_capitalization_and_balance_reduction boolean)
  RETURNS numeric AS
$BODY$
        DECLARE
            _last_settlement record;
            _balance numeric(15,2);
            _contract record;
        BEGIN

            SELECT * FROM contract c WHERE c.id = _contract_id INTO _contract;
            IF (
                ( _contract.closed_at IS NULL OR (_contract.closed_at IS NOT NULL AND _contract.closed_at >= _date))
                AND _contract.activated_at is NOT NULL
                AND _contract.activated_at::DATE <= _date::DATE)
            THEN
                SELECT * FROM settlement s WHERE s.contract_id = _contract_id AND s.date <= _date ORDER BY s.date DESC LIMIT 1 INTO _last_settlement;
                IF _last_settlement.id <> 0 THEN
                    _balance = _last_settlement.balance;
                    IF _with_capitalization_and_balance_reduction = true THEN
                        _balance = _balance + _last_settlement.capitalized - settlement_balance_reduction(_last_settlement.id);
                    END IF;
                ELSE
                    _balance = _contract.amount;
                END IF;
            ELSE
                _balance = 0;
            END IF;
        return _balance;
        END;
    $BODY$
  LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
  COST 100;


ALTER TABLE payment RENAME bank_account  TO sender_bank_account;
ALTER TABLE payment ADD COLUMN bank_account_id integer;
ALTER TABLE payment ADD CONSTRAINT payment_bank_account_id_fkey FOREIGN KEY (bank_account_id) REFERENCES bank_account (id) ON UPDATE CASCADE ON DELETE RESTRICT;

UPDATE payment set bank_account_id = (select id from bank_account where name like 'Unicredit');

ALTER TABLE payment alter column bank_account_id set not null;


COMMIT;