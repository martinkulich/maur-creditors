--
-- PostgreSQL database dump
--

-- Dumped from database version 9.0.4
-- Dumped by pg_dump version 9.0.4
-- Started on 2012-05-25 10:43:01 CEST

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;

--
-- TOC entry 357 (class 2612 OID 11574)
-- Name: plpgsql; Type: PROCEDURAL LANGUAGE; Schema: -; Owner: -
--

--CREATE OR REPLACE PROCEDURAL LANGUAGE plpgsql;


SET search_path = public, pg_catalog;

--
-- TOC entry 23 (class 1255 OID 2488223)
-- Dependencies: 357 5
-- Name: interest(date, date, numeric, numeric); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION interest(_date_from date, _date_to date, _interest_rate numeric, _amount numeric) RETURNS numeric
    LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
    AS $$
  DECLARE
    _interest numeric(15,2);
  BEGIN
  
	_interest = (_date_to - _date_from)/365 * _interest_rate * _amount;
  
  RETURN _interest;
  END;
  $$;


--
-- TOC entry 24 (class 1255 OID 2488239)
-- Dependencies: 5 357
-- Name: interest_to_end_of_year(integer); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION interest_to_end_of_year(_settlement_id integer) RETURNS numeric
    LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
    AS $$
  DECLARE
    _interest numeric(15,2);
  BEGIN
  
	select  ((settlement_year(s.id) || '-12-31' )::date - s.date)::numeric/365::numeric * c.interest_rate/100 * s.balance
	from settlement s JOIN contract c ON c.id = s.contract_id 
	where s.id = _settlement_id INTO _interest;
  
  RETURN _interest;
  END;
  $$;


--
-- TOC entry 19 (class 1255 OID 2488180)
-- Dependencies: 357 5
-- Name: last_contract_settelent_date_of_year(integer); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION last_contract_settelent_date_of_year(_settlement_id integer) RETURNS date
    LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
    AS $$
  DECLARE
    unpaid numeric(15,2);
  BEGIN
  
  SELECT
	(s.interest - s.paid - s.capitalized)
  FROM
    settlement s

  WHERE s.id = _settlement_id  
  	
  INTO
    unpaid;
  
  IF unpaid < 0 THEN
  	RETURN 0;
  ELSE
  	RETURN unpaid;
  END IF;
  
  END;
  $$;


--
-- TOC entry 18 (class 1255 OID 2488175)
-- Dependencies: 5 357
-- Name: last_contract_settelent_date_of_year(double precision, integer); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION last_contract_settelent_date_of_year(year double precision, _contract_id integer) RETURNS date
    LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
    AS $$
  DECLARE
    last_settlement_date date;
  BEGIN
  
  SELECT
    max(s.date)
  FROM
    settlement s

  WHERE s.contract_id = _contract_id  
  AND EXTRACT(YEAR FROM s.date) = year
	
  INTO
    last_settlement_date;
  
  
    RETURN last_settlement_date;
  END;
  $$;


--
-- TOC entry 21 (class 1255 OID 2488216)
-- Dependencies: 5 357
-- Name: settlement_balance_after_settlement(integer); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION settlement_balance_after_settlement(_settlement_id integer) RETURNS numeric
    LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
    AS $$
  DECLARE
    balance_after_settlement numeric(15,2);
  BEGIN
  
  SELECT
	s.balance + s.capitalized - s.balance_reduction
  FROM
    settlement s

  WHERE s.id = _settlement_id
  
  INTO
    balance_after_settlement;
  
 
  	RETURN balance_after_settlement;
 
  END;
  $$;


--
-- TOC entry 22 (class 1255 OID 2488197)
-- Dependencies: 357 5
-- Name: settlement_unpaid(integer); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION settlement_unpaid(_settlement_id integer) RETURNS numeric
    LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
    AS $$
  DECLARE
    unpaid numeric(15,2);
  BEGIN
  
  SELECT
	(s.interest - s.paid - s.capitalized)
  FROM
    settlement s

  WHERE s.id = _settlement_id  
  	
  INTO
    unpaid;
  
  IF unpaid < 0 THEN
  	RETURN 0;
  ELSE
  	RETURN unpaid;
  END IF;
  
  END;
  $$;


--
-- TOC entry 20 (class 1255 OID 2488211)
-- Dependencies: 5 357
-- Name: settlement_year(integer); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION settlement_year(_settlement_id integer) RETURNS numeric
    LANGUAGE plpgsql IMMUTABLE SECURITY DEFINER
    AS $$
  DECLARE
    year integer;
  BEGIN
  
  SELECT
	date_part('year'::text, s.date)::integer
  FROM
    settlement s

  WHERE s.id = _settlement_id  
  	
  INTO
    year;
  
   	RETURN year;
  
  END;
  $$;


SET default_tablespace = '';

SET default_with_oids = false;

--
-- TOC entry 1565 (class 1259 OID 2484131)
-- Dependencies: 5
-- Name: contract; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE contract (
    id integer NOT NULL,
    creditor_id integer NOT NULL,
    created_at date NOT NULL,
    activated_at date,
    period integer NOT NULL,
    interest_rate numeric(5,2) NOT NULL,
    amount numeric(15,2) NOT NULL,
    name character varying NOT NULL
);


--
-- TOC entry 1564 (class 1259 OID 2484129)
-- Dependencies: 5 1565
-- Name: contract_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE contract_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 1915 (class 0 OID 0)
-- Dependencies: 1564
-- Name: contract_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE contract_id_seq OWNED BY contract.id;


--
-- TOC entry 1563 (class 1259 OID 2484095)
-- Dependencies: 5
-- Name: creditor; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE creditor (
    id integer NOT NULL,
    subject_type_code character varying NOT NULL,
    identification_number character varying NOT NULL,
    firstname character varying NOT NULL,
    lastname character varying NOT NULL,
    email character varying,
    phone character varying,
    bank_account character varying,
    city character varying,
    street character varying,
    zip character varying
);


--
-- TOC entry 1562 (class 1259 OID 2484093)
-- Dependencies: 5 1563
-- Name: creditor_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE creditor_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 1916 (class 0 OID 0)
-- Dependencies: 1562
-- Name: creditor_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE creditor_id_seq OWNED BY creditor.id;


--
-- TOC entry 1567 (class 1259 OID 2488074)
-- Dependencies: 1865 1866 1867 1868 5
-- Name: settlement; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE settlement (
    id integer NOT NULL,
    contract_id integer NOT NULL,
    date date NOT NULL,
    interest numeric(15,2) NOT NULL,
    paid numeric(15,2) DEFAULT 0 NOT NULL,
    capitalized numeric(15,2) DEFAULT 0 NOT NULL,
    balance numeric(15,2) DEFAULT 0 NOT NULL,
    balance_reduction numeric(15,2) DEFAULT 0 NOT NULL
);


--
-- TOC entry 1571 (class 1259 OID 2488224)
-- Dependencies: 1660 5
-- Name: last_settlement_of_year; Type: VIEW; Schema: public; Owner: -
--

CREATE VIEW last_settlement_of_year AS
    SELECT s.id, s.date, date_part('year'::text, s.date) AS year, s.contract_id, s.balance, s.paid, s.interest, s.balance_reduction, s.capitalized FROM settlement s WHERE (s.date = last_contract_settelent_date_of_year(date_part('year'::text, s.date), s.contract_id));


--
-- TOC entry 1569 (class 1259 OID 2488147)
-- Dependencies: 1870 5
-- Name: payment; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE payment (
    id integer NOT NULL,
    contract_id integer NOT NULL,
    date date NOT NULL,
    amount numeric(15,2) DEFAULT 0 NOT NULL
);


--
-- TOC entry 1568 (class 1259 OID 2488145)
-- Dependencies: 5 1569
-- Name: payment_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE payment_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 1917 (class 0 OID 0)
-- Dependencies: 1568
-- Name: payment_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE payment_id_seq OWNED BY payment.id;


--
-- TOC entry 1570 (class 1259 OID 2488212)
-- Dependencies: 1659 5
-- Name: settlement_year; Type: VIEW; Schema: public; Owner: -
--

CREATE VIEW settlement_year AS
    SELECT s.id, s.date, date_part('year'::text, s.date) AS year FROM settlement s;


--
-- TOC entry 1572 (class 1259 OID 2488250)
-- Dependencies: 1661 5
-- Name: regulation; Type: VIEW; Schema: public; Owner: -
--

CREATE VIEW regulation AS
    SELECT cr.firstname AS creditor_firstname, cr.lastname AS creditor_lastname, c.name AS contract_name, c.id AS contract_id, sy.year AS settlement_year, (SELECT settlement_balance_after_settlement(lsopf.id) AS settlement_balance_after_settlement) AS start_balance, CASE date_part('year'::text, c.activated_at) WHEN sy.year THEN c.activated_at ELSE NULL::date END AS contract_activated_at, CASE date_part('year'::text, c.activated_at) WHEN sy.year THEN c.amount ELSE NULL::numeric END AS contract_balance, sum(s.interest) AS regulation, sum(s.paid) AS paid, (sum(s.paid) - interest_to_end_of_year(lsopf.id)) AS paid_for_current_year, sum(s.capitalized) AS capitalized, interest_to_end_of_year(lsocf.id) AS teoretically_to_pay_in_current_year, (SELECT settlement_balance_after_settlement(lsocf.id) AS settlement_balance_after_settlement) AS end_balance FROM (((((settlement s JOIN contract c ON ((c.id = s.contract_id))) JOIN creditor cr ON ((cr.id = c.creditor_id))) JOIN settlement_year sy ON ((s.id = sy.id))) LEFT JOIN last_settlement_of_year lsopf ON (((lsopf.contract_id = c.id) AND (lsopf.year = (sy.year - (1)::double precision))))) LEFT JOIN last_settlement_of_year lsocf ON (((lsocf.contract_id = c.id) AND (lsocf.year = sy.year)))) GROUP BY s.contract_id, c.id, c.name, c.activated_at, c.amount, cr.firstname, cr.lastname, lsopf.id, lsopf.date, lsopf.year, c.interest_rate, lsopf.balance, lsocf.id, sy.year;


--
-- TOC entry 1552 (class 1259 OID 2483995)
-- Dependencies: 1857 5
-- Name: security_group; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE security_group (
    id integer NOT NULL,
    name character varying NOT NULL,
    is_public boolean DEFAULT true NOT NULL
);


--
-- TOC entry 1551 (class 1259 OID 2483993)
-- Dependencies: 1552 5
-- Name: security_group_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE security_group_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 1918 (class 0 OID 0)
-- Dependencies: 1551
-- Name: security_group_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE security_group_id_seq OWNED BY security_group.id;


--
-- TOC entry 1554 (class 1259 OID 2484007)
-- Dependencies: 1859 5
-- Name: security_perm; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE security_perm (
    id integer NOT NULL,
    code character varying NOT NULL,
    name character varying NOT NULL,
    is_public boolean DEFAULT false NOT NULL
);


--
-- TOC entry 1553 (class 1259 OID 2484005)
-- Dependencies: 5 1554
-- Name: security_perm_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE security_perm_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 1919 (class 0 OID 0)
-- Dependencies: 1553
-- Name: security_perm_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE security_perm_id_seq OWNED BY security_perm.id;


--
-- TOC entry 1556 (class 1259 OID 2484021)
-- Dependencies: 5
-- Name: security_role; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE security_role (
    id integer NOT NULL,
    name character varying
);


--
-- TOC entry 1555 (class 1259 OID 2484019)
-- Dependencies: 1556 5
-- Name: security_role_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE security_role_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 1920 (class 0 OID 0)
-- Dependencies: 1555
-- Name: security_role_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE security_role_id_seq OWNED BY security_role.id;


--
-- TOC entry 1557 (class 1259 OID 2484030)
-- Dependencies: 5
-- Name: security_role_perm; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE security_role_perm (
    role_id integer NOT NULL,
    perm_id integer NOT NULL
);


--
-- TOC entry 1550 (class 1259 OID 2483980)
-- Dependencies: 1854 1855 5
-- Name: security_user; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE security_user (
    id integer NOT NULL,
    email character varying NOT NULL,
    password character varying NOT NULL,
    firstname character varying NOT NULL,
    surname character varying NOT NULL,
    phone character varying,
    active boolean DEFAULT true NOT NULL,
    is_super_admin boolean DEFAULT false NOT NULL
);


--
-- TOC entry 1558 (class 1259 OID 2484045)
-- Dependencies: 5
-- Name: security_user_group; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE security_user_group (
    user_id integer NOT NULL,
    group_id integer NOT NULL
);


--
-- TOC entry 1549 (class 1259 OID 2483978)
-- Dependencies: 1550 5
-- Name: security_user_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE security_user_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 1921 (class 0 OID 0)
-- Dependencies: 1549
-- Name: security_user_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE security_user_id_seq OWNED BY security_user.id;


--
-- TOC entry 1560 (class 1259 OID 2484062)
-- Dependencies: 5
-- Name: security_user_perm; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE security_user_perm (
    id integer NOT NULL,
    perm_id integer NOT NULL,
    user_id integer NOT NULL
);


--
-- TOC entry 1559 (class 1259 OID 2484060)
-- Dependencies: 1560 5
-- Name: security_user_perm_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE security_user_perm_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 1922 (class 0 OID 0)
-- Dependencies: 1559
-- Name: security_user_perm_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE security_user_perm_id_seq OWNED BY security_user_perm.id;


--
-- TOC entry 1561 (class 1259 OID 2484078)
-- Dependencies: 5
-- Name: security_user_role; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE security_user_role (
    user_id integer NOT NULL,
    role_id integer NOT NULL
);


--
-- TOC entry 1566 (class 1259 OID 2488072)
-- Dependencies: 1567 5
-- Name: settlement_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE settlement_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 1923 (class 0 OID 0)
-- Dependencies: 1566
-- Name: settlement_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE settlement_id_seq OWNED BY settlement.id;


--
-- TOC entry 1863 (class 2604 OID 2484134)
-- Dependencies: 1564 1565 1565
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE contract ALTER COLUMN id SET DEFAULT nextval('contract_id_seq'::regclass);


--
-- TOC entry 1862 (class 2604 OID 2484098)
-- Dependencies: 1563 1562 1563
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE creditor ALTER COLUMN id SET DEFAULT nextval('creditor_id_seq'::regclass);


--
-- TOC entry 1869 (class 2604 OID 2488150)
-- Dependencies: 1569 1568 1569
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE payment ALTER COLUMN id SET DEFAULT nextval('payment_id_seq'::regclass);


--
-- TOC entry 1856 (class 2604 OID 2483998)
-- Dependencies: 1552 1551 1552
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE security_group ALTER COLUMN id SET DEFAULT nextval('security_group_id_seq'::regclass);


--
-- TOC entry 1858 (class 2604 OID 2484010)
-- Dependencies: 1553 1554 1554
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE security_perm ALTER COLUMN id SET DEFAULT nextval('security_perm_id_seq'::regclass);


--
-- TOC entry 1860 (class 2604 OID 2484024)
-- Dependencies: 1555 1556 1556
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE security_role ALTER COLUMN id SET DEFAULT nextval('security_role_id_seq'::regclass);


--
-- TOC entry 1853 (class 2604 OID 2483983)
-- Dependencies: 1549 1550 1550
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE security_user ALTER COLUMN id SET DEFAULT nextval('security_user_id_seq'::regclass);


--
-- TOC entry 1861 (class 2604 OID 2484065)
-- Dependencies: 1559 1560 1560
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE security_user_perm ALTER COLUMN id SET DEFAULT nextval('security_user_perm_id_seq'::regclass);


--
-- TOC entry 1864 (class 2604 OID 2488077)
-- Dependencies: 1566 1567 1567
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE settlement ALTER COLUMN id SET DEFAULT nextval('settlement_id_seq'::regclass);


--
-- TOC entry 1894 (class 2606 OID 2484136)
-- Dependencies: 1565 1565
-- Name: contract_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY contract
    ADD CONSTRAINT contract_pkey PRIMARY KEY (id);


--
-- TOC entry 1892 (class 2606 OID 2484128)
-- Dependencies: 1563 1563
-- Name: creditor_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY creditor
    ADD CONSTRAINT creditor_pkey PRIMARY KEY (id);


--
-- TOC entry 1898 (class 2606 OID 2488153)
-- Dependencies: 1569 1569
-- Name: payment_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY payment
    ADD CONSTRAINT payment_pkey PRIMARY KEY (id);


--
-- TOC entry 1876 (class 2606 OID 2484004)
-- Dependencies: 1552 1552
-- Name: security_group_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY security_group
    ADD CONSTRAINT security_group_pkey PRIMARY KEY (id);


--
-- TOC entry 1878 (class 2606 OID 2484018)
-- Dependencies: 1554 1554
-- Name: security_perm_code_uidx; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY security_perm
    ADD CONSTRAINT security_perm_code_uidx UNIQUE (code);


--
-- TOC entry 1880 (class 2606 OID 2484016)
-- Dependencies: 1554 1554
-- Name: security_perm_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY security_perm
    ADD CONSTRAINT security_perm_pkey PRIMARY KEY (id);


--
-- TOC entry 1884 (class 2606 OID 2484034)
-- Dependencies: 1557 1557 1557
-- Name: security_role_perm_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY security_role_perm
    ADD CONSTRAINT security_role_perm_pkey PRIMARY KEY (role_id, perm_id);


--
-- TOC entry 1882 (class 2606 OID 2484029)
-- Dependencies: 1556 1556
-- Name: security_role_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY security_role
    ADD CONSTRAINT security_role_pkey PRIMARY KEY (id);


--
-- TOC entry 1872 (class 2606 OID 2483992)
-- Dependencies: 1550 1550
-- Name: security_user_email_uidx; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY security_user
    ADD CONSTRAINT security_user_email_uidx UNIQUE (email);


--
-- TOC entry 1886 (class 2606 OID 2484049)
-- Dependencies: 1558 1558 1558
-- Name: security_user_group_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY security_user_group
    ADD CONSTRAINT security_user_group_pkey PRIMARY KEY (user_id, group_id);


--
-- TOC entry 1888 (class 2606 OID 2484067)
-- Dependencies: 1560 1560
-- Name: security_user_perm_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY security_user_perm
    ADD CONSTRAINT security_user_perm_pkey PRIMARY KEY (id);


--
-- TOC entry 1874 (class 2606 OID 2483990)
-- Dependencies: 1550 1550
-- Name: security_user_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY security_user
    ADD CONSTRAINT security_user_pkey PRIMARY KEY (id);


--
-- TOC entry 1890 (class 2606 OID 2484082)
-- Dependencies: 1561 1561 1561
-- Name: security_user_role_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY security_user_role
    ADD CONSTRAINT security_user_role_pkey PRIMARY KEY (user_id, role_id);


--
-- TOC entry 1896 (class 2606 OID 2488081)
-- Dependencies: 1567 1567
-- Name: settlement_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY settlement
    ADD CONSTRAINT settlement_pkey PRIMARY KEY (id);


--
-- TOC entry 1899 (class 2606 OID 2484035)
-- Dependencies: 1881 1557 1556
-- Name: Ref_00; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY security_role_perm
    ADD CONSTRAINT "Ref_00" FOREIGN KEY (role_id) REFERENCES security_role(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 1900 (class 2606 OID 2484040)
-- Dependencies: 1557 1879 1554
-- Name: Ref_01; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY security_role_perm
    ADD CONSTRAINT "Ref_01" FOREIGN KEY (perm_id) REFERENCES security_perm(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 1905 (class 2606 OID 2484083)
-- Dependencies: 1561 1556 1881
-- Name: Ref_02; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY security_user_role
    ADD CONSTRAINT "Ref_02" FOREIGN KEY (role_id) REFERENCES security_role(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 1906 (class 2606 OID 2484088)
-- Dependencies: 1550 1873 1561
-- Name: Ref_03; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY security_user_role
    ADD CONSTRAINT "Ref_03" FOREIGN KEY (user_id) REFERENCES security_user(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 1903 (class 2606 OID 2484068)
-- Dependencies: 1560 1879 1554
-- Name: Ref_35; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY security_user_perm
    ADD CONSTRAINT "Ref_35" FOREIGN KEY (perm_id) REFERENCES security_perm(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 1904 (class 2606 OID 2484073)
-- Dependencies: 1550 1560 1873
-- Name: Ref_36; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY security_user_perm
    ADD CONSTRAINT "Ref_36" FOREIGN KEY (user_id) REFERENCES security_user(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 1907 (class 2606 OID 2484137)
-- Dependencies: 1565 1891 1563
-- Name: contract_creditor_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY contract
    ADD CONSTRAINT contract_creditor_id_fkey FOREIGN KEY (creditor_id) REFERENCES creditor(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 1909 (class 2606 OID 2488154)
-- Dependencies: 1893 1565 1569
-- Name: payment_contract_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY payment
    ADD CONSTRAINT payment_contract_id_fkey FOREIGN KEY (contract_id) REFERENCES contract(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 1901 (class 2606 OID 2484050)
-- Dependencies: 1552 1558 1875
-- Name: security_user_group_group_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY security_user_group
    ADD CONSTRAINT security_user_group_group_id_fkey FOREIGN KEY (group_id) REFERENCES security_group(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 1902 (class 2606 OID 2484055)
-- Dependencies: 1558 1550 1873
-- Name: security_user_group_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY security_user_group
    ADD CONSTRAINT security_user_group_user_id_fkey FOREIGN KEY (user_id) REFERENCES security_user(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 1908 (class 2606 OID 2488082)
-- Dependencies: 1565 1567 1893
-- Name: settlement_contract_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY settlement
    ADD CONSTRAINT settlement_contract_fkey FOREIGN KEY (contract_id) REFERENCES contract(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 1914 (class 0 OID 0)
-- Dependencies: 5
-- Name: public; Type: ACL; Schema: -; Owner: -
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


-- Completed on 2012-05-25 10:43:02 CEST

--
-- PostgreSQL database dump complete
--

