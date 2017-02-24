########################################
# - Title:   Bergfex Resort Parser
# - Author:  Lukas Pichler
# - Date:    2016-02-05
########################################
import db_connector
import bergfexparser
from datetime import datetime
import sys

# main part
start = datetime.now()
print "start bergfex italia parser"

# set string formating to UTF
reload(sys)
sys.setdefaultencoding('utf-8')

country = "italien"
region_array = ["friaul", "suedtirol"]
db_connector.connect()

for region in region_array:
	bergfexparser.load_resort_for_region(region, country)

db_connector.close()
print "finished execution, runtime ", (datetime.now()-start)
