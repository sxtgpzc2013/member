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

# 通过父uid获取子拓展
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

				if _child not in childs:
					childs.append(_child)

	return childs

#更新会员的业绩状态
def update_achievement_status(uid):
	flag = False
	sql = """
		select uid from zx_member where parentid = %s
	""" % (uid)

	result = conn.query(sql)
	
	if result:
		for result in result:
			uid = result['uid']
			update_uid_sql = """
				update zx_member set achievementstatus = 1 where uid = %s
			""" % (uid)
			conn.dml(update_uid_sql, 'update')

			childs = gettuijiannumber_child(uid)
			if childs:
				for child in childs:
					update_child_sql = """
						update zx_member set achievementstatus = 1 where uid = %s
					""" % (child)
					conn.dml(update_child_sql, 'update')

		flag = True

	return flag

def update_member(usertitle, jianglijifen, usernumber):
	sql = """
		update zx_member set usertitle = %s, jianglijifen = jianglijifen + %s where usernumber = %s 
	""" % (usertitle, jianglijifen, usernumber)
	status = conn.dml(sql, 'update')

	return status

# 插入福利积分明细
def insert_bonus_detail_jianglijifen(uid, usernumber, realname, moneytype, jianglijifen):
	sql = """
		insert into zx_bonus_detail (touserid, tousernumber, torealname, moneytype, jianglijifen, total, real_total, createdate) 
		values (%s, %s, '%s', %s, %s, %s, %s, %s)
	""" % (uid, usernumber, realname, moneytype, jianglijifen, jianglijifen, jianglijifen, now_second)

	return conn.dml(sql, 'insert')


# 计算会员头衔
def main():
	sql = """
		select uid, usernumber, realname, userrank, usertitle, leftachievement, middleachievement, rightachievement from zx_member where znum = 3 and usernumber != 1 and userrank != 1
	"""
	members = conn.query(sql)
	if members:
		for member in members:
			usernumber = member['usernumber']
			usertitle = member['usertitle']
			uid = member['uid']
			usernumber = member['usernumber']
			realname = member['realname']

			value = compare(member['leftachievement'], member['middleachievement'], member['rightachievement'])
			if value >= 100000 and value < 300000:
				title = 1
				jianglijifen = 3000
				if usertitle == 0:
					achievement_status = update_achievement_status(uid)
					if achievement_status:
						status = update_member(title, jianglijifen, usernumber)
						if status:
							insert_bonus_detail_jianglijifen(uid, usernumber, realname, 9, jianglijifen)
			elif value >= 300000 and value < 800000: 
				title = 2				
				jianglijifen = 6000
				if usertitle == 0 or usertitle == 1:
					achievement_status = update_achievement_status(uid)
					if achievement_status:
						status = update_member(title, jianglijifen, usernumber)
						if status:
							insert_bonus_detail_jianglijifen(uid, usernumber, realname, 9, jianglijifen)
			elif value >= 800000 and value < 2000000:
				title = 3
				jianglijifen = 15000
				if usertitle == 0 or usertitle == 1 or usertitle == 2:
					achievement_status = update_achievement_status(uid)
					if achievement_status:
						status = update_member(title, jianglijifen, usernumber)
						if status:
							insert_bonus_detail_jianglijifen(uid, usernumber, realname, 9, jianglijifen)
			elif value >= 2000000 and value < 5000000:
				title = 4
				jianglijifen = 36000     
				if usertitle == 0 or usertitle == 1 or usertitle == 2 or usertitle == 3:
					achievement_status = update_achievement_status(uid)
					if achievement_status:
						status = update_member(title, jianglijifen, usernumber)
						if status:
							insert_bonus_detail_jianglijifen(uid, usernumber, realname, 9, jianglijifen)
			elif value >= 5000000 and value < 8000000:
				title = 5
				jianglijifen = 80000
				if usertitle == 0 or usertitle == 1 or usertitle == 2 or usertitle == 3 or usertitle == 4:
					achievement_status = update_achievement_status(uid)
					if achievement_status:
						status = update_member(title, jianglijifen, usernumber)
						if status:
							insert_bonus_detail_jianglijifen(uid, usernumber, realname, 9, jianglijifen)
			elif value >= 8000000:
				title = 6
				jianglijifen = 100000
				if usertitle == 0 or usertitle == 1 or usertitle == 2 or usertitle == 3 or usertitle == 4 or usertitle == 5:
					achievement_status = update_achievement_status(uid)
					if achievement_status:
						status = update_member(title, jianglijifen, usernumber)
						if status:
							insert_bonus_detail_jianglijifen(uid, usernumber, realname, 9, jianglijifen)
	conn.close()
	print "ok"

if __name__ == '__main__':
	main()
