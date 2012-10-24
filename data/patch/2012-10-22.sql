BEGIN;
    INSERT INTO security_perm(code, name, is_public) VALUES ('currency.admin', 'Měny', true);

    ALTER TABLE currency ADD COLUMN is_default boolean NOT NULL DEFAULT false;
    ALTER TABLE currency ADD COLUMN rate numeric(15,2) NOT NULL DEFAULT 1;

COMMIT;

BEGIN;

CREATE OR REPLACE FUNCTION amount_in_currency(_amount numeric, _from_currency_code string, _to_currency_code string)
  RETURNS numeric AS
$BODY$
        DECLARE
            _from_currency_rate numeric;
            _to_currency_rate numeric;
        BEGIN

            SELECT rate FROM currency where code = _from_currency_code INTO _from_currency_rate;
            SELECT rate FROM currency where code = _to_currency_code INTO _to_currency_rate;

            RETURN _amount * _from_currency_rate / _to_currency_rate;

        END;
    $BODY$
  LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
  COST 100;
COMMIT;