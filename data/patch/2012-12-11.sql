BEGIN;
  
CREATE OR REPLACE FUNCTION contract_capitalized(_contract_id integer, _month integer, _year integer)
  RETURNS numeric AS
$BODY$
        BEGIN
            return coalesce(SUM(s.capitalized), 0) FROM settlement s 
                WHERE s.contract_id = _contract_id 
                AND year(s.date) = _year
                AND month(s.date) = _month;

        END;
    $BODY$
  LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
  COST 100;

CREATE OR REPLACE FUNCTION contract_received_payments(_contract_id integer, _month integer, _year integer)
  RETURNS numeric AS
$BODY$
        BEGIN
            return coalesce(SUM(p.amount), 0) FROM payment p 
                    WHERE p.contract_id = _contract_id 
                    AND year(p.date) = _year
                    AND month(p.date) = _month
                    AND p.payment_type = 'payment';

        END;
    $BODY$
  LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
  COST 100;


COMMIT;