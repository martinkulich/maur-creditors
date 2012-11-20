BEGIN;
    
    
CREATE OR REPLACE FUNCTION contract_paid(_contract_id integer, _till_date date)
  RETURNS numeric AS
$BODY$

        BEGIN

            return coalesce(SUM(s.paid), 0) FROM settlement s WHERE s.contract_id = _contract_id AND s.date_of_payment <= _till_date;

        END;
    $BODY$
  LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
  COST 100;

CREATE OR REPLACE FUNCTION contract_interest(_contract_id integer, _till_date date)
  RETURNS numeric AS
$BODY$

        BEGIN

            return coalesce(SUM(s.interest), 0) FROM settlement s WHERE s.contract_id = _contract_id AND s.date <= _till_date;

        END;
    $BODY$
  LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
  COST 100;


CREATE OR REPLACE FUNCTION contract_unpaid(_contract_id integer, _till_date date)
  RETURNS numeric AS
$BODY$

        BEGIN

            return COALESCE(SUM(s.interest),0) - COALESCE(SUM(s.capitalized),0) - COALESCE(SUM(s.paid),0) FROM settlement s WHERE s.contract_id = _contract_id AND s.date <= _till_date;

        END;
    $BODY$
  LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
  COST 100;
COMMIT;