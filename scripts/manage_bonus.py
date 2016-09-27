#encoding:utf-8

import mysql
import datetime
import sys

default_encoding = 'utf-8'
if sys.getdefaultencoding() != default_encoding:
    reload(sys)
    sys.setdefaultencoding(default_encoding)

conn = mysql.db()

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
		insert into zx_bonus_detail (touserid, tousernumber, torealname, moneytype, jiangjinbi, rongzidun, lovemoney, platmoney, taxmoney, total, real_total, createdate) 
		values (%s, %s, '%s', %s, %s, %s, %s, %s, %s, %s, %s, %s)
	""" % (uid, usernumber, realname, moneytype, jianglijifen, yes_second)

	return conn.dml(sql, 'insert')

def insert_money_change_jianglijifen(moneytype, uid, usernumber, realname, changetype, recordtype, jianglijifen, createtime):
	sql = """
		insert into zx_money_change (moneytype, status, targetuserid, targetusernumber, targetrealname, userid, usernumber, realname, changetype, recordtype, money, createtime)
		values (%s, %s, %s, %s, '%s', %s, %s, '%s', %s, %s, %s, %s)
	""" % (moneytype, 1, uid, usernumber, realname, 1, 1, '戎子', changetype, recordtype, jianglijifen, createtime)

	return conn.dml(sql, 'insert')

# 管理补贴
def managerbonus():
	now = datetime.datetime.now()
	now_second = datetime.datetime.now().strftime('%s')
	yes_second = (now + datetime.timedelta(days=-1)).strftime('%s')
	
	member_sql = """
		select uid, usernumber, realname, userrank, usertitle, leftachievement, middleachievement, rightachievement from zx_member where znum = 3
	"""

	members = conn.query(member_sql)

	#根据激活时间 计算管理奖， 管理奖必须有推荐关系，滑落的点不计算管理奖， 管理奖是极差制度

	#互助奖，享受管理补贴的代数的奖励

	if members:
		for member in members:
			usernumber = member['usernumber']
			userrank = member['userrank']
			uid = member['uid']
			usernumber = member['usernumber']
			realname = member['realname']
			value = compare(member['leftachievement'], member['middleachievement'], member['rightachievement'])
			
			if value > 100000 and value < 300000:
				usertitle = 1
				jianglijifen = 3000
				if userrank == 0:
					status = update_member(usertitle, jianglijifen, usernumber)
					if status:
						insert_bonus_detail_jianglijifen(uid, usernumber, realname, 2, jianglijifen, yes_second)
						insert_money_change_jianglijifen(5, uid, usernumber, realname, 4, 1, jianglijifen, now_second)
			elif value > 300000 and value < 800000: 
				usertitle = 2				
				jianglijifen = 9000
				if userrank == 0 or userrank == 1:
					status = update_member(usertitle, jianglijifen, usernumber)
					if status:
						insert_bonus_detail_jianglijifen(uid, usernumber, realname, 2, jianglijifen, yes_second)
						insert_money_change_jianglijifen(5, uid, usernumber, realname, 4, 1, jianglijifen, now_second)
			elif value > 800000 and value < 2000000:
				usertitle = 3
				jianglijifen = 24000
				if userrank == 0 or userrank == 1 or userrank == 2:
					status = update_member(usertitle, jianglijifen, usernumber)
					if status:
						insert_bonus_detail_jianglijifen(uid, usernumber, realname, 2, jianglijifen, yes_second)
						insert_money_change_jianglijifen(5, uid, usernumber, realname, 4, 1, jianglijifen, now_second)
			elif value > 2000000 and value < 5000000:
				usertitle = 4
				jianglijifen = 60000
				if userrank == 0 or userrank == 1 or userrank == 2 or userrank == 3:
					status = update_member(usertitle, jianglijifen, usernumber)
					if status:
						insert_bonus_detail_jianglijifen(uid, usernumber, realname, 2, jianglijifen, yes_second)
						insert_money_change_jianglijifen(5, uid, usernumber, realname, 4, 1, jianglijifen, now_second)
			elif value > 5000000 and value < 8000000:
				usertitle = 5
				jianglijifen = 150000
				if userrank == 0 or userrank == 1 or userrank == 2 or userrank == 3 or userrank == 4:
					status = update_member(usertitle, jianglijifen, usernumber)
					if status:
						insert_bonus_detail_jianglijifen(uid, usernumber, realname, 2, jianglijifen, yes_second)
						insert_money_change_jianglijifen(5, uid, usernumber, realname, 4, 1, jianglijifen, now_second)
			elif value > 8000000:
				usertitle = 6
				jianglijifen = 240000
				if userrank == 0 or userrank == 1 or userrank == 2 or userrank == 3 or userrank == 4 or userrank == 5:
					status = update_member(usertitle, jianglijifen, usernumber)
					if status:
						insert_bonus_detail_jianglijifen(uid, usernumber, realname, 2, jianglijifen, yes_second)
						insert_money_change_jianglijifen(5, uid, usernumber, realname, 4, 1, jianglijifen, now_second)
	conn.close()
	
def leaderbonus():
	pass

if __name__ == '__main__':
	managerbonus()