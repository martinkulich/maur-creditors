BEGIN;

ALTER TABLE settlement ADD CONSTRAINT settlement_date_contract_id_uidx UNIQUE (contract_id, date);




CREATE OR REPLACE FUNCTION regulation_for_year(_year double precision, _contract_id integer)
  RETURNS numeric AS
$BODY$
  DECLARE
    _contract_activated_at date;
    _contract_year double precision;
    _date_from date;
    _date_to date;
    _regulation numeric(15,2) = NULL;
    _balance numeric(15,2);
    _interest_rate numeric(15,2);
    _last_settlement_of_previous_year integer;
    _closing_settlement_date date;
  BEGIN
    _contract_activated_at = (SELECT activated_at FROM contract WHERE id = _contract_id);
    _closing_settlement_date = (SELECT MAX("date") FROM settlement WHERE contract_id = _contract_id AND settlement_type = 'closing');

     _date_to = (_year || '_12_31')::DATE;
    IF _closing_settlement_date IS NOT NULL THEN
        IF  date_part('year'::text, _closing_settlement_date) = _year THEN
            _date_to = _closing_settlement_date;
        END IF;
    END IF;

    IF _contract_activated_at IS NOT NULL THEN
    _contract_year =date_part('year'::text, _contract_activated_at);
    _interest_rate = (SELECT interest_rate FROM contract WHERE id = _contract_id);
        IF _contract_year < _year THEN
            _date_from = (_year || '_01_01')::DATE;
            _last_settlement_of_previous_year = (select id FROM last_settlement_of_year lsoy WHERE lsoy.year = (_year - 1) AND lsoy.contract_id = _contract_id);
            _balance = settlement_balance_after_settlement(_last_settlement_of_previous_year);
            _regulation = _balance * _interest_rate / 100::numeric;
        ELSE
            _date_from = _contract_activated_at;
            _balance = (SELECT amount FROM contract WHERE id = _contract_id);
            _regulation = interest(_date_from, _date_to, _interest_rate , _balance);
        END IF;
    END IF;
  RETURN _regulation;
  END;
  $BODY$
  LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
  COST 100;
COMMIT;