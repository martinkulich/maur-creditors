BEGIN;
    ALTER TABLE settlement DROP COLUMN currency_rate;


CREATE OR REPLACE FUNCTION contract_balance_reduction(_contract_id integer, _year integer)
  RETURNS numeric AS
$BODY$
        BEGIN
            return
              coalesce(SUM(a.balance_reduction), 0)
              FROM allocation a
              join settlement s on s.id = a.settlement_id
              WHERE s.contract_id = _contract_id AND year(s.date) = _year;

        END;
    $BODY$
  LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
  COST 100;

CREATE OR REPLACE FUNCTION contract_received_payments(_contract_id integer,  _year integer)
  RETURNS numeric AS
$BODY$
        BEGIN
            return coalesce(SUM(p.amount), 0) FROM payment p
                    WHERE p.contract_id = _contract_id
                    AND year(p.date) = _year
                    AND p.payment_type = 'payment';

        END;
    $BODY$
  LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
  COST 100;
COMMIT;