#encoding:utf-8
import mysql
from time import time

conn = mysql.db()

def rate():
	rate_sql = """
		select category, value from zx_bonus_rule where category in ('rongzidun', 'jiangjinbi', 'lovemoney', 'platmoney', 'taxmoney')
	"""
	rates = conn.query(rate_sql)
	conn.close()
	if rates:
		rates = rates
	else:
		rates = (
			{'category': 'rongzidun', 'value': 25}, 
			{'category': 'jiangjinbi', 'value': 55}, 
			{'category': 'lovemoney', 'value': 1}, 
			{'category': 'platmoney', 'value': 2}, 
			{'category': 'taxmoney', 'value': 17}
		)
	return rates

# 分红
def fenhong():
	# 比率配比
	rates = rate()
 	fenghong_scale_sql = "select value from zx_bonus_rule where category = 'UserCash'"
 	fenhongs = conn.query(fenghong_scale_sql)
 	if fenhongs:
 		fenghong_scale = fenhongs[0]['value'] / 100
 	else:
 		fenghong_scale = 1.1 / 100

	# 会员
	member_sql = """
					select m.uid, m.usernumber, m.realname, m.userrank, m.jiangjinbi, m.rongzidun, r.value from zx_member as m left join zx_bonus_rule as r
					on m.userrank = r.key
	 				where m.userrank != 1 and m.status = 1 and m.proxy_state = 1 and r.category = 'userrank'
	"""
	members = conn.query(member_sql)

	if members:
		for member in members:
			uid = member['uid']
			usernumber = member['usernumber']
			realname = member['realname']
			userrank = member['userrank']
			value = member['value']

			# total 奖金
			fenhong = fenghong_scale * value
			jiangjinbi, rongzidun, lovemoney, platmoney, taxmoney = 0, 0, 0, 0, 0

			for r in rates:
				if r['category'] == 'jiangjinbi':
					jiangjinbi_rate = r['value'] / 100
					jiangjinbi = fenhong * jiangjinbi_rate
				elif r['category'] == 'rongzidun':
					rongzidun_rate = r['value'] / 100
					rongzidun = fenhong * rongzidun_rate
				elif r['category'] == 'lovemoney':
					lovemoney_rate = r['value'] / 100
					lovemoney = fenhong * lovemoney_rate
				elif r['category'] == 'platmoney':
					platmoney_rate = r['value'] / 100
					platmoney = fenhong * platmoney_rate
				elif r['category'] == 'taxmoney':
					taxmoney_rate = r['value'] / 100
					taxmoney = fenhong * taxmoney_rate

			# real_total 实发奖金
			real_total = fenhong - lovemoney - platmoney - taxmoney
			now = int(time())
			# 明细
			zx_bonus_detail_sql = """
				insert into zx_bonus_detail (touserid, tousernumber, torealname, moneytype, jiangjinbi, rongzidun, lovemoney, platmoney, taxmoney, total, real_total, createdate) 
				values (%s, %s, '%s', %s, %s, %s, %s, %s, %s, %s, %s, %s)
			""" % (uid, usernumber, realname, userrank, jiangjinbi, rongzidun, lovemoney, platmoney, taxmoney, fenhong, real_total, now)
			#  插入明细表
			zx_bonus_detail = conn.dml(zx_bonus_detail_sql, 'insert')

			# 财务流水
			jiangjinbi_change_sql = """
				insert into zx_money_change (moneytype, status, targetuserid, targetusernumber, targetrealname, userid, usernumber, realname, changetype, recordtype, money, hasmoney, createtime)
				values ()
			""" % ()
			rongzidun_change_sql = """
				insert into zx_money_change (moneytype, status, targetuserid, targetusernumber, targetrealname, userid, usernumber, realname, changetype, recordtype, money, hasmoney, createtime)
				values ()
			""" % ()
			lovemoney_change_sql = """
				insert into zx_money_change (moneytype, status, targetuserid, targetusernumber, targetrealname, userid, usernumber, realname, changetype, recordtype, money, hasmoney, createtime)
				values ()
			""" % ()
			platmoney_change_sql = """
				insert into zx_money_change (moneytype, status, targetuserid, targetusernumber, targetrealname, userid, usernumber, realname, changetype, recordtype, money, hasmoney, createtime)
				values ()
			""" % ()
			taxmoney_change_sql = """
				insert into zx_money_change (moneytype, status, targetuserid, targetusernumber, targetrealname, userid, usernumber, realname, changetype, recordtype, money, hasmoney, createtime)
				values ()
			""" % ()
	else:
		print "null"

	conn.close()

if __name__ == '__main__':
	fenhong()