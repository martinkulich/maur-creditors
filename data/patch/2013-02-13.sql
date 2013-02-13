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
                    IF _with_capitalization_and_balance_reduction = true OR _last_settlement.date < _date THEN
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
COMMIT;