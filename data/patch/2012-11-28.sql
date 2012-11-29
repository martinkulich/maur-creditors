BEGIN;
    

CREATE OR REPLACE FUNCTION contract_unpaid_regular(_contract_id integer, _till_date date)
  RETURNS numeric AS
$BODY$

        BEGIN

        return COALESCE(SUM(s.interest),0) - COALESCE(SUM(s.capitalized),0) - COALESCE(SUM(s.paid),0) FROM settlement s 
            WHERE s.contract_id = _contract_id 
            AND (
                    s.date < _till_date 
                    OR 
                    ((s.date::date = _till_date::date)
                        AND 
                     (s.settlement_type != 'end_of_year')
                    )
                );


        END;
    $BODY$
  LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
  COST 100;
COMMIT;