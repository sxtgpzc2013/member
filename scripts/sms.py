#encoding:utf-8
import sys

default_encoding = 'utf-8'
if sys.getdefaultencoding() != default_encoding:
    reload(sys)
    sys.setdefaultencoding(default_encoding)

import httplib
import urllib
import json

sms_host = "sms.yunpian.com"
port = 443
version = "v2"

# 模板短信接口的URI
sms_tpl_send_uri = "/" + version + "/sms/tpl_single_send.json"

# 模板接口发短信
def tpl_send_sms(apikey, tpl_id, tpl_value, mobile):
	params = urllib.urlencode({'apikey': apikey, 'tpl_id':tpl_id, 'tpl_value': urllib.urlencode(tpl_value), 'mobile':mobile})
	headers = {"Content-type": "application/x-www-form-urlencoded", "Accept": "text/plain"}
	conn = httplib.HTTPSConnection(sms_host, port=port, timeout=30)
	conn.request("POST", sms_tpl_send_uri, params, headers)
	response = conn.getresponse()
	response_str = response.read()
	conn.close()
	return response_str

def main():
	if len(sys.argv) >= 3:
		tpl_value = {}
		usernumber = int(sys.argv[1])
		mobile = int(sys.argv[2])
		apikey = "9d6886eeb1674c01ff65e3a0ead12258"
		tpl_id = 1611878
		tpl_value['#usernumber#'] = usernumber
		tpl_value['#password#'] = mobile
		print tpl_send_sms(apikey, tpl_id, tpl_value, mobile)

if __name__ == '__main__':
	main()