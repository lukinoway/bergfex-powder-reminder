########################################
# - Title:   Bergfex Resort Parser
# - Author:  Lukas Pichler
# - Date:    2016-02-05
########################################
#import re
import db_connector
import bergfexparser
import urllib
from bs4 import BeautifulSoup
from datetime import datetime
import sys

# function to load resorts for region
def load_resort_for_region(region, country):
	link = "http://www.bergfex.at/" + region

	print "get data for link " + link
	soup = BeautifulSoup(urllib.urlopen(link), 'html.parser')
	resortlist = soup.find('div', class_='cols2 clearfix')
	for resort in resortlist.findAll('a'):

	    resortStr = resort['href'].replace("/", "")
	    if "http" not in resortStr:
		print "found resort " + resortStr + " -> now load snow"
		bergfexparser.snow_height(resortStr, region, country)


# main part
start = datetime.now()
print "start bergfex parser"

# set string formating to UTF
reload(sys)
sys.setdefaultencoding('utf-8')

country = "oesterreich"
region_array = ["tirol","steiermark", "salzburg", "kaernten", "oberoesterreich", "niederoesterreich", "vorarlberg"]
db_connector.connect()

for region in region_array:
	load_resort_for_region(region, country)

#bergfexparser.snow_height("tauplitz", "steiermark", country)

db_connector.close()
print "finished execution, runtime ", (datetime.now()-start)
