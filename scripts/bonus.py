#encoding:utf-8

import mysql
import datetime
import sys

default_encoding = 'utf-8'
if sys.getdefaultencoding() != default_encoding:
    reload(sys)
    sys.setdefaultencoding(default_encoding)

conn = mysql.db()

def rate():
	rate_sql = """
		select category, value from zx_bonus_rule where category in ('rongzidun', 'jiangjinbi', 'lovemoney', 'platmoney', 'taxmoney')
	"""
	rates = conn.query(rate_sql)

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

# 最大分红比例
def maxcash(userrank):
	value = 0
	sql = """
		select value from zx_bonus_rule where category = 'maxcash' and `key` = %s
	""" % (userrank)
	result = conn.query(sql)
	if result:
		value = result[0]['value']

	return value
# 分红
def fenhong():
	now = datetime.datetime.now()
	now_second = datetime.datetime.now().strftime('%s')
	yes_second = (now + datetime.timedelta(days=-1)).strftime('%s')
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
					select m.uid, m.usernumber, m.realname, m.userrank, m.jiangjinbi, m.rongzidun, m.max_bonus, r.value from zx_member as m left join zx_bonus_rule as r
					on m.userrank = r.key
	 				where m.userrank != 1 and m.status = 1 and m.proxy_state = 1 and r.category = 'userrank' and m.usernumber != 1
	"""
	members = conn.query(member_sql)

	if members:
		for member in members:
			uid = member['uid']
			usernumber = member['usernumber']
			realname = member['realname']
			userrank = member['userrank']
			value = member['value']
			max_bonus = float(member['max_bonus'])

			# 最大分红的奖金
			max_cash = int(maxcash(userrank) * value)
			fenhong = fenghong_scale * value

			if max_bonus < max_cash:
				if fenhong + max_bonus > max_cash:
					fenhong = max_cash - max_bonus
					sql = """
						update zx_member set proxy_state = 0 where uid = %s 
					""" % (uid)

					conn.dml(sql, 'update')
				else:
					fenhong = fenhong

				jiangjinbi_award, rongzidun_award, lovemoney_award, platmoney_award, taxmoney_award = 0, 0, 0, 0, 0

				for r in rates:
					if r['category'] == 'jiangjinbi':
						jiangjinbi_rate = r['value'] / 100
						jiangjinbi_award = fenhong * jiangjinbi_rate
					elif r['category'] == 'rongzidun':
						rongzidun_rate = r['value'] / 100
						rongzidun_award = fenhong * rongzidun_rate
					elif r['category'] == 'lovemoney':
						lovemoney_rate = r['value'] / 100
						lovemoney_award = fenhong * lovemoney_rate
					elif r['category'] == 'platmoney':
						platmoney_rate = r['value'] / 100
						platmoney_award = fenhong * platmoney_rate
					elif r['category'] == 'taxmoney':
						taxmoney_rate = r['value'] / 100
						taxmoney_award = fenhong * taxmoney_rate

				# real_total 实发奖金
				real_total = fenhong - lovemoney_award - platmoney_award - taxmoney_award
				# 销费商虚拟币增加
				zx_member_sql = """
					update zx_member set jiangjinbi = jiangjinbi + %s, rongzidun = rongzidun + %s where usernumber = %s
				""" % (jiangjinbi_award, rongzidun_award, usernumber)
			
				zx_member = conn.dml(zx_member_sql, 'update')

				if zx_member:
					max_bonus_sql = """
						update zx_member set max_bonus = max_bonus + %s where uid = %s
					""" % (fenhong, uid)
					conn.dml(max_bonus_sql, 'update')

					# 分红奖金支出
					zx_finance_sql = """
						update zx_finance set expend = expend + %s, createtime = %s
					""" % (fenhong, now_second)
					conn.dml(zx_finance_sql, 'update')

					# 明细
					zx_bonus_detail_sql = """
						insert into zx_bonus_detail (touserid, tousernumber, torealname, moneytype, jiangjinbi, rongzidun, lovemoney, platmoney, taxmoney, total, real_total, createdate) 
						values (%s, %s, '%s', %s, %s, %s, %s, %s, %s, %s, %s, %s)
					""" % (uid, usernumber, realname, 1, jiangjinbi_award, rongzidun_award, lovemoney_award, platmoney_award, taxmoney_award, fenhong, real_total, yes_second)
					#  插入明细表
	 				conn.dml(zx_bonus_detail_sql, 'insert')

					# 奖金币流水
					jiangjinbi_change_sql = """
						insert into zx_money_change (moneytype, status, targetuserid, targetusernumber, targetrealname, userid, usernumber, realname, changetype, recordtype, money, createtime)
						values (%s, %s, %s, %s, '%s', %s, %s, '%s', %s, %s, %s, %s)
					""" % (1, 1, uid, usernumber, realname, 1, 1, '戎子', 3, 1, jiangjinbi_award, now_second)
					
					conn.dml(jiangjinbi_change_sql, 'insert')
					# 戎子盾流水
					rongzidun_change_sql = """
						insert into zx_money_change (moneytype, status, targetuserid, targetusernumber, targetrealname, userid, usernumber, realname, changetype, recordtype, money, createtime)
						values (%s, %s, %s, %s, '%s', %s, %s, '%s', %s, %s, %s, %s)
					""" % (3, 3, uid, usernumber, realname, 1, 1, '戎子', 3, 1, rongzidun_award, now_second)
					conn.dml(rongzidun_change_sql, 'insert')
					# 爱心基金流水
					lovemoney_change_sql = """
						insert into zx_money_change (moneytype, status, targetuserid, targetusernumber, targetrealname, userid, usernumber, realname, changetype, recordtype, money, createtime)
						values (%s, %s, %s, %s, '%s', %s, %s, '%s', %s, %s, %s, %s)
					""" % (6, 6, uid, usernumber, realname, 1, 1, '戎子', 3, 0, lovemoney_award, now_second)
					conn.dml(lovemoney_change_sql, 'insert')
					# 平台管理费流水
					platmoney_change_sql = """
						insert into zx_money_change (moneytype, status, targetuserid, targetusernumber, targetrealname, userid, usernumber, realname, changetype, recordtype, money, createtime)
						values (%s, %s, %s, %s, '%s', %s, %s, '%s', %s, %s, %s, %s)
					""" % (7, 7, uid, usernumber, realname, 1, 1, '戎子', 3, 0, platmoney_award, now_second)
					conn.dml(platmoney_change_sql, 'insert')
					# 税费流水
					taxmoney_change_sql = """
						insert into zx_money_change (moneytype, status, targetuserid, targetusernumber, targetrealname, userid, usernumber, realname, changetype, recordtype, money, createtime)
						values (%s, %s, %s, %s, '%s', %s, %s, '%s', %s, %s, %s, %s)
					""" % (8, 8, uid, usernumber, realname, 1, 1, '戎子', 3, 0, taxmoney_award, now_second)
					conn.dml(taxmoney_change_sql, 'insert')

	conn.close()

if __name__ == '__main__':
	fenhong()