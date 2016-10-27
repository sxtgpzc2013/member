#encoding:utf-8

import mysql
import datetime
import sys

default_encoding = 'utf-8'
if sys.getdefaultencoding() != default_encoding:
    reload(sys)
    sys.setdefaultencoding(default_encoding)

conn = mysql.db()
now = datetime.datetime.now()
yes_time_second = (now + datetime.timedelta(days=-1)).strftime('%s')
yes_time = (now + datetime.timedelta(days=-1)).strftime('%Y-%m-%d')

def money_change(uid, usernumber, realname):
	total, realtotal = 0, 0
	fenhong_total, fenhong_real_total, manager_total, manager_real_total, leader_total, leader_real_total, expand_total, expand_real_total, market_total, market_real_total, consume_total, consume_real_total, service_total, service_real_total, twice_consume_total, twice_consume_real_total, service_agent_total, service_agent_real_total, manager_agent_total, manager_agent_real_total = 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0

	sql = """
		select moneytype, sum(total) as total, sum(real_total) as real_total from zx_bonus_detail 
		where touserid = %s and from_unixtime(createdate, '%%Y-%%m-%%d') = '%s' group by moneytype
	""" % (uid, yes_time)

	results = conn.query(sql)
	if results:
		for result in results:
			moneytype = int(result['moneytype'])
			if moneytype == 1:
				fenhong_total = result['total']
				fenhong_real_total = result['real_total']
			elif moneytype == 2:
				manager_total = result['total']
				manager_real_total = result['real_total']
			elif moneytype == 3:
				leader_total = result['total']
				leader_real_total = result['real_total']
			elif moneytype == 4:
				expand_total = result['total']
				expand_real_total = result['real_total']
			elif moneytype == 5:
				market_total = result['total']
				market_real_total = result['real_total']
			elif moneytype == 6:
				consume_total = result['total']
				consume_real_total = result['real_total']
			elif moneytype == 7:
				service_total = result['total']
				service_real_total = result['real_total']
			elif moneytype == 8:
				twice_consume_total = result['total']
				twice_consume_real_total = result['real_total']
			elif moneytype == 10:
				service_agent_total = result['total']
				service_agent_real_total = result['real_total']
			elif moneytype == 11:
				manager_agent_total = result['total']
				manager_agent_real_total = result['real_total']

		total = fenhong_total + manager_total + leader_total + expand_total + market_total + consume_total + service_total + twice_consume_total + service_agent_total + manager_agent_total
		realtotal =  fenhong_real_total + manager_real_total + leader_real_total + expand_real_total + market_real_total + consume_real_total + service_real_total + twice_consume_real_total + service_agent_real_total + manager_agent_real_total

	zx_bonus_count_sql = """
		insert into zx_bonus_count (touserid, tousernumber, torealname, bonus1, bonus2, bonus3, bonus4, bonus5, bonus6, bonus7, bonus8, bonus10, bonus11, total, real_total, count_date) values (%s, %s, '%s', %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
	""" % (uid, usernumber, realname, fenhong_total, manager_total, leader_total, expand_total, market_total, consume_total, service_total, twice_consume_total, service_agent_total, total, manager_agent_total, realtotal, yes_time_second)
	conn.dml(zx_bonus_count_sql, 'insert')

def main():
	# 查昨天所有的明细
	member_sql = """
		select uid, usernumber, realname from zx_member where status = 1 and uid != 1
	"""
	results = conn.query(member_sql)

	if results:
		for result in results:
			uid = result['uid']
			usernumber = result['usernumber']
			realname = result['realname']
			money_change(uid, usernumber, realname)

	conn.close()
	print "ok"

if __name__ == '__main__':
	main()