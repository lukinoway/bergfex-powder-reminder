########################################
# - Title:   Bergfex Resort Parser
# - Author:  Lukas Pichler
# - Date:    2016-02-05
########################################
#import re
import db_connector
import bergfexparser
import urllib2
from bs4 import BeautifulSoup
from datetime import datetime
import sys
import time


# function to load resorts for region
def load_resort_for_region(region, country):
	link = "http://www.bergfex.at/" + region

	print "get data for link " + link
	opener = urllib2.build_opener()
	opener.addheaders = [('User-agent', 'Mozilla/5.0')]
	soup = BeautifulSoup(opener.open(link), 'html.parser')
	resortlist = soup.find('div', class_='cols2 clearfix')
	for resort in resortlist.findAll('a'):

	    resortStr = resort['href'].replace("/", "")
	    if "http" not in resortStr:
		print "found resort " + resortStr + " -> now load snow"
		bergfexparser.snow_height(resortStr, region, country)
		print "sleep 10 sek"
		time.sleep(10)


# main part
start = datetime.now()
print "start bergfex parser"

# set string formating to UTF
reload(sys)
sys.setdefaultencoding('utf-8')

country = "italien"
region_array = ["friaul", "suedtirol"]
db_connector.connect()

for region in region_array:
	load_resort_for_region(region, country)

#bergfexparser.snow_height("tauplitz", "steiermark", country)

db_connector.close()
print "finished execution, runtime ", (datetime.now()-start)
