BEGIN;

CREATE OR REPLACE FUNCTION year(_date date)
  RETURNS integer AS
$BODY$
      BEGIN

      RETURN date_part('year'::text, _date)::integer ;

      END;
      $BODY$
  LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER;

  CREATE OR REPLACE FUNCTION month(_date date)
  RETURNS integer AS
$BODY$
      BEGIN

      return date_part('month'::text, _date)::integer;

      END;
      $BODY$
  LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER;

CREATE OR REPLACE FUNCTION creditor_balance(_creditor_id integer, _date date, _currency_code text, _with_capitalization_and_balance_reduction boolean)
  RETURNS numeric AS
$BODY$

        BEGIN

            return (select sum(coalesce(contract_balance(c.id, _date, _with_capitalization_and_balance_reduction), 0)) from contract c where c.creditor_id = _creditor_id and c.currency_code = _currency_code);

        END;
    $BODY$
  LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
  COST 100;


CREATE OR REPLACE FUNCTION creditor_balance(_creditor_id integer, _date date, _currency_code text)
  RETURNS numeric AS
$BODY$

        BEGIN

            return creditor_balance(_creditor_id, _date, _currency_code, true);

        END;
    $BODY$
  LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
  COST 100;



CREATE OR REPLACE FUNCTION creditor_paid(_creditor_id integer, _month integer, _year integer, _currency_code text)
  RETURNS numeric AS
$BODY$

        BEGIN

            return (select sum(coalesce(contract_paid(c.id, _month, _year),0)) from contract c where c.creditor_id = _creditor_id and c.currency_code = _currency_code);

        END;
    $BODY$
  LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
  COST 100;





CREATE OR REPLACE FUNCTION creditor_capitalized(_creditor_id integer, _month integer, _year integer, _currency_code text)
  RETURNS numeric AS
$BODY$

        BEGIN

            return (select sum(coalesce(contract_capitalized(c.id, _month, _year),0)) from contract c where c.creditor_id = _creditor_id and c.currency_code = _currency_code);

        END;
    $BODY$
  LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
  COST 100;




CREATE OR REPLACE FUNCTION creditor_interest(_creditor_id integer, _month integer, _year integer, _currency_code text)
  RETURNS numeric AS
$BODY$

        BEGIN

            return (select sum(coalesce(contract_interest(c.id, _month, _year),0)) from contract c where c.creditor_id = _creditor_id and c.currency_code = _currency_code);

        END;
    $BODY$
  LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
  COST 100;


CREATE OR REPLACE FUNCTION creditor_unpaid(_creditor_id integer, _till_date date, _currency_code text)
  RETURNS numeric AS
$BODY$

        BEGIN

            return (select sum(coalesce(contract_unpaid(c.id, _till_date),0)) from contract c where c.creditor_id = _creditor_id and c.currency_code = _currency_code);

        END;
    $BODY$
  LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
  COST 100;

  CREATE OR REPLACE FUNCTION creditor_interest(_creditor_id integer, _year integer, _currency_code text)
  RETURNS numeric AS
$BODY$
        BEGIN
            return (select sum(coalesce(contract_interest(c.id, _year),0)) from contract c where c.creditor_id = _creditor_id and c.currency_code = _currency_code);
        END;
    $BODY$
  LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
  COST 100;

  insert into security_perm(code, "name", is_public) values ('report-creditors', 'Předpis věřitelů', true);


CREATE TABLE months
(
  "number" integer
);

insert into months(number) values
(1),
(2),
(3),
(4),
(5),
(6),
(7),
(8),
(9),
(10),
(11),
(12);

COMMIT;