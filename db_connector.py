########################################
# - Title:   Bergfex DB Connector
# - Author:  Lukas Pichler
# - Date:    2017-02-03
########################################

import psycopg2

conn = None

def connect():
    global conn
    if not conn:
        conn = psycopg2.connect("dbname='bergfex' user='bergfex' host='localhost' password='bergfex'")
        print "connected to DB"


def import_weather(date, resort, tmin, tmax, snow, rain, rain_chance, sun, region, country):
    if conn:
        stmt = "INSERT INTO weather_entries(date_info, resort, tmin, tmax, snow, rain, rain_chance, sun, region, country, creation_dt) VALUES(%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,NOW());"
        try:
            cur = conn.cursor()
            cur.execute(stmt, (date, resort, tmin, tmax, snow, rain, rain_chance, sun, region, country))

            conn.commit()
	    print "stored entry"

        except psycopg2.Error as e:
            print "error occured while importing weather"
            print e.pgcode
            print e.pgerror
	    print e
    else:
        print "seems that there is no connection"


def load_top_10():
    if conn:
        stmt = """\
            with resort_date as (
            	select resort, region, date_info, max(id) as id
            	  from weather_entries
            	 where date_info >= date(now())
            	   --and resort like 'kitzsteinhorn-kaprun'
            	 group by resort, region, date_info
             )
             select rd.resort, rd.region, sum(w1.snow) as total_snow
               from weather_entries w1
               inner join resort_date rd on rd.id = w1.id
              group by rd.resort, rd.region
             having sum(snow) > 30
              order by total_snow desc
              LIMIT 10;"""

        try:
            cur = conn.cursor()
            cur.execute(stmt)
            conn.commit()
            print "got data"
            return cur.fetchall()

        except psycopg2.Error as e:
            print "error occured while importing weather"
            print e.pgcode
            print e.pgerror
            print e
    else:
        print "seems that there is no connection"

def import_snow_full(resort, region, country,
                snowValues):
    if conn:
        stmt = "INSERT INTO snow_entries(resort, region, country, snow_berg, snow_berg_change, snow_tal, snow_tal_change, date_info , creation_dt) VALUES(%s,%s,%s,%s,%s,%s,%s,date(now()),NOW());"
        try:
            cur = conn.cursor()
            cur.execute(stmt, (resort, region, country, snowValues[0], snowValues[1], snowValues[2], snowValues[3]))

            conn.commit()
	    print "stored entry"

        except psycopg2.Error as e:
            print "error occured while importing snow entry"
            print e.pgcode
            print e.pgerror
	    print e
    else:
        print "seems that there is no connection"

def import_snow_berg(resort, region, country,
                snowValues):
    if conn:
        stmt = "INSERT INTO snow_entries(resort, region, country, snow_berg, snow_berg_change, snow_tal, snow_tal_change, date_info , creation_dt) VALUES(%s,%s,%s,%s,%s,0,0,date(now()),NOW());"
        try:
            cur = conn.cursor()
            cur.execute(stmt, (resort, region, country, snowValues[0], snowValues[1]))

            conn.commit()
	    print "stored entry"

        except psycopg2.Error as e:
            print "error occured while importing snow entry"
            print e.pgcode
            print e.pgerror
	    print e
    else:
        print "seems that there is no connection"

def close():
    if conn:
        print "close DB connection"
        conn.close()
