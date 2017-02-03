########################################
# - Title:   Bergfex Powder Parser
# - Author:  Lukas Pichler
# - Date:    2016-10-12
########################################
#import re
import urllib
from bs4 import BeautifulSoup

print "start bergfex parser"

resort = "obertauern"
link = "http://www.bergfex.at/" + resort + "/wetter/berg/"

print "get data for link " + link

#s = requests.Session()
#r = s.get(link)


soup = BeautifulSoup(urllib.urlopen(link), 'html.parser')

print "parse input"
for day in soup.findAll('div', class_=['day clickable selectable fields']):

    print "------------------"

    print day['title']

    sonne = day.find('div', class_='sonne')
    print "Sonne: " + sonne.text.strip()

    snow = day.find('div', class_='group nschnee ')
    print "Neuschnee: " + snow.text.strip()

    rain = day.find('div', class_='rrr')
    print "Niederschlag: " + rain.text.strip()

    possiblesnow = day.find('div', class_='rrp')
    print "Wahrscheinlichkeit: " + possiblesnow.text.strip()

    tmin = day.find('div', class_='tmin')
    tmax = day.find('div', class_='tmax')

    print "Temperatur: " + tmin.text + " -> " + tmax.text
