#encoding:utf-8

import datetime
import sys
import subprocess



default_encoding = 'utf-8'
if sys.getdefaultencoding() != default_encoding:
    reload(sys)
    sys.setdefaultencoding(default_encoding)


def main():
	cmd1 = "python /var/www/member/scripts/manage_bonus.py"
	info1 = subprocess.Popen(cmd1, stdout = subprocess.PIPE, shell = True).communicate()[0].strip()
	if info1 == "ok":
		cmd2 = "python /var/www/member/scripts/achievement.py"
		info2 = subprocess.Popen(cmd2, stdout = subprocess.PIPE, shell = True).communicate()[0].strip()
		print info2

if __name__ == '__main__':
	main()