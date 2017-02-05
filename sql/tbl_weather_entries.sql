-- Table: public.weather_entries

-- DROP TABLE public.weather_entries;

CREATE TABLE public.weather_entries
(
  id integer NOT NULL DEFAULT nextval('weather_entries_id_seq'::regclass),
  resort text,
  date_info date,
  tmin integer,
  tmax integer,
  snow integer,
  rain double precision,
  rain_chance integer,
  creation_dt timestamp without time zone,
  sun integer,
  region text,
  country text
)
WITH (
  OIDS=FALSE
);
ALTER TABLE public.weather_entries
  OWNER TO bergfex;
