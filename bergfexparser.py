########################################
# - Title:   Bergfex Powder Parser
# - Author:  Lukas Pichler
# - Date:    2016-02-05
########################################
#import re
import db_connector
import urllib
from bs4 import BeautifulSoup


def get_int(x):
    return int(''.join(ele for ele in x if ele == '-' or ele.isdigit()))


def get_float(x):
    return float(''.join(ele for ele in x if ele.isdigit() or ele == '.'))

def get_data(resort, region, country):
	print "load weather data for " + country + "/" + region + "/" + resort

	link = "http://www.bergfex.at/" + resort + "/wetter/berg/"

	print "URL: " + link

	soup = BeautifulSoup(urllib.urlopen(link), 'html.parser')

	for day in soup.findAll('div', class_=['day clickable selectable fields']):
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
				intSnow = get_int(snow.text.strip())


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


def snow_height(resort):
	link = "http://www.bergfex.at/" + resort + "/schneebericht/"

	#print "URL: " + link
	soup = BeautifulSoup(urllib.urlopen(link), 'html.parser')
	#print soup
	block = soup.find('dl', class_='dl-horizontal dt-large')
	#print block

	#print "------ info"
	#for info in block.findAll('dt', class_='big'):
		#print info.text.strip()


	#print "------- info val"
	if block:
		for info_val in block.findAll('dd', class_='big'):
			new_snow = info_val.find('div')
			if new_snow:
				print "snow_update: " +  resort + " " + new_snow.text.strip();
