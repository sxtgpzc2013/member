#encoding:utf-8

import mysql

conn = mysql.db()

def rate():
	rate_sql = """
		select category, value from zx_bonus_rule where category in ('rongzidun', 'jiangjinbi', 'lovemoney', 'platmoney', 'taxmoney')
	"""
	rates = conn.query(rate_sql)
	if rates:
		return rates
	else:
		rates = (
			{'category': 'rongzidun', 'value': 25}, 
			{'category': 'jiangjinbi', 'value': 55}, 
			{'category': 'lovemoney', 'value': 1}, 
			{'category': 'platmoney', 'value': 2}, 
			{'category': 'taxmoney', 'value': 17}
		)
		return rates
	return rates

# 分红
def fenhong():
	# 明细  
	# 财务流水 
	# 比率配比
	rates = rate()
 	fenghong_scale_sql = "select value from zx_bonus_rule where category = 'UserCash'"
 	fenhongs = conn.query(fenghong_scale_sql)
 	if fenhongs:
 		fenghong_scale = fenhongs[0]['value']
 	else:
 		fenghong_scale = 1.1

 	print fenghong_scale
	# 会员
	member_sql = """
					select m.usernumber, m.realname, m.userrank, r.value from zx_member as m left join zx_bonus_rule as r
					on m.userrank = r.key
	 				where m.userrank != 1 and m.status = 1 and m.proxy_state = 1 and r.category = 'userrank'
	 			"""
	members = conn.query(member_sql)

	if members and rates:
		for member in members:
			usernumber = member['usernumber']
			realname = member['realname']
			userrank = member['userrank']
			value = member['value']

			fenhong = fenghong_scale * value

			print fenhong
			for r in rates:
				if r['category'] == 'jiangjinbi':
					jiangjinbi_rate = r['value']
					jiangjinbi = fenhong * jiangjinbi_rate
				elif r['category'] == 'rongzidun':
					rongzidun_rate = r['value']
					rongzidun_rate = fenhong * rongzidun_rate
				elif r['category'] == 'lovemoney':
					lovemoney_rate = r['value']
					lovemoney = fenhong * lovemoney_rate
				elif r['category'] == 'platmoney':
					platmoney_rate = r['value']
					platmoney = fenhong * platmoney_rate
				elif r['category'] == 'taxmoney':
					taxmoney_rate = r['value']
					taxmoney = fenhong * taxmoney_rate

			print jiangjinbi
	else:
		print "null"

if __name__ == '__main__':
	fenhong()