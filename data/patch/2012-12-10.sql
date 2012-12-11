BEGIN;
    
insert into security_perm(code, "name", is_public) values 
('report-monthly', 'Měsíční přehled', true);

CREATE OR REPLACE FUNCTION contract_balance(_contract_id integer, _date date)
  RETURNS numeric AS
$BODY$
        DECLARE 
            _last_settlement record;
            _balance numeric(15,2);
            _contract record;
        BEGIN

            SELECT * FROM contract c WHERE c.id = _contract_id INTO _contract;
            IF (
                ( _contract.closed_at IS NULL OR (_contract.closed_at IS NOT NULL AND _contract.closed_at > _date))
                AND _contract.activated_at is NOT NULL 
                AND _contract.activated_at::DATE <= _date::DATE) 
            THEN
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


CREATE OR REPLACE FUNCTION month(_date date)
  RETURNS integer AS
$BODY$
      DECLARE
        month integer;
      BEGIN

      SELECT date_part('month'::text, _date)::integer INTO month;

        RETURN month;

      END;
      $BODY$
  LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
  COST 100;


CREATE OR REPLACE FUNCTION contract_paid(_contract_id integer, _month integer, _year integer)
  RETURNS numeric AS
$BODY$

        BEGIN

            return coalesce(SUM(s.paid), 0) FROM settlement s WHERE s.contract_id = _contract_id 
                AND year(s.date_of_payment) = _year
                AND month(s.date_of_payment) = _month;

        END;
    $BODY$
  LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
  COST 100;


CREATE OR REPLACE FUNCTION last_day(_month integer, _year integer)
RETURNS date AS
$BODY$
  SELECT (date_trunc('MONTH', ($2 || '-' || $1 || '-1')::DATE) + INTERVAL '1 MONTH - 1 day')::date;
$BODY$ LANGUAGE 'sql' IMMUTABLE STRICT;


COMMIT;