BEGIN;
ALTER TABLE settlement ADD COLUMN currency_rate numeric(15,2) NOT NULL DEFAULT 1;

update settlement set currency_rate = 25 where contract_id in (select id from contract where currency_code = 'EUR');

CREATE OR REPLACE FUNCTION multiplication(_number1 numeric, _number2 numeric )
  RETURNS numeric AS
$BODY$
       
        BEGIN

            

            RETURN _number1 * _number2;

        END;
    $BODY$
  LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
  COST 100;



COMMIT;