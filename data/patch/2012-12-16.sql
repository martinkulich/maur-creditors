BEGIN;
    
    INSERT INTO security_perm(code, name, is_public) VALUES ('report-to-pay', 'K vyplacení', true);


CREATE OR REPLACE FUNCTION previous_settlement(_contract_id integer, _date date)
    RETURNS settlement AS $$
    DECLARE
     result settlement;
    BEGIN
      SELECT * INTO result FROM settlement where contract_id = _contract_id AND date <= _date ORDER BY date DESC limit 1;

      return result ;
    END
$$ language plpgsql;

CREATE OR REPLACE FUNCTION previous_regular_settlement(_contract_id integer, _date date)
    RETURNS settlement AS $$
    DECLARE
     result settlement;
    BEGIN
        SELECT * INTO result FROM settlement 
        where contract_id = _contract_id 
        AND date <= _date 
        AND settlement_type != 'end_of_year'
        ORDER BY date DESC limit 1;

      return result ;
    END
$$ language plpgsql;

CREATE OR REPLACE FUNCTION contract_unpaid(_contract_id integer, _till_date date, _use_date_of_settlement boolean)
  RETURNS numeric AS
$BODY$

        BEGIN

            return contract_interest(_contract_id, _till_date) - contract_capitalized(_contract_id, _till_date) - contract_paid(_contract_id, _till_date, _use_date_of_settlement);
                

        END;
    $BODY$
  LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
  COST 100;

CREATE OR REPLACE FUNCTION contract_unpaid_regular(_contract_id integer, _till_date date, _use_date_of_settlement boolean)
  RETURNS numeric AS
$BODY$

        BEGIN

            return contract_interest_regular(_contract_id, _till_date) - contract_capitalized(_contract_id, _till_date) - contract_paid(_contract_id, _till_date, _use_date_of_settlement);
                

        END;
    $BODY$
  LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
  COST 100;


CREATE OR REPLACE FUNCTION contract_paid(_contract_id integer, _till_date date, _use_date_of_settlement boolean)
  RETURNS numeric AS
$BODY$

        BEGIN
            IF _use_date_of_settlement = false THEN 
                RETURN contract_paid(_contract_id, _till_date);
            ELSE
                RETURN  
                    coalesce(SUM(s.paid), 0) 
                    FROM settlement s 
                    JOIN outgoing_payment op ON op.id = s.outgoing_payment_id
                    WHERE s.contract_id = _contract_id 
                    AND s.date <= _till_date;
            end if;

        END;
    $BODY$
  LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
  COST 100;

COMMIT;