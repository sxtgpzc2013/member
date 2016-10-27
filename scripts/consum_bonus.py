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
now_second = datetime.datetime.now().strftime('%s')

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

def getmemberinfo(uid):
	flag = False
	sql = """
		select usernumber, realname from zx_member where uid = %s
	""" % (uid)
	result = conn.query(sql)
	if result:
		return result

	return flag

# 插入二次消费补贴明细,流水
def insert_bonus_detail_8(uid, usernumber, realname, repeatcash):
	# 会员
	member_sql = """
					select m.userrank, r.value from zx_member as m left join zx_bonus_rule as r
					on m.userrank = r.key
	 				where m.status = 1 and r.category = 'userrank' and m.uid = %s
	""" % (uid)
	member = conn.query(member_sql)
	if member:
		userrank = member[0]['userrank']
		value = member[0]['value']

		rates = rate()
		jiangjinbi_award, rongzidun_award, lovemoney_award, platmoney_award, taxmoney_award = 0, 0, 0, 0, 0
		for r in rates:
			if r['category'] == 'jiangjinbi':
				jiangjinbi_rate = r['value'] / 100
				jiangjinbi_award = repeatcash * jiangjinbi_rate
			elif r['category'] == 'rongzidun':
				rongzidun_rate = r['value'] / 100
				rongzidun_award = repeatcash * rongzidun_rate
			elif r['category'] == 'lovemoney':
				lovemoney_rate = r['value'] / 100
				lovemoney_award = repeatcash * lovemoney_rate
			elif r['category'] == 'platmoney':
				platmoney_rate = r['value'] / 100
				platmoney_award = repeatcash * platmoney_rate
			elif r['category'] == 'taxmoney':
				taxmoney_rate = r['value'] / 100
				taxmoney_award = repeatcash * taxmoney_rate

		real_total = repeatcash - lovemoney_award - platmoney_award - taxmoney_award
		zx_member_sql = """
			update zx_member set jiangjinbi = jiangjinbi + %s, rongzidun = rongzidun + %s where usernumber = %s
		""" % (jiangjinbi_award, rongzidun_award, usernumber)
		zx_member = conn.dml(zx_member_sql, 'update')
		if zx_member:
			max_bonus_sql = """
				update zx_member set max_bonus = max_bonus + %s where uid = %s
			""" % (repeatcash, uid)
			conn.dml(max_bonus_sql, 'update')

			zx_finance_sql = """
				update zx_finance set expend = expend + %s, createtime = %s
			""" % (repeatcash, now_second)
			conn.dml(zx_finance_sql, 'update')
			# 明细
			zx_bonus_detail_sql = """
				insert into zx_bonus_detail (touserid, tousernumber, torealname, moneytype, jiangjinbi, rongzidun, lovemoney, platmoney, taxmoney, total, real_total, createdate)
	            values (%s, %s, '%s', %s, %s, %s, %s, %s, %s, %s, %s, %s)
			""" % (uid, usernumber, realname, 8, jiangjinbi_award, rongzidun_award, lovemoney_award, platmoney_award, taxmoney_award, repeatcash, real_total, now_second)
			conn.dml(zx_bonus_detail_sql, 'insert')
			# 奖金币流水
			jiangjinbi_change_sql = """
				insert into zx_money_change (moneytype, status, targetuserid, targetusernumber, targetrealname, userid, usernumber, realname, changetype, recordtype, money, createtime)
	            values (%s, %s, %s, %s, '%s', %s, %s, '%s', %s, %s, %s, %s)
			""" % (1, 1, uid, usernumber, realname, 1, 1, '戎子', 10, 1, jiangjinbi_award, now_second)
			conn.dml(jiangjinbi_change_sql, 'insert')

			jiangjinbi_change_sql_1 = """
				insert into zx_money_change (moneytype, status, targetuserid, targetusernumber, targetrealname, userid, usernumber, realname, changetype, recordtype, money, createtime)
	            values (%s, %s, %s, %s, '%s', %s, %s, '%s', %s, %s, %s, %s)
			""" % (1, 1, 1, 1, '戎子', uid, usernumber, realname, 10, 0, jiangjinbi_award, now_second)
			conn.dml(jiangjinbi_change_sql_1, 'insert')
			# 戎子盾流水
			rongzidun_change_sql = """
				insert into zx_money_change (moneytype, status, targetuserid, targetusernumber, targetrealname, userid, usernumber, realname, changetype, recordtype, money, createtime)
	            values (%s, %s, %s, %s, '%s', %s, %s, '%s', %s, %s, %s, %s)
			""" % (3, 3, uid, usernumber, realname, 1, 1, '戎子', 10, 1, rongzidun_award, now_second)
			conn.dml(rongzidun_change_sql, 'insert')

			rongzidun_change_sql_1 = """
				insert into zx_money_change (moneytype, status, targetuserid, targetusernumber, targetrealname, userid, usernumber, realname, changetype, recordtype, money, createtime)
	            values (%s, %s, %s, %s, '%s', %s, %s, '%s', %s, %s, %s, %s)
			""" % (3, 3, 1, 1, '戎子', uid, usernumber, realname, 10, 0, rongzidun_award, now_second)
			conn.dml(rongzidun_change_sql_1, 'insert')
			# 爱心基金流水
			lovemoney_change_sql = """
				insert into zx_money_change (moneytype, status, targetuserid, targetusernumber, targetrealname, userid, usernumber, realname, changetype, recordtype, money, createtime)
	            values (%s, %s, %s, %s, '%s', %s, %s, '%s', %s, %s, %s, %s)
			""" % (6, 6, uid, usernumber, realname, 1, 1, '戎子', 10, 0, lovemoney_award, now_second)
			conn.dml(lovemoney_change_sql, 'insert')

			lovemoney_change_sql_1 = """
				insert into zx_money_change (moneytype, status, targetuserid, targetusernumber, targetrealname, userid, usernumber, realname, changetype, recordtype, money, createtime)
	            values (%s, %s, %s, %s, '%s', %s, %s, '%s', %s, %s, %s, %s)
			""" % (6, 6, 1, 1, '戎子', uid, usernumber, realname, 10, 1, lovemoney_award, now_second)
			conn.dml(lovemoney_change_sql_1, 'insert')
			# 平台管理费流水
			platmoney_change_sql = """
				insert into zx_money_change (moneytype, status, targetuserid, targetusernumber, targetrealname, userid, usernumber, realname, changetype, recordtype, money, createtime)
	            values (%s, %s, %s, %s, '%s', %s, %s, '%s', %s, %s, %s, %s)
			""" % (7, 7, uid, usernumber, realname, 1, 1, '戎子', 10, 0, platmoney_award, now_second)
			conn.dml(platmoney_change_sql, 'insert')

			platmoney_change_sql_1 = """
				insert into zx_money_change (moneytype, status, targetuserid, targetusernumber, targetrealname, userid, usernumber, realname, changetype, recordtype, money, createtime)
	            values (%s, %s, %s, %s, '%s', %s, %s, '%s', %s, %s, %s, %s)
			""" % (7, 7, 1, 1, '戎子', uid, usernumber, realname, 10, 1, platmoney_award, now_second)
			conn.dml(platmoney_change_sql_1, 'insert')
			# 税费流水
			taxmoney_change_sql = """
				insert into zx_money_change (moneytype, status, targetuserid, targetusernumber, targetrealname, userid, usernumber, realname, changetype, recordtype, money, createtime)
	            values (%s, %s, %s, %s, '%s', %s, %s, '%s', %s, %s, %s, %s)
			""" % (8, 8, uid, usernumber, realname, 1, 1, '戎子', 10, 0, taxmoney_award, now_second)
			conn.dml(taxmoney_change_sql, 'insert')

			taxmoney_change_sql_1 = """
				insert into zx_money_change (moneytype, status, targetuserid, targetusernumber, targetrealname, userid, usernumber, realname, changetype, recordtype, money, createtime)
	            values (%s, %s, %s, %s, '%s', %s, %s, '%s', %s, %s, %s, %s)
			""" % (8, 8, 1, 1, '戎子', uid, usernumber, realname, 10, 1, taxmoney_award, now_second)
			conn.dml(taxmoney_change_sql_1, 'insert')


# 极差算法, value 是销售的金额
def jicha(value, memberlevels):
	for index, val in enumerate(memberlevels):
		if index > 0:
			flag = False
			member_uid = int(memberlevels[index][0])
			member_title = int(memberlevels[index][1])
			member_value = int(memberlevels[index][2])
			i = 0
			for x in range(0, index):
				if member_title > int(memberlevels[x][1]):
					flag = True
				elif member_title == int(memberlevels[x][1]):
					flag = False
					break
				elif member_title < int(memberlevels[x][1]):
					flag = False
					break
				i = int(memberlevels[x][2])

			if flag:
				_member_value = member_value - i
				repeatcash = value * _member_value / 100
				result = getmemberinfo(member_uid)
				if result:
					insert_bonus_detail_8(member_uid, result[0]['usernumber'], result[0]['realname'], repeatcash)
					
		elif index == 0:
			member_uid = int(memberlevels[index][0])
			member_title = int(memberlevels[index][1])
			member_value = int(memberlevels[index][2])
			repeatcash = value * member_value / 100
			result = getmemberinfo(member_uid)
			if result:
				insert_bonus_detail_8(member_uid, result[0]['usernumber'], result[0]['realname'], repeatcash)
			
	return True

def getuservalue(parents):
	members = []
	for uid in parents:
		val = []
		sql = """
			select m.uid, m.usertitle, m.isbill, r.value from zx_member as m left join zx_bonus_rule as r on m.usertitle = r.key
			where m.uid = %s and category = 'repeatcash' and m.userrank != 1
		""" % (uid)
		result = conn.query(sql)
		if result and result[0]['usertitle'] != 0:
			val.append(result[0]['uid'])
			val.append(result[0]['usertitle'])
			val.append(result[0]['value'])
			members.append(val)

	return members

# 通过子uid获取父推荐
def gettuijiannumber_parent(uid):
	parents = []
	sql = """
		select recommenduserpath from zx_member where uid = %s
	"""  % (uid)
	result = conn.query(sql)

	if result:
		parents = result[0]['recommenduserpath'].split(',')

	return parents[-2::-1]

def main():
	# 获取销售商的uid和销售产品的金额
	if len(sys.argv) >= 3:
		uid = int(sys.argv[1])
		value = int(sys.argv[2])
		parents = gettuijiannumber_parent(uid)
		if parents:
			memberlevels = getuservalue(parents)
			if memberlevels:
				jicha(value, memberlevels)

	conn.close()
	print "ok"

if __name__ == '__main__':
	main()