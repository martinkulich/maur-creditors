BEGIN;
--byla spatne 
DROP VIEW unpaid;
DROP FUNCTION creditor_unpaid(integer, date);


CREATE OR REPLACE FUNCTION contract_balance(_contract_id integer, _date date)
  RETURNS numeric AS
$BODY$
        DECLARE 
            _last_settlement record;
            _contract record;
            _balance numeric(15,2);
        BEGIN

            SELECT * FROM contract c WHERE c.id = _contract_id INTO _contract;
            
            IF (_contract.closed_at IS NULL AND _contract.activated_at is NOT NULL AND _contract.activated_at::DATE <= _date::DATE) THEN
                SELECT * FROM settlement s WHERE s.contract_id = _contract_id AND s.date <= _date ORDER BY s.date DESC LIMIT 1 INTO _last_settlement;
                IF _last_settlement.id <> 0 THEN 
                    _balance = _last_settlement.balance + _last_settlement.capitalized - _last_settlement.balance_reduction;
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


CREATE OR REPLACE FUNCTION contract_capitalized(_contract_id integer, _till_date date)
  RETURNS numeric AS
$BODY$
        BEGIN
            return coalesce(SUM(s.capitalized), 0) FROM settlement s WHERE s.contract_id = _contract_id AND s.date <= _till_date;

        END;
    $BODY$
  LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
  COST 100;

CREATE OR REPLACE FUNCTION contract_balance_reduction(_contract_id integer, _till_date date)
  RETURNS numeric AS
$BODY$
        BEGIN
            return coalesce(SUM(s.balance_reduction), 0) FROM settlement s WHERE s.contract_id = _contract_id AND s.date <= _till_date;

        END;
    $BODY$
  LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
  COST 100;


CREATE OR REPLACE FUNCTION creditor_received_payments(_creditor_id integer, _till_date date)
  RETURNS numeric AS
$BODY$
        BEGIN
            return coalesce(SUM(contract_received_payments(c.id, _till_date)), 0) FROM contract c 
                    WHERE c.creditor_id = _creditor_id;

        END;
    $BODY$
  LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
  COST 100;


CREATE OR REPLACE FUNCTION contract_received_payments(_contract_id integer, _till_date date)
  RETURNS numeric AS
$BODY$
        BEGIN
            return coalesce(SUM(p.amount), 0) FROM payment p 
                    WHERE p.contract_id = _contract_id 
                    AND p.date <= _till_date
                    AND p.payment_type = 'payment';

        END;
    $BODY$
  LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
  COST 100;


CREATE OR REPLACE FUNCTION contract_interest_regular(_contract_id integer, _till_date date)
  RETURNS numeric AS
$BODY$

        BEGIN

        return COALESCE(SUM(s.interest),0) FROM settlement s 
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