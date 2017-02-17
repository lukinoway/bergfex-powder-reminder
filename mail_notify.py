import db_connector
import mail_util

def build_html_table(results):
    #print len(results)
    if len(results) > 0:
    #    print "got entries"
        #seems we got some entries, now perform main task
        table = """\
        <table>
            <thead>
                <th>Resort</th>
                <th>Region</th>
                <th>Snow [cm]</th>
            </thead>
            <tbody>
        """
        for record in results:
            table = table + """\
            <tr>
                <td>""" + record[0] + """</td>
                <td>""" + record[1] + """</td>
                <td>""" + str(record[2]) + """</td>
            </tr>
            """

        table = table + "</tbody></table>"


        return table
    else:
        print "no entries in results"

db_connector.connect()
resultset = db_connector.load_top_10()
table = build_html_table(resultset)
if table:
    print "got a message table"
    mail_util.sendmail("lukas.pichler@gmail.com", table)
else:
    print "no table, so nothing todo"

db_connector.close()
