BEGIN;

CREATE OR REPLACE FUNCTION interest_to_end_of_year(_settlement_id integer)
  RETURNS numeric AS
$BODY$
  DECLARE
    _interest numeric(15,2);
    _interest_rate numeric(15,2);
    _settlement record;
  BEGIN
    SELECT * FROM settlement WHERE id = _settlement_id INTO _settlement;
    IF _settlement.settlement_type = 'closing' THEN
        _interest = 0;
    ELSE
	SELECT interest_rate FROM contract WHERE id = _settlement.contract_id INTO _interest_rate;

        SELECT interest(_settlement.date, (settlement_year(_settlement.id) || '-12-30' )::date, _interest_rate, _settlement.balance) INTO _interest;
     END IF;

  RETURN _interest;
  END;
  $BODY$
  LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
  COST 100;

update settlement set date = date -1  where (date_part('day'::text, date)::text = '31'::text);

COMMIT;