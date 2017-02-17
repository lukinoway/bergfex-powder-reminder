import smtplib
from email.mime.multipart import MIMEMultipart
from email.mime.text import MIMEText

# send mail from powder guru email address
def sendmail(you, message):

	# define some basic stuff
	me = "powder-guru@web.de"
	password = "MultiPass1"
	smtpserver='smtp.web.de:587'


	msg = MIMEMultipart('alternative')
	msg['Subject'] = "Powder Radar"
	msg['From'] = me
	msg['To'] = you

	# Create the body of the message (a plain-text and an HTML version).
	#text = "Hi!\nHow are you?\nHere is the link you wanted:\nhttps://www.python.org"
	html = """\
	<html>
	  <head>
	    <style>
	      tr:nth-child(odd) {background-color: #f2f2f2}
	      th {
	        background-color: #546e7a;
	        color: white;
	      }
	      th, td {
	        padding: 15px;
	        text-align: left;
	      }
	      table {
	        width: 100%;
	        border-collapse: collapse;
	      }
	      body {
	        margin: 0px;
	      }
	    </style>
	  </head>
	  <body>
	""" + message + """\
	    </p>
	  </body>
	</html>
	"""

	# Record the MIME types of both parts - text/plain and text/html.
	#part1 = MIMEText(text, 'plain')
	part2 = MIMEText(html, 'html')

	# Attach parts into message container.
	# According to RFC 2046, the last part of a multipart message, in this case
	# the HTML message, is best and preferred.
	#msg.attach(part1)
	msg.attach(part2)

	server = smtplib.SMTP(smtpserver)
	server.starttls()
	server.login(me,password)
	problems = server.sendmail(me , you, msg.as_string())
	print "send message"
	#print msg.as_string()
	server.quit()
