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

# 升级补差 管理补贴和互助补贴
def main(uid):
	# 会员
	sql = """
		select m.uid, m.usernumber, m.realname, m.userrank, m.max_bonus, m.upgrade_level, m.upgrade_status, r.value from zx_member as m left join zx_bonus_rule as r
		on m.userrank = r.key where m.status = 1 and and m.uid = %s and r.category = 'userrank'
	""" % (uid)
	result = conn.query(sql)
	if result:
		pass

if __name__ == '__main__':
	if len(sys.argv) >= 2:
		uid = sys.argv[1]
		main(uid)