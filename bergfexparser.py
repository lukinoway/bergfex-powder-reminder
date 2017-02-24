########################################
# - Title:   Bergfex Powder Parser
# - Author:  Lukas Pichler
# - Date:    2016-02-05
########################################
#import re
import db_connector
import urllib2
from bs4 import BeautifulSoup


def get_int(x):
    return int(''.join(ele for ele in x if ele == '-' or ele.isdigit()))


def get_float(x):
    return float(''.join(ele for ele in x if ele.isdigit() or ele == '.'))

def get_data(resort, region, country):
    print "load weather data for " + country + "/" + region + "/" + resort

    link = "http://www.bergfex.at/" + resort + "/wetter/berg/"

    print "URL: " + link

    opener = urllib2.build_opener()
    opener.addheaders = [('User-agent', 'Mozilla/5.0')]

    try:
    	soup = BeautifulSoup(opener.open(link), 'html.parser')

    	for day in soup.findAll('div', class_=['day']):
    		print "------------------"

    		print day['title']
    		date_info = day['title'].split()[1]

    		sonne = day.find('div', class_='sonne')
    		print "Sonne: " + sonne.text.strip()

    		intSun = 0
    		if sonne.text.strip() != "-":
    			intSun = get_int(sonne.text.strip())


    		snow = day.find('div', class_='nschnee')

    		intSnow = 0

    		if snow:
    			print "Neuschnee: " + snow.text.strip()
    			if snow.text.strip() != "-":
    				snowstr = snow.text.strip()
    				# add check for range
    				if snowstr.find("-") == -1:
    					intSnow = get_int(snowstr)
    				else:
    					print "seems there is a range in snow forecast"
    					minsnow = snowstr.split("-")[0]
    					maxsnow = snowstr.split("-")[1]
    					print "min: " + minsnow
    					print "max: " + maxsnow
    					intSnow = (get_int(minsnow) + get_int(maxsnow)) / 2
    					print "avg: " + str(intSnow)


    		rain = day.find('div', class_='rrr')
    		print "Niederschlag: " + rain.text.strip()

    		floatRain = 0
    		if rain.text.strip() != "-":
    			floatRain = get_float(rain.text.strip().replace(",", "."))

    		possiblesnow = day.find('div', class_='rrp')
    		print "Wahrscheinlichkeit: " + possiblesnow.text.strip()

    		intRP = 0
    		if possiblesnow.text.strip() != "-":
    			intRP = get_int(possiblesnow.text.strip())


    		tmin = day.find('div', class_='tmin')
    		tmax = day.find('div', class_='tmax')

    		print "Temperatur: " + tmin.text + " -> " + tmax.text

    		intTmin = get_int(tmin.text)
    		intTmax = get_int(tmax.text)


    		db_connector.import_weather(date_info, resort, intTmin, intTmax, intSnow, floatRain, intRP, intSun, region, country)

    	print "finished import"

    except urllib2.HTTPError as e:
        print "error during import"
        print e


def snow_height(resort, region, country):
    link = "http://www.bergfex.at/" + resort + "/schneebericht/"

    print "URL: " + link
    opener = urllib2.build_opener()
    opener.addheaders = [('User-agent', 'Mozilla/5.0')]

    try:
    	soup = BeautifulSoup(opener.open(link), 'html.parser')
        block = soup.find('dl', class_='dl-horizontal dt-large')
        #print block

        if block:
            snowvalues = []
            for info_val in block.findAll('dd', class_='big'):
                #print info_val

                intSnow = 0

                snowstr = info_val.text.strip()
                if snowstr != "-":
                    snowval = snowstr.split("cm")[0]
                    if snowval:
                        intSnow = get_int(snowval)
                    #print intSnow

                # check for snow updates
                new_snow = info_val.find('div')
                intSnowChange = 0
                if new_snow:
                    print "snow_update: " +  resort + " " + new_snow.text.strip();
                    intSnowChange = get_int(new_snow.text.strip())
                    #print intSnowChange

                # fill array
                snowvalues.append(intSnow)
                snowvalues.append(intSnowChange)

            print snowvalues
            if len(snowvalues) > 2:
                db_connector.import_snow_full(resort, region, country, snowvalues)
            if len(snowvalues) == 2:
                db_connector.import_snow_berg(resort, region, country, snowvalues)

    except urllib2.HTTPError as e:
        print "error during import"
        print e


# function to load resorts for region
def load_resort_for_region(region, country):
    link = "http://www.bergfex.at/" + region

    print "get data for link " + link
    opener = urllib2.build_opener()
    opener.addheaders = [('User-agent', 'Mozilla/5.0')]
    try:
		soup = BeautifulSoup(opener.open(link), 'html.parser')
		resortlist = soup.find('div', class_='cols2 clearfix')
		for resort in resortlist.findAll('a'):

		    resortStr = resort['href'].replace("/", "")
		    if "http" not in resortStr:
			print "found resort " + resortStr + " -> now load weather"
			get_data(resortStr, region, country)

    except urllib2.HTTPError as e:
        print "error during import"
        print e
