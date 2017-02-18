CREATE TABLE public.snow_entries
(
  id serial,
  resort text,
  region text,
  country text,
  date_info date,
  snow_berg integer,
  snow_berg_change integer,
  snow_tal integer,
  snow_tal_change integer,
  creation_dt timestamp without time zone
)
