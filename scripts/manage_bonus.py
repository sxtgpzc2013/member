#encoding:utf-8

import mysql
from time import time

import sys

default_encoding = 'utf-8'
if sys.getdefaultencoding() != default_encoding:
    reload(sys)
    sys.setdefaultencoding(default_encoding)

conn = mysql.db()

# 管理补贴
def managerbonus():
	# 先把满足考核的会员查出来
	member_sql = """
		select uid, usernumber, realname, userrank, usertitle, leftachievement, middleachievement, rightachievement from zx_member where znum = 3
	"""

	members = conn.query(member_sql)

	if members:
		for member in members:
			leftachievement = member['leftachievement']
			middleachievement = member['middleachievement']
			rightachievement = member['rightachievement']


	# 查看这些会员的 最小2区的业绩 达到了 多少, 判断 现有的 会员头衔

	# 3个区 取最小的2个区， 如果大于 设定的值, update 会员头衔 add 奖励积分， 更具激活时间 计算管理奖， 管理奖必须有推荐关系，滑落的点不计算管理奖， 管理奖是极差制度

	#互助奖，享受管理补贴的代数的奖励

def leaderbonus():
	pass

if __name__ == '__main__':
	managerbonus()