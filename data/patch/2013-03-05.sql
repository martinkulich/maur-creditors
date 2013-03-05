BEGIN;
    
insert into security_perm(code, "name", is_public) values 
('report-regulation-monthly', 'Předpisy měsíčně', true);

CREATE OR REPLACE FUNCTION contract_interest(_contract_id integer, _month integer, _year integer)
  RETURNS numeric AS
$BODY$
        BEGIN
            return coalesce(SUM(s.interest), 0) FROM settlement s WHERE s.contract_id = _contract_id AND year(s.date) = _year AND month(s.date) = _month;
        END;
    $BODY$
  LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
  COST 100;


CREATE OR REPLACE FUNCTION year_month(_date date)
  RETURNS text AS
$BODY$
      BEGIN

      RETURN date_part('year'::text, _date)::text || '_' || date_part('month'::text, _date)::text;

      END;
      $BODY$
  LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
  COST 100;


CREATE OR REPLACE FUNCTION year_month(_year integer, _month integer)
  RETURNS text AS
$BODY$
	DECLARE _date date;
      BEGIN
	_date = first_day(_month, _year);
      RETURN year_month(_date);

      END;
      $BODY$
  LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
  COST 100;

COMMIT;