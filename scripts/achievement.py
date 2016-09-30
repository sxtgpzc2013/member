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


# 管理补贴 和 互助补贴
def main():
	sql = """
		select uid, usernumber, realname, userrank, usertitle, leftachievement, middleachievement, rightachievement from zx_member where znum = 3 and usernumber != 1
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

if __name__ == '__main__':
	main()