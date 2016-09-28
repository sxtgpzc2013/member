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

def compare(x, y, z):
	values = []
	values.append(x)
	values.append(y)
	values.append(z)

	for i, v in enumerate(values):
		if v == max(values):
			del values[i]

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

def insert_bonus_detail_jianglijifen(uid, usernumber, realname, moneytype, jianglijifen, yes_second):
	sql = """
		insert into zx_bonus_detail (touserid, tousernumber, torealname, moneytype, jianglijifen, createdate) 
		values (%s, %s, '%s', %s, %s, %s)
	""" % (uid, usernumber, realname, moneytype, jianglijifen, yes_second)

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
			if usertitle > 0:
				managerbonus(usernumber, usertitle)

			value = compare(member['leftachievement'], member['middleachievement'], member['rightachievement'])
			if value > 100000 and value < 300000:
				title = 1
				jianglijifen = 3000
				if usertitle == 0:
					status = update_member(title, jianglijifen, usernumber)
					if status:
						insert_bonus_detail_jianglijifen(uid, usernumber, realname, 2, jianglijifen, yes_second)
						insert_money_change_jianglijifen(5, uid, usernumber, realname, 4, 1, jianglijifen, now_second)
			elif value > 300000 and value < 800000: 
				title = 2				
				jianglijifen = 9000
				if usertitle == 0 or usertitle == 1:
					status = update_member(title, jianglijifen, usernumber)
					if status:
						insert_bonus_detail_jianglijifen(uid, usernumber, realname, 2, jianglijifen, yes_second)
						insert_money_change_jianglijifen(5, uid, usernumber, realname, 4, 1, jianglijifen, now_second)
			elif value > 800000 and value < 2000000:
				title = 3
				jianglijifen = 24000
				if usertitle == 0 or usertitle == 1 or usertitle == 2:
					status = update_member(title, jianglijifen, usernumber)
					if status:
						insert_bonus_detail_jianglijifen(uid, usernumber, realname, 2, jianglijifen, yes_second)
						insert_money_change_jianglijifen(5, uid, usernumber, realname, 4, 1, jianglijifen, now_second)
			elif value > 2000000 and value < 5000000:
				title = 4
				jianglijifen = 60000     
				if usertitle == 0 or usertitle == 1 or usertitle == 2 or usertitle == 3:
					status = update_member(title, jianglijifen, usernumber)
					if status:
						insert_bonus_detail_jianglijifen(uid, usernumber, realname, 2, jianglijifen, yes_second)
						insert_money_change_jianglijifen(5, uid, usernumber, realname, 4, 1, jianglijifen, now_second)
			elif value > 5000000 and value < 8000000:
				title = 5
				jianglijifen = 150000
				if usertitle == 0 or usertitle == 1 or usertitle == 2 or usertitle == 3 or usertitle == 4:
					status = update_member(title, jianglijifen, usernumber)
					if status:
						insert_bonus_detail_jianglijifen(uid, usernumber, realname, 2, jianglijifen, yes_second)
						insert_money_change_jianglijifen(5, uid, usernumber, realname, 4, 1, jianglijifen, now_second)
			elif value > 8000000:
				title = 6
				jianglijifen = 240000
				if usertitle == 0 or usertitle == 1 or usertitle == 2 or usertitle == 3 or usertitle == 4 or usertitle == 5:
					status = update_member(title, jianglijifen, usernumber)
					if status:
						insert_bonus_detail_jianglijifen(uid, usernumber, realname, 2, jianglijifen, yes_second)
						insert_money_change_jianglijifen(5, uid, usernumber, realname, 4, 1, jianglijifen, now_second)

	conn.close()

# 通过子usernumber获取父接点人
def getparentnumber(usernumber):
	sql = """
		select parentnumber from zx_member where FIND_IN_SET(usernumber, getJiedianList(%s))
	"""  % (usernumber)

	return conn.query(sql)

# 通过父usernumber获取子推荐
def gettuijiannumber_child(usernumber):
	#获取子的级别金额, 需要 usernumber, userrank, value
	sql = """
		select m.usernumber, r.value from zx_member as m 
		left join zx_bonus_rule as r on m.userrank = r.key
		where FIND_IN_SET(m.usernumber, getChildList(%s))
		and m.usernumber <> %s and m.status = 1 and m.proxy_state = 1 and from_unixtime(m.active_time, '%%Y-%%m-%%d') = '%s' and r.category = 'userrank'
	"""  % (usernumber, usernumber, yes_time)

	return conn.query(sql)

# 通过子usernumber获取父推荐
def gettuijiannumber_parent(usernumber):
	sql = """
		select tuijiannumber from zx_member where FIND_IN_SET(usernumber, getJiedianList(%s))
	"""  % (usernumber)

	return conn.query(sql)

#根据激活时间 计算管理奖， 管理奖必须有推荐关系，滑落的点不计算管理奖， 管理奖是极差制度
def managerbonus(usernumber, usertitle):
	# 管理奖比例
	managercash_rule_sql = """
		select `key`, value from zx_bonus_rule where category = 'managercash' and `key` = %s
	""" % (usertitle)
	result = conn.query(managercash_rule_sql)
	if result and len(result) == 1:
		managercash = result[0]['value']

	# 获取usernumber 的 左 中 右 消费商
	parentmembers = """
		select uid, usernumber, usertitle from zx_member where parentnumber = %s
	""" % (usernumber)

	#获取按激活时间的子推荐
	for parentmember in parentnumbers:
		usernumber = parentmember['usernumber']
		value = parentmember['value']
		tuijiannumbers_child = gettuijiannumber_child(usernumber)
		for tuijiannumber_child in tuijiannumbers_child:
			usernumber = tuijiannumber['usernumber']
			tuijiannumbers_parent = gettuijiannumber_parent(usernumber)
			for tuijiannumber_parent in tuijiannumbers_parent:
				pass

#互助奖，享受管理补贴的代数的奖励
def leaderbonus():
	pass

if __name__ == '__main__':
	print getparentnumber(100)