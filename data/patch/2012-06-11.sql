BEGIN;

    UPDATE settlement SET cash = false where cash is null;
    ALTER TABLE settlement ALTER COLUMN cash SET DEFAULT false;
    ALTER TABLE settlement ALTER COLUMN cash SET NOT NULL;


    ALTER TABLE settlement ADD COLUMN settlement_type CHARACTER VARYING DEFAULT 'in_period' NOT NULL;


CREATE OR REPLACE FUNCTION year(_date date)
  RETURNS numeric AS
  $BODY$
      DECLARE
        year integer;
      BEGIN

      SELECT date_part('year'::text, _date)::integer INTO year;

        RETURN year;

      END;
      $BODY$
  LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
  COST 100;


COMMIT;