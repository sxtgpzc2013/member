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

# 插入管理补贴明细,流水
def insert_bonus_detail_2(uid, usernumber, realname, managercash):
	# 比率配比
	rates = rate()
	jiangjinbi_award, rongzidun_award, lovemoney_award, platmoney_award, taxmoney_award = 0, 0, 0, 0, 0
	for r in rates:
		if r['category'] == 'jiangjinbi':
			jiangjinbi_rate = r['value'] / 100
			jiangjinbi_award = managercash * jiangjinbi_rate
		elif r['category'] == 'rongzidun':
			rongzidun_rate = r['value'] / 100
			rongzidun_award = managercash * rongzidun_rate
		elif r['category'] == 'lovemoney':
			lovemoney_rate = r['value'] / 100
			lovemoney_award = managercash * lovemoney_rate
		elif r['category'] == 'platmoney':
			platmoney_rate = r['value'] / 100
			platmoney_award = managercash * platmoney_rate
		elif r['category'] == 'taxmoney':
			taxmoney_rate = r['value'] / 100
			taxmoney_award = managercash * taxmoney_rate

	real_total = managercash - lovemoney_award - platmoney_award - taxmoney_award
	zx_member_sql = """
		update zx_member set jiangjinbi = jiangjinbi + %s, rongzidun = rongzidun + %s where usernumber = %s
	""" % (jiangjinbi_award, rongzidun_award, usernumber)
	zx_member = conn.dml(zx_member_sql, 'update')
	if zx_member:
		max_bonus_sql = """
			update zx_member set max_bonus = max_bonus + %s where uid = %s
		""" % (managercash, uid)
		conn.dml(max_bonus_sql, 'update')

		zx_finance_sql = """
			update zx_finance set expend = expend + %s, createtime = %s
		""" % (managercash, now_second)
		conn.dml(zx_finance_sql, 'update')
		# 明细
		zx_bonus_detail_sql = """
			insert into zx_bonus_detail (touserid, tousernumber, torealname, moneytype, jiangjinbi, rongzidun, lovemoney, platmoney, taxmoney, total, real_total, createdate)
            values (%s, %s, '%s', %s, %s, %s, %s, %s, %s, %s, %s, %s)
		""" % (uid, usernumber, realname, 2, jiangjinbi_award, rongzidun_award, lovemoney_award, platmoney_award, taxmoney_award, managercash, real_total, now_second)
		conn.dml(zx_bonus_detail_sql, 'insert')
		# 奖金币流水
		jiangjinbi_change_sql = """
			insert into zx_money_change (moneytype, status, targetuserid, targetusernumber, targetrealname, userid, usernumber, realname, changetype, recordtype, money, createtime)
            values (%s, %s, %s, %s, '%s', %s, %s, '%s', %s, %s, %s, %s)
		""" % (1, 1, uid, usernumber, realname, 1, 1, '戎子', 4, 1, jiangjinbi_award, now_second)
		conn.dml(jiangjinbi_change_sql, 'insert')

		jiangjinbi_change_sql_1 = """
			insert into zx_money_change (moneytype, status, targetuserid, targetusernumber, targetrealname, userid, usernumber, realname, changetype, recordtype, money, createtime)
            values (%s, %s, %s, %s, '%s', %s, %s, '%s', %s, %s, %s, %s)
		""" % (1, 1, 1, 1, '戎子', uid, usernumber, realname, 4, 0, jiangjinbi_award, now_second)
		conn.dml(jiangjinbi_change_sql_1, 'insert')
		# 戎子盾流水
		rongzidun_change_sql = """
			insert into zx_money_change (moneytype, status, targetuserid, targetusernumber, targetrealname, userid, usernumber, realname, changetype, recordtype, money, createtime)
            values (%s, %s, %s, %s, '%s', %s, %s, '%s', %s, %s, %s, %s)
		""" % (3, 3, uid, usernumber, realname, 1, 1, '戎子', 4, 1, rongzidun_award, now_second)
		conn.dml(rongzidun_change_sql, 'insert')

		rongzidun_change_sql_1 = """
			insert into zx_money_change (moneytype, status, targetuserid, targetusernumber, targetrealname, userid, usernumber, realname, changetype, recordtype, money, createtime)
            values (%s, %s, %s, %s, '%s', %s, %s, '%s', %s, %s, %s, %s)
		""" % (3, 3, 1, 1, '戎子', uid, usernumber, realname, 4, 0, rongzidun_award, now_second)
		conn.dml(rongzidun_change_sql_1, 'insert')

		# 爱心基金流水
		lovemoney_change_sql = """
			insert into zx_money_change (moneytype, status, targetuserid, targetusernumber, targetrealname, userid, usernumber, realname, changetype, recordtype, money, createtime)
            values (%s, %s, %s, %s, '%s', %s, %s, '%s', %s, %s, %s, %s)
		""" % (6, 6, uid, usernumber, realname, 1, 1, '戎子', 4, 0, lovemoney_award, now_second)
		conn.dml(lovemoney_change_sql, 'insert')

		# 爱心基金流水
		lovemoney_change_sql_1 = """
			insert into zx_money_change (moneytype, status, targetuserid, targetusernumber, targetrealname, userid, usernumber, realname, changetype, recordtype, money, createtime)
            values (%s, %s, %s, %s, '%s', %s, %s, '%s', %s, %s, %s, %s)
		""" % (6, 6, 1, 1, '戎子', uid, usernumber, realname, 4, 1, lovemoney_award, now_second)
		conn.dml(lovemoney_change_sql_1, 'insert')
		# 平台管理费流水
		platmoney_change_sql = """
			insert into zx_money_change (moneytype, status, targetuserid, targetusernumber, targetrealname, userid, usernumber, realname, changetype, recordtype, money, createtime)
            values (%s, %s, %s, %s, '%s', %s, %s, '%s', %s, %s, %s, %s)
		""" % (7, 7, uid, usernumber, realname, 1, 1, '戎子', 4, 0, platmoney_award, now_second)
		conn.dml(platmoney_change_sql, 'insert')

		platmoney_change_sql_1 = """
			insert into zx_money_change (moneytype, status, targetuserid, targetusernumber, targetrealname, userid, usernumber, realname, changetype, recordtype, money, createtime)
            values (%s, %s, %s, %s, '%s', %s, %s, '%s', %s, %s, %s, %s)
		""" % (7, 7, 1, 1, '戎子', uid, usernumber, realname, 4, 1, platmoney_award, now_second)
		conn.dml(platmoney_change_sql_1, 'insert')
		# 税费流水
		taxmoney_change_sql = """
			insert into zx_money_change (moneytype, status, targetuserid, targetusernumber, targetrealname, userid, usernumber, realname, changetype, recordtype, money, createtime)
            values (%s, %s, %s, %s, '%s', %s, %s, '%s', %s, %s, %s, %s)
		""" % (8, 8, uid, usernumber, realname, 1, 1, '戎子', 4, 0, taxmoney_award, now_second)
		conn.dml(taxmoney_change_sql, 'insert')

		taxmoney_change_sql_1 = """
			insert into zx_money_change (moneytype, status, targetuserid, targetusernumber, targetrealname, userid, usernumber, realname, changetype, recordtype, money, createtime)
            values (%s, %s, %s, %s, '%s', %s, %s, '%s', %s, %s, %s, %s)
		""" % (8, 8, 1, 1, '戎子', uid, usernumber, realname, 4, 1, taxmoney_award, now_second)
		conn.dml(taxmoney_change_sql_1, 'insert')

	return True

# 插入互助补贴明细,流水
def insert_bonus_detail_3(uid, usernumber, realname, leadercash):
	# 比率配比
	rates = rate()
	jiangjinbi_award, rongzidun_award, lovemoney_award, platmoney_award, taxmoney_award = 0, 0, 0, 0, 0
	for r in rates:
		if r['category'] == 'jiangjinbi':
			jiangjinbi_rate = r['value'] / 100
			jiangjinbi_award = leadercash * jiangjinbi_rate
		elif r['category'] == 'rongzidun':
			rongzidun_rate = r['value'] / 100
			rongzidun_award = leadercash * rongzidun_rate
		elif r['category'] == 'lovemoney':
			lovemoney_rate = r['value'] / 100
			lovemoney_award = leadercash * lovemoney_rate
		elif r['category'] == 'platmoney':
			platmoney_rate = r['value'] / 100
			platmoney_award = leadercash * platmoney_rate
		elif r['category'] == 'taxmoney':
			taxmoney_rate = r['value'] / 100
			taxmoney_award = leadercash * taxmoney_rate

	real_total = leadercash - lovemoney_award - platmoney_award - taxmoney_award
	zx_member_sql = """
		update zx_member set jiangjinbi = jiangjinbi + %s, rongzidun = rongzidun + %s where usernumber = %s
	""" % (jiangjinbi_award, rongzidun_award, usernumber)
	zx_member = conn.dml(zx_member_sql, 'update')
	if zx_member:
		max_bonus_sql = """
			update zx_member set max_bonus = max_bonus + %s where uid = %s
		""" % (leadercash, uid)
		conn.dml(max_bonus_sql, 'update')

		zx_finance_sql = """
			update zx_finance set expend = expend + %s, createtime = %s
		""" % (leadercash, now_second)
		conn.dml(zx_finance_sql, 'update')
		# 明细
		zx_bonus_detail_sql = """
			insert into zx_bonus_detail (touserid, tousernumber, torealname, moneytype, jiangjinbi, rongzidun, lovemoney, platmoney, taxmoney, total, real_total, createdate)
            values (%s, %s, '%s', %s, %s, %s, %s, %s, %s, %s, %s, %s)
		""" % (uid, usernumber, realname, 3, jiangjinbi_award, rongzidun_award, lovemoney_award, platmoney_award, taxmoney_award, leadercash, real_total, now_second)
		conn.dml(zx_bonus_detail_sql, 'insert')
		# 奖金币流水
		jiangjinbi_change_sql = """
			insert into zx_money_change (moneytype, status, targetuserid, targetusernumber, targetrealname, userid, usernumber, realname, changetype, recordtype, money, createtime)
            values (%s, %s, %s, %s, '%s', %s, %s, '%s', %s, %s, %s, %s)
		""" % (1, 1, uid, usernumber, realname, 1, 1, '戎子', 5, 1, jiangjinbi_award, now_second)
		conn.dml(jiangjinbi_change_sql, 'insert')

		jiangjinbi_change_sql_1 = """
			insert into zx_money_change (moneytype, status, targetuserid, targetusernumber, targetrealname, userid, usernumber, realname, changetype, recordtype, money, createtime)
            values (%s, %s, %s, %s, '%s', %s, %s, '%s', %s, %s, %s, %s)
		""" % (1, 1, 1, 1, '戎子', uid, usernumber, realname, 5, 0, jiangjinbi_award, now_second)
		conn.dml(jiangjinbi_change_sql_1, 'insert')
		# 戎子盾流水
		rongzidun_change_sql = """
			insert into zx_money_change (moneytype, status, targetuserid, targetusernumber, targetrealname, userid, usernumber, realname, changetype, recordtype, money, createtime)
            values (%s, %s, %s, %s, '%s', %s, %s, '%s', %s, %s, %s, %s)
		""" % (3, 3, uid, usernumber, realname, 1, 1, '戎子', 5, 1, rongzidun_award, now_second)
		conn.dml(rongzidun_change_sql, 'insert')

		rongzidun_change_sql_1 = """
			insert into zx_money_change (moneytype, status, targetuserid, targetusernumber, targetrealname, userid, usernumber, realname, changetype, recordtype, money, createtime)
            values (%s, %s, %s, %s, '%s', %s, %s, '%s', %s, %s, %s, %s)
		""" % (3, 3, 1, 1, '戎子', uid, usernumber, realname, 5, 0, rongzidun_award, now_second)
		conn.dml(rongzidun_change_sql_1, 'insert')

		# 爱心基金流水
		lovemoney_change_sql = """
			insert into zx_money_change (moneytype, status, targetuserid, targetusernumber, targetrealname, userid, usernumber, realname, changetype, recordtype, money, createtime)
            values (%s, %s, %s, %s, '%s', %s, %s, '%s', %s, %s, %s, %s)
		""" % (6, 6, uid, usernumber, realname, 1, 1, '戎子', 5, 0, lovemoney_award, now_second)
		conn.dml(lovemoney_change_sql, 'insert')

		lovemoney_change_sql_1 = """
			insert into zx_money_change (moneytype, status, targetuserid, targetusernumber, targetrealname, userid, usernumber, realname, changetype, recordtype, money, createtime)
            values (%s, %s, %s, %s, '%s', %s, %s, '%s', %s, %s, %s, %s)
		""" % (6, 6, 1, 1, '戎子', uid, usernumber, realname, 5, 1, lovemoney_award, now_second)
		conn.dml(lovemoney_change_sql_1, 'insert')
		# 平台管理费流水
		platmoney_change_sql = """
			insert into zx_money_change (moneytype, status, targetuserid, targetusernumber, targetrealname, userid, usernumber, realname, changetype, recordtype, money, createtime)
            values (%s, %s, %s, %s, '%s', %s, %s, '%s', %s, %s, %s, %s)
		""" % (7, 7, uid, usernumber, realname, 1, 1, '戎子', 5, 0, platmoney_award, now_second)
		conn.dml(platmoney_change_sql, 'insert')

		platmoney_change_sql_1 = """
			insert into zx_money_change (moneytype, status, targetuserid, targetusernumber, targetrealname, userid, usernumber, realname, changetype, recordtype, money, createtime)
            values (%s, %s, %s, %s, '%s', %s, %s, '%s', %s, %s, %s, %s)
		""" % (7, 7, 1, 1, '戎子', uid, usernumber, realname, 5, 1, platmoney_award, now_second)
		conn.dml(platmoney_change_sql_1, 'insert')
		# 税费流水
		taxmoney_change_sql = """
			insert into zx_money_change (moneytype, status, targetuserid, targetusernumber, targetrealname, userid, usernumber, realname, changetype, recordtype, money, createtime)
            values (%s, %s, %s, %s, '%s', %s, %s, '%s', %s, %s, %s, %s)
		""" % (8, 8, uid, usernumber, realname, 1, 1, '戎子', 5, 0, taxmoney_award, now_second)
		conn.dml(taxmoney_change_sql, 'insert')

		taxmoney_change_sql_1 = """
			insert into zx_money_change (moneytype, status, targetuserid, targetusernumber, targetrealname, userid, usernumber, realname, changetype, recordtype, money, createtime)
            values (%s, %s, %s, %s, '%s', %s, %s, '%s', %s, %s, %s, %s)
		""" % (8, 8, 1, 1, '戎子', uid, usernumber, realname, 5, 1, taxmoney_award, now_second)
		conn.dml(taxmoney_change_sql_1, 'insert')

	return True

def getmemberinfo(uid):
	flag = False
	sql = """
		select usernumber, realname from zx_member where uid = %s
	""" % (uid)
	result = conn.query(sql)
	if result:
		return result

	return flag

#插入互助补贴明细, 流水
def leaderbonus(uid, managercash):
	sql = """
		select `key`, value from zx_bonus_rule where category = 'leadercash'
	"""
	rates = conn.query(sql)
	rate1 = 0
	rate2 = 0
	rate3 = 0

	if rates:
		for rate in rates:
			if rate['key'] == 1:
				rate1 = rate['value']
			elif rate['key'] == 2:
				rate2 = rate['value']
			elif rate['key'] == 3:
				rate3 = rate['value']
	else:
		rates = (
			{'key': '1', 'value': 15},
			{'key': '3', 'value': 10},
			{'key': '5', 'value': 5}
		)
		for rate in rates:
			if rate['key'] == 1:
				rate1 = rate['value']
			elif rate['key'] == 2:
				rate2 = rate['value']
			elif rate['key'] == 3:
				rate3 = rate['value']

	_uids = gettuijiannumber_parent(uid)

	for i, v in enumerate(_uids):
		if int(v) == 1:
			del _uids[i]
		else:
			# 过滤掉普卡
			filter_member_sql = """
				select uid from zx_member where uid = %s and userrank = 1
			""" % (v)
			result = conn.query(filter_member_sql)
			if result:
				del _uids[i]

	lengh = len(_uids)
	if lengh > 3:
		uids = _uids[0:3]
	else:
		uids =  _uids[0:lengh]

	i = 0
	leadercash = 0
	if uids:
		for _uid in uids:
			result = getmemberinfo(_uid)
			if result:
				usernumber = result[0]['usernumber']
				realname = result[0]['realname']

				i += 1
				if i == 1:
					leadercash = managercash * rate1 / 100
				elif i == 2:
					leadercash = managercash * rate2 / 100
				elif i == 3:
					leadercash = managercash * rate3 / 100

				insert_bonus_detail_3(_uid, usernumber, realname, leadercash)

def member_achievement_status(uid):
	flag = False
	sql = """
		select active_time from zx_member where uid = %s and achievementstatus = 0
	""" % (uid)
	result = conn.query(sql)
	if result:
		return True
	else:
		flag = False

	return flag

# 通过子uid获取父拓展
def gettuijiannumber_parent(uid):
	parents = []
	sql = """
		select recommenduserpath from zx_member where uid = %s
	"""  % (uid)
	result = conn.query(sql)
	if result:
		parents = result[0]['recommenduserpath'].split(',')

	return parents[-2::-1]

def getuservalue(parents):
	members = []
	for uid in parents:
		if int(uid) == 1:
			break

		val = []
		sql = """
			select m.uid, m.usertitle, r.value from zx_member as m left join zx_bonus_rule as r on m.usertitle = r.key
			where m.uid = %s and category = 'managercash' and m.userrank != 1 and m.usertitle != 0
		""" % (uid)
		result = conn.query(sql)
		if result:
			val.append(result[0]['uid'])
			val.append(result[0]['usertitle'])
			val.append(result[0]['value'])
			members.append(val)

	return members

# 获取管理奖比例
def getmaxmanagercash(usertitle):
	value = 0
	sql = """
		select value from zx_bonus_rule where `key` = %s and category = 'managercash'
	""" % (usertitle)
	result = conn.query(sql)
	if result:
		value = result[0]['value']

	return value

# 获取会员的级别对应的金额
def getmembervalue(uid):
	value = 0
	sql = """
		select r.value from zx_member as m left join zx_bonus_rule as r on m.userrank = r.key
		where r.category = 'userrank' and m.uid = %s
	""" % (uid)
	result = conn.query(sql)
	if result:
		value = result[0]['value']

	return value

# 极差算法
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
				managercash = value * _member_value / 100
				result = getmemberinfo(member_uid)
				if result:
					status = insert_bonus_detail_2(member_uid, result[0]['usernumber'], result[0]['realname'], managercash)
					if status:
						leaderbonus(member_uid, managercash)
		elif index == 0:
			member_uid = int(memberlevels[index][0])
			member_title = int(memberlevels[index][1])
			member_value = int(memberlevels[index][2])
			managercash = value * member_value / 100
			result = getmemberinfo(member_uid)
			if result:
				status = insert_bonus_detail_2(member_uid, result[0]['usernumber'], result[0]['realname'], managercash)
				if status:
					leaderbonus(member_uid, managercash)

	return True

#更新会员的业绩状态
def update_achievement_status(uid):
	sql = """
		update zx_member set achievementstatus = 1 where uid = %s
	""" % (uid)

	status = conn.dml(sql, 'update')
	return status

# 通过拓展的人计算管理奖
def managerbonus(uid):
	flag = False
	# 获取拓展人的级别金额
	value = getmembervalue(uid)
	# 获取拓展的人的父级
	parents = gettuijiannumber_parent(uid)

	if parents:
		# 赛选有星级的会员
		memberlevels = getuservalue(parents)
		if memberlevels:
			status = jicha(value, memberlevels)
			return status

	return flag

# 管理补贴和互助补贴
def main():
	if len(sys.argv) >= 2:
		uid = sys.argv[1]
		status = managerbonus(uid)	
		if status:
			update_achievement_status(uid)

	conn.close()
	print "ok" 

if __name__ == '__main__':
	main()
