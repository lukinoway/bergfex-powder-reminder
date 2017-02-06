import urllib
from bs4 import BeautifulSoup

resort = "kitzsteinhorn-kaprun"
link = "http://www.bergfex.at/" + resort + "/schneebericht/"

print "URL: " + link
soup = BeautifulSoup(urllib.urlopen(link), 'html.parser')
#print soup
block = soup.find('dl', class_='dl-horizontal dt-large')
print block

print "------ info"
for info in block.findAll('dt', class_='big'):
	print info.text.strip()


print "------- info val"
for info_val in block.findAll('dd', class_='big'):
	new_snow = info_val.find('div')
	if new_snow:
		print new_snow.text.strip();

	print info_val.text.strip()