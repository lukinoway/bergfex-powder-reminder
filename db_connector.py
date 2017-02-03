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


def import_data(date, resort, tmin, tmax, snow, rain, rain_chance):
    if conn:
        stmt = """INSERT INTO weather_entries(date_info, resort, tmin, tmax, snow, rain, rain_chance, creation_dt) VALUES('"""+date+"""', '"""+resort+"""', '"""+tmin+"""', '"""+tmax+"""', '"""+snow+"""', '"""+rain+"""', '"""+rain_chance+"""', NOW())"""
        try:
            cur = conn.cursor()
            cur.execute(stmt)

            conn.commit()

        except psycopg2.Error as e:
            print "error occured while importing data"
            print e.pgcode
            print e.pgerror
    else:
        print "seems that there is no connection"


def close():
    if conn:
        print "close DB connection"
        conn.close()