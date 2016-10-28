#encoding:utf-8

import datetime
import sys
import subprocess

default_encoding = 'utf-8'
if sys.getdefaultencoding() != default_encoding:
    reload(sys)
    sys.setdefaultencoding(default_encoding)

conn = mysql.db()

# usernubmer, mobile
def getmemberinfo(uid):
	flag = False
	sql = """
		select usernumber, mobile from zx_member where uid = %s
	""" % (uid)
	result = conn.query(sql)
	if result:
		return result

	return flag

def main(uid):
	cmd1 = "python /var/www/member/scripts/manage_bonus.py %s" % (uid)
	info1 = subprocess.Popen(cmd1, stdout = subprocess.PIPE, shell = True).communicate()[0].strip()
	if info1 == "ok":
		cmd2 = "python /var/www/member/scripts/achievement.py"
		info2 = subprocess.Popen(cmd2, stdout = subprocess.PIPE, shell = True).communicate()[0].strip()
		if info2 == "ok":
				result = getmemberinfo(uid)
				if result:
					usernumber = result[0]['usernumber'] 
					mobile = result[0]['mobile']
					cmd3 = "python /var/www/member/scripts/sms.py %s %s" % (usernumber, mobile)
					info3 = subprocess.Popen(cmd3, stdout = subprocess.PIPE, shell = True).communicate()[0].strip()
					print "ok"

if __name__ == '__main__':
	if len(sys.argv) >= 2:
		uid = sys.argv[1]
		main(uid)