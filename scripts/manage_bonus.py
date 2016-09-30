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
yes_second = (now + datetime.timedelta(days=-1)).strftime('%s')
yes_time = (now + datetime.timedelta(days=-1)).strftime('%Y-%m-%d')


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

def compare(x, y, z):
	values = []
	values.append(x)
	values.append(y)
	values.append(z)

	for i, v in enumerate(values):
		if v == max(values):
			del values[i]
			break

	if len(values) == 2:
		value = values[0] + values[1]
		return value
	else:
		return 0

def update_member(usertitle, jianglijifen, usernumber):
	sql = """
		update zx_member set usertitle = %s, jianglijifen = jianglijifen + %s where usernumber = %s 
	""" % (usertitle, jianglijifen, usernumber)

	return conn.dml(sql, 'update')

# 插入奖励积分明细
def insert_bonus_detail_jianglijifen(uid, usernumber, realname, moneytype, jianglijifen, yes_second):
	sql = """
		insert into zx_bonus_detail (touserid, tousernumber, torealname, moneytype, jianglijifen, createdate) 
		values (%s, %s, '%s', %s, %s, %s)
	""" % (uid, usernumber, realname, moneytype, jianglijifen, yes_second)

	return conn.dml(sql, 'insert')

# 插入奖励积分流水
def insert_money_change_jianglijifen(moneytype, uid, usernumber, realname, changetype, recordtype, jianglijifen, createtime):
	sql = """
		insert into zx_money_change (moneytype, status, targetuserid, targetusernumber, targetrealname, userid, usernumber, realname, changetype, recordtype, money, createtime)
		values (%s, %s, %s, %s, '%s', %s, %s, '%s', %s, %s, %s, %s)
	""" % (moneytype, 1, uid, usernumber, realname, 1, 1, '戎子', changetype, recordtype, jianglijifen, createtime)

	return conn.dml(sql, 'insert')

# 插入管理补贴明细,流水
def insert_bonus_detail_2(uid, usernumber, realname, managercash, now_second):
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
	sql = """
		update zx_member set jiangjinbi = jiangjinbi + %s, rongzidun = rongzidun + %s where usernumber = %s
	""" % (jiangjinbi_award, rongzidun_award, usernumber)
	zx_member = conn.dml(zx_member_sql, 'update')
	if zx_member:
		zx_finance_sql = """
			update zx_finance set expend = expend + %s, createtime = %s
		""" % (managercash, now_second)
		# 明细
		zx_bonus_detail_sql = """
			insert into zx_bonus_detail (touserid, tousernumber, torealname, moneytype, jiangjinbi, rongzidun, lovemoney, platmoney, taxmoney, total, real_total, createdate) 
            values (%s, %s, '%s', %s, %s, %s, %s, %s, %s, %s, %s, %s)
		""" % (uid, usernumber, realname, 1, jiangjinbi_award, rongzidun_award, lovemoney_award, platmoney_award, taxmoney_award, managercash, real_total, yes_second)
		#  插入明细表
		conn.dml(zx_bonus_detail_sql, 'insert')
		jiangjinbi_change_sql = """
			insert into zx_money_change (moneytype, status, targetuserid, targetusernumber, targetrealname, userid, usernumber, realname, changetype, recordtype, money, createtime)
            values (%s, %s, %s, %s, '%s', %s, %s, '%s', %s, %s, %s, %s)
		""" % (1, 1, uid, usernumber, realname, 1, 1, '戎子', 4, 1, jiangjinbi_award, now_second)
		conn.dml(jiangjinbi_change_sql, 'insert')
		# 戎子盾流水
		rongzidun_change_sql = """
			insert into zx_money_change (moneytype, status, targetuserid, targetusernumber, targetrealname, userid, usernumber, realname, changetype, recordtype, money, createtime)
            values (%s, %s, %s, %s, '%s', %s, %s, '%s', %s, %s, %s, %s)
		""" % (3, 3, uid, usernumber, realname, 1, 1, '戎子', 4, 1, rongzidun_award, now_second)
		conn.dml(rongzidun_change_sql, 'insert')
		# 爱心基金流水
		lovemoney_change_sql = """
			insert into zx_money_change (moneytype, status, targetuserid, targetusernumber, targetrealname, userid, usernumber, realname, changetype, recordtype, money, createtime)
            values (%s, %s, %s, %s, '%s', %s, %s, '%s', %s, %s, %s, %s)
		""" % (6, 6, uid, usernumber, realname, 1, 1, '戎子', 4, 0, lovemoney_award, now_second)
		conn.dml(lovemoney_change_sql, 'insert')
		# 平台管理费流水
		platmoney_change_sql = """
			insert into zx_money_change (moneytype, status, targetuserid, targetusernumber, targetrealname, userid, usernumber, realname, changetype, recordtype, money, createtime)
            values (%s, %s, %s, %s, '%s', %s, %s, '%s', %s, %s, %s, %s)
		""" % (7, 7, uid, usernumber, realname, 1, 1, '戎子', 4, 0, platmoney_award, now_second)
		conn.dml(platmoney_change_sql, 'insert')
		# 税费流水
		taxmoney_change_sql = """
			insert into zx_money_change (moneytype, status, targetuserid, targetusernumber, targetrealname, userid, usernumber, realname, changetype, recordtype, money, createtime)
            values (%s, %s, %s, %s, '%s', %s, %s, '%s', %s, %s, %s, %s)
		""" % (8, 8, uid, usernumber, realname, 1, 1, '戎子', 4, 0, taxmoney_award, now_second)
		conn.dml(taxmoney_change_sql, 'insert')
	else:
		print "member is null"

# 插入互助补贴明细,流水
def insert_bonus_detail_3(uid, usernumber, realname, leadercash, now_second):
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
		zx_finance_sql = """
			update zx_finance set expend = expend + %s, createtime = %s
		""" % (leadercash, now_second)
		# 明细
		zx_bonus_detail_sql = """
			insert into zx_bonus_detail (touserid, tousernumber, torealname, moneytype, jiangjinbi, rongzidun, lovemoney, platmoney, taxmoney, total, real_total, createdate) 
            values (%s, %s, '%s', %s, %s, %s, %s, %s, %s, %s, %s, %s)
		""" % (uid, usernumber, realname, 3, jiangjinbi_award, rongzidun_award, lovemoney_award, platmoney_award, taxmoney_award, leadercash, real_total, yes_second)
		#  插入明细表
		conn.dml(zx_bonus_detail_sql, 'insert')

		jiangjinbi_change_sql = """
			insert into zx_money_change (moneytype, status, targetuserid, targetusernumber, targetrealname, userid, usernumber, realname, changetype, recordtype, money, createtime)
            values (%s, %s, %s, %s, '%s', %s, %s, '%s', %s, %s, %s, %s)
		""" % (1, 1, uid, usernumber, realname, 1, 1, '戎子', 5, 1, jiangjinbi_award, now_second)
		conn.dml(jiangjinbi_change_sql, 'insert')
		# 戎子盾流水
		rongzidun_change_sql = """
			insert into zx_money_change (moneytype, status, targetuserid, targetusernumber, targetrealname, userid, usernumber, realname, changetype, recordtype, money, createtime)
            values (%s, %s, %s, %s, '%s', %s, %s, '%s', %s, %s, %s, %s)
		""" % (3, 3, uid, usernumber, realname, 1, 1, '戎子', 5, 1, rongzidun_award, now_second)
		conn.dml(rongzidun_change_sql, 'insert')
		# 爱心基金流水
		lovemoney_change_sql = """
			insert into zx_money_change (moneytype, status, targetuserid, targetusernumber, targetrealname, userid, usernumber, realname, changetype, recordtype, money, createtime)
            values (%s, %s, %s, %s, '%s', %s, %s, '%s', %s, %s, %s, %s)
		""" % (6, 6, uid, usernumber, realname, 1, 1, '戎子', 5, 0, lovemoney_award, now_second)
		conn.dml(lovemoney_change_sql, 'insert')
		# 平台管理费流水
		platmoney_change_sql = """
			insert into zx_money_change (moneytype, status, targetuserid, targetusernumber, targetrealname, userid, usernumber, realname, changetype, recordtype, money, createtime)
            values (%s, %s, %s, %s, '%s', %s, %s, '%s', %s, %s, %s, %s)
		""" % (7, 7, uid, usernumber, realname, 1, 1, '戎子', 5, 0, platmoney_award, now_second)
		conn.dml(platmoney_change_sql, 'insert')
		# 税费流水
		taxmoney_change_sql = """
			insert into zx_money_change (moneytype, status, targetuserid, targetusernumber, targetrealname, userid, usernumber, realname, changetype, recordtype, money, createtime)
            values (%s, %s, %s, %s, '%s', %s, %s, '%s', %s, %s, %s, %s)
		""" % (8, 8, uid, usernumber, realname, 1, 1, '戎子', 5, 0, taxmoney_award, now_second)
		conn.dml(taxmoney_change_sql, 'insert')
	else:
		print "member is null"

def getmemberinfo(uid):
	sql = """
		select usernumber, realname from zx_member where uid = %s
	""" % (uid)
	result = conn.query(sql)
	if result:
		return result

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

	uids = gettuijiannumber_parent(uid)[0:3]
	
	for i, v in enumerate(uids):
		if int(v) == 1:
			del uids[i]
	i = 0
	leadercash = 0
	for uid in uids:
		result = getmemberinfo(uid)
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

			insert_bonus_detail_3(uid, usernumber, realname, leadercash, now_second)
		
# 管理补贴 和 互助补贴
def main():
	sql = """
		select uid, usernumber, realname, userrank, usertitle, leftachievement, middleachievement, rightachievement from zx_member where znum = 3
	"""
	members = conn.query(sql)
	if members:
		for member in members:
			usernumber = member['usernumber']
			usertitle = member['usertitle']
			uid = member['uid']
			usernumber = member['usernumber']
			realname = member['realname']
			# 判断是星级的会员
			if usertitle == 1 or usertitle == 2 or usertitle == 3 or usertitle == 4 or usertitle == 5 or usertitle == 6:
				managerbonus(uid, usertitle)

			value = compare(member['leftachievement'], member['middleachievement'], member['rightachievement'])
			if value > 100000 and value < 300000:
				title = 1
				jianglijifen = 3000
				if usertitle == 0:
					status = update_member(title, jianglijifen, usernumber)
					if status:
						insert_bonus_detail_jianglijifen(uid, usernumber, realname, 2, jianglijifen, yes_second)
						insert_money_change_jianglijifen(5, uid, usernumber, realname, 4, 1, jianglijifen, now_second)
			elif value >= 300000 and value < 800000: 
				title = 2				
				jianglijifen = 9000
				if usertitle == 0 or usertitle == 1:
					status = update_member(title, jianglijifen, usernumber)
					if status:
						insert_bonus_detail_jianglijifen(uid, usernumber, realname, 2, jianglijifen, yes_second)
						insert_money_change_jianglijifen(5, uid, usernumber, realname, 4, 1, jianglijifen, now_second)
			elif value >= 800000 and value < 2000000:
				title = 3
				jianglijifen = 24000
				if usertitle == 0 or usertitle == 1 or usertitle == 2:
					status = update_member(title, jianglijifen, usernumber)
					if status:
						insert_bonus_detail_jianglijifen(uid, usernumber, realname, 2, jianglijifen, yes_second)
						insert_money_change_jianglijifen(5, uid, usernumber, realname, 4, 1, jianglijifen, now_second)
			elif value >= 2000000 and value < 5000000:
				title = 4
				jianglijifen = 60000     
				if usertitle == 0 or usertitle == 1 or usertitle == 2 or usertitle == 3:
					status = update_member(title, jianglijifen, usernumber)
					if status:
						insert_bonus_detail_jianglijifen(uid, usernumber, realname, 2, jianglijifen, yes_second)
						insert_money_change_jianglijifen(5, uid, usernumber, realname, 4, 1, jianglijifen, now_second)
			elif value >= 5000000 and value < 8000000:
				title = 5
				jianglijifen = 150000
				if usertitle == 0 or usertitle == 1 or usertitle == 2 or usertitle == 3 or usertitle == 4:
					status = update_member(title, jianglijifen, usernumber)
					if status:
						insert_bonus_detail_jianglijifen(uid, usernumber, realname, 2, jianglijifen, yes_second)
						insert_money_change_jianglijifen(5, uid, usernumber, realname, 4, 1, jianglijifen, now_second)
			elif value >= 8000000:
				title = 6
				jianglijifen = 240000
				if usertitle == 0 or usertitle == 1 or usertitle == 2 or usertitle == 3 or usertitle == 4 or usertitle == 5:
					status = update_member(title, jianglijifen, usernumber)
					if status:
						insert_bonus_detail_jianglijifen(uid, usernumber, realname, 2, jianglijifen, yes_second)
						insert_money_change_jianglijifen(5, uid, usernumber, realname, 4, 1, jianglijifen, now_second)

	conn.close()

def member_active_time(uid):
	flag = False
	sql = """
		select active_time from zx_member where uid = %s and from_unixtime(active_time, '%%Y-%%m-%%d') = '%s'
	""" % (uid, yes_time)
	results = conn.query(sql)
	if results:
		return False
	else:
		flag = True

	return flag

# 通过父uid获取子推荐, 按昨天的激活时间子推荐
def gettuijiannumber_child(uid):
	childs = []
	sql = """
		select recommenduserpath from zx_member where find_in_set(%s, recommenduserpath) and uid != %s
	"""  % (uid, uid)
	results = conn.query(sql)
	if results:
		for result in results:
			_childs = result['recommenduserpath'].split(',')[::-1]
			for _child in _childs:
				if int(_child) == int(uid):
					break
				
				status = member_active_time(_child)
				if status:
					if _child not in childs:
						childs.append(_child)

	return childs

# 通过子uid获取父推荐
def gettuijiannumber_parent(uid):
	parents = []
	sql = """
		select recommenduserpath from zx_member where uid = %s
	"""  % (uid)
	results = conn.query(sql)
	if results:
		parents = results[0]['recommenduserpath'].split(',')

	return parents[-2::-1]

def getuservalue(parents):
	members = []
	for uid in parents:
		val = []
		sql = """
			select m.uid, m.usertitle, r.value from zx_member as m left join zx_bonus_rule as r on m.usertitle = r.key 
			where m.uid = %s and category = 'managercash'
		""" % (uid)
		result = conn.query(sql)
		if result and result[0]['usertitle'] != 0:
			val.append(result[0]['uid'])
			val.append(result[0]['usertitle'])
			val.append(result[0]['value'])
			members.append(val)

	return members

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
		where r.category = 'userrank'
	"""
	results = conn.query(sql)
	if results:
		value = results[0]['value']
	return value

# 极差算法
def jicha(uid, usertitle, value, maxmanagercash, memberlevels):
	maxmanagercash = maxmanagercash
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
				if member_uid == int(uid):
					managercash = value * maxmanagercash / 100
					result = getmemberinfo(member_uid)
					if result:
						insert_bonus_detail_2(member_uid, result[0]['usernumber'], result['realname'], managercash, now_second)
					break
				else:
					if member_title > int(usertitle):
						managercash = value * maxmanagercash / 100
						result = getmemberinfo(member_uid)
						if result:
							insert_bonus_detail_2(member_uid, result[0]['usernumber'], result['realname'], managercash, now_second)	
						break
					elif member_title == int(usertitle): 
						managercash = value * member_value / 100
						result = getmemberinfo(member_uid)
						if result:
							insert_bonus_detail_2(member_uid, result[0]['usernumber'], result['realname'], managercash, now_second)	
						break
					elif member_title < int(usertitle):
						_member_value = member_value - i
						managercash = value * _member_value / 100 
						maxmanagercash -= _member_value
						result = getmemberinfo(member_uid)
						if result:
							insert_bonus_detail_2(member_uid, result[0]['usernumber'], result['realname'], managercash, now_second)	
						
		elif index == 0:
			member_uid = int(memberlevels[index][0])
			member_title = int(memberlevels[index][1])
			member_value = int(memberlevels[index][2])
			if member_uid == int(uid):
				managercash = value * maxmanagercash / 100
				result = getmemberinfo(member_uid)	
				if result:
					insert_bonus_detail_2(member_uid, result[0]['usernumber'], result['realname'], managercash, now_second)	
			else:
				if member_title > int(usertitle):
					managercash = value * maxmanagercash / 100
					result = getmemberinfo(member_uid)
					if result:
						insert_bonus_detail_2(member_uid, result[0]['usernumber'], result['realname'], managercash, now_second)	
					break
				elif member_title == int(usertitle):
					managercash = value * member_value / 100
					result = getmemberinfo(member_uid)
					if result:
						insert_bonus_detail_2(member_uid, result[0]['usernumber'], result['realname'], managercash, now_second)	
					break
				elif member_title < int(usertitle):
					managercash = value * member_value / 100
					maxmanagercash -= member_value
					result = getmemberinfo(member_uid)
					if result:
						insert_bonus_detail_2(member_uid, result[0]['usernumber'], result['realname'], managercash, now_second)	
	return True

#根据激活时间 计算管理奖， 管理奖必须有推荐关系，滑落的点不计算管理奖， 管理奖是极差制度
def managerbonus(uid, usertitle):
	# 先获取会员管理比例的最大值
	maxmanagercash = getmaxmanagercash(usertitle)

	# 获取会员 的 左 中 右 消费商
	sql = """
		select uid from zx_member where parentid = %s
	""" % (uid)
	members = conn.query(sql)
	for member in members:
		member_uid = member['uid']
		# 获取消费商推荐的人
		childs = gettuijiannumber_child(member_uid)
		for child in childs:
			# 获取推荐人的级别金额
			value = getmembervalue(child)
			# 获取推荐的人的父级 
			parents = gettuijiannumber_parent(child)
			for k, v in enumerate(parents):
				if int(v) == int(uid):
					# 极差
					# 赛选有星级的会员 uid, usertitle
					memberlevels = getuservalue(parents[0:k+1])
					jicha(uid, usertitle, value, maxmanagercash, memberlevels)

if __name__ == '__main__':
	#lists = [[170, 2L, 5.0], [171L, 1L, 10.0], [173L, 1L, 15.0], [174L, 2L, 20.0]]
	#jicha(174, 4, 1980, 20, lists)
	main()