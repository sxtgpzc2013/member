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
yes_time = (now + datetime.timedelta(days=-1)).strftime('%Y-%m-%d')

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

def insert_bonus_detail_jianglijifen(uid, usernumber, realname, moneytype, jianglijifen, now_second):
	sql = """
		insert into zx_bonus_detail (touserid, tousernumber, torealname, moneytype, jianglijifen, createdate) 
		values (%s, %s, '%s', %s, %s, %s)
	""" % (uid, usernumber, realname, moneytype, jianglijifen, now_second)

	return conn.dml(sql, 'insert')

def insert_money_change_jianglijifen(moneytype, uid, usernumber, realname, changetype, recordtype, jianglijifen, createtime):
	sql = """
		insert into zx_money_change (moneytype, status, targetuserid, targetusernumber, targetrealname, userid, usernumber, realname, changetype, recordtype, money, createtime)
		values (%s, %s, %s, %s, '%s', %s, %s, '%s', %s, %s, %s, %s)
	""" % (moneytype, 1, uid, usernumber, realname, 1, 1, '戎子', changetype, recordtype, jianglijifen, createtime)

	return conn.dml(sql, 'insert')

# 管理补贴
def member():
	member_sql = """
		select uid, usernumber, realname, userrank, usertitle, leftachievement, middleachievement, rightachievement from zx_member where znum = 3
	"""

	members = conn.query(member_sql)

	if members:
		for member in members:
			usernumber = member['usernumber']
			usertitle = member['usertitle']
			uid = member['uid']
			usernumber = member['usernumber']
			realname = member['realname']
			# 判断是星级的会员
			if usertitle == 1 or usertitle == 2 or usertitle == 3 or usertitle == 4 or usertitle == 5 or usertitle == 6:
				managerbonus(uid)

			value = compare(member['leftachievement'], member['middleachievement'], member['rightachievement'])
			if value > 100000 and value < 300000:
				title = 1
				jianglijifen = 3000
				if usertitle == 0:
					status = update_member(title, jianglijifen, usernumber)
					if status:
						insert_bonus_detail_jianglijifen(uid, usernumber, realname, 2, jianglijifen, now_second)
						insert_money_change_jianglijifen(5, uid, usernumber, realname, 4, 1, jianglijifen, now_second)
			elif value > 300000 and value < 800000: 
				title = 2				
				jianglijifen = 9000
				if usertitle == 0 or usertitle == 1:
					status = update_member(title, jianglijifen, usernumber)
					if status:
						insert_bonus_detail_jianglijifen(uid, usernumber, realname, 2, jianglijifen, now_second)
						insert_money_change_jianglijifen(5, uid, usernumber, realname, 4, 1, jianglijifen, now_second)
			elif value > 800000 and value < 2000000:
				title = 3
				jianglijifen = 24000
				if usertitle == 0 or usertitle == 1 or usertitle == 2:
					status = update_member(title, jianglijifen, usernumber)
					if status:
						insert_bonus_detail_jianglijifen(uid, usernumber, realname, 2, jianglijifen, now_second)
						insert_money_change_jianglijifen(5, uid, usernumber, realname, 4, 1, jianglijifen, now_second)
			elif value > 2000000 and value < 5000000:
				title = 4
				jianglijifen = 60000     
				if usertitle == 0 or usertitle == 1 or usertitle == 2 or usertitle == 3:
					status = update_member(title, jianglijifen, usernumber)
					if status:
						insert_bonus_detail_jianglijifen(uid, usernumber, realname, 2, jianglijifen, now_second)
						insert_money_change_jianglijifen(5, uid, usernumber, realname, 4, 1, jianglijifen, now_second)
			elif value > 5000000 and value < 8000000:
				title = 5
				jianglijifen = 150000
				if usertitle == 0 or usertitle == 1 or usertitle == 2 or usertitle == 3 or usertitle == 4:
					status = update_member(title, jianglijifen, usernumber)
					if status:
						insert_bonus_detail_jianglijifen(uid, usernumber, realname, 2, jianglijifen, now_second)
						insert_money_change_jianglijifen(5, uid, usernumber, realname, 4, 1, jianglijifen, now_second)
			elif value > 8000000:
				title = 6
				jianglijifen = 240000
				if usertitle == 0 or usertitle == 1 or usertitle == 2 or usertitle == 3 or usertitle == 4 or usertitle == 5:
					status = update_member(title, jianglijifen, usernumber)
					if status:
						insert_bonus_detail_jianglijifen(uid, usernumber, realname, 2, jianglijifen, now_second)
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

def getuservalue(uid):
	usertitle = 0
	managercash = 0
	sql = """
		select m.usertitle, r.value from zx_member as m left join zx_bonus_rule as r 
		on m.usertitle = r.key where m.uid = %s and r.category = 'managercash'
	""" % (uid)

	result = conn.query(sql)
	print result
	if result:
		usertitle = result[0]['usertitle']
		managercash = result[0]['value']

	return usertitle, managercash

def getmaxmanagercash(parents, uid):
	managercashs = []
	for _uid in uids:
		if int(_uid) == int(uid):
			usertitle, managercash = getuservalue(uid)
			managercashs.append(managercash)
			break

		usertitle, managercash = getuservalue(uid)
		managercashs.append(managercash)

	return max(managercashs)

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
 

#根据激活时间 计算管理奖， 管理奖必须有推荐关系，滑落的点不计算管理奖， 管理奖是极差制度
def managerbonus(uid):
	# 获取会员 的 左 中 右 消费商
	members = """
		select uid from zx_member where parentid = %s
	""" % (uid)

	break
	for member in members:
		member_uid = member['uid']
		# 获取消费商推荐的人
		childs = gettuijiannumber_child(member_uid)
		for child in childs:
			# 获取推荐的人的父级, 推荐人对应级别的金额
			value = getmembervalue(child)
			# 获取推荐的人的父级 
			parents = gettuijiannumber_parent(child)
			# 先获取会员管理比例的最大值
			maxmanagercash = getmaxmanagercash(parents, uid)

			for parent_uid in parents:
				if int(parent_uid) == uid:
					usertitle, managercash = getuservalue(parent_uid)
					if usertitle == 1:
						cash = value * maxmanagercash / 100
						maxmanagercash -= 5
					elif usertitle == 2:
						cash = value * maxmanagercash / 100
						maxmanagercash -= 10
					elif usertitle == 3:
						cash = value * maxmanagercash / 100
						maxmanagercash -= 15
					elif usertitle == 4:
						cash = value * maxmanagercash / 100
						maxmanagercash -= 20
					elif usertitle == 5:
						cash = value * maxmanagercash / 100
						maxmanagercash -= 25
					elif usertitle == 6:
						cash = value * maxmanagercash / 100
						maxmanagercash -= 30
					break

				usertitle, managercash = getuservalue(parent_uid)
				if usertitle == 1:
					cash = value * maxmanagercash / 100
					maxmanagercash -= 5
				elif usertitle == 2:
					cash = value * maxmanagercash / 100
					maxmanagercash -= 10
				elif usertitle == 3:
					cash = value * maxmanagercash / 100
					maxmanagercash -= 15
				elif usertitle == 4:
					cash = value * maxmanagercash / 100
					maxmanagercash -= 20
				elif usertitle == 5:
					cash = value * maxmanagercash / 100
					maxmanagercash -= 25
				elif usertitle == 6:
					cash = value * maxmanagercash / 100
					maxmanagercash -= 30

#互助奖，享受管理补贴的代数的奖励
def leaderbonus():
	pass

if __name__ == '__main__':
	print getuservalue(3)