BEGIN;
    
CREATE OR REPLACE FUNCTION contract_interest_regular(_contract_id integer, _till_date date)
  RETURNS numeric AS
$BODY$
        BEGIN
       return COALESCE(SUM(s.interest),0) FROM settlement s 
            WHERE s.contract_id = _contract_id 
            AND s.date <= (SELECT max(s.date) FROM settlement s where s.contract_id = _contract_id AND s.settlement_type != 'end_of_year' AND s.date <= _till_date);


        END;
    $BODY$
  LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
  COST 100;

COMMIT;