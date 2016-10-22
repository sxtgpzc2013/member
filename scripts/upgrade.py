#encoding:utf-8

import mysql
import datetime
import sys

default_encoding = 'utf-8'
if sys.getdefaultencoding() != default_encoding:
    reload(sys)
    sys.setdefaultencoding(default_encoding)

conn = mysql.db()

# 最大分红
def maxcash(userrank):
	value = 0
	sql = """
		select value from zx_bonus_rule where category = 'maxcash' and `key` = %s
	""" % (userrank)
	result = conn.query(sql)
	if result:
		value = result[0]['value']

	return value

def main():
	pass

if __name__ == '__main__':
	main()