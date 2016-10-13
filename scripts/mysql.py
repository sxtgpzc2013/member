#encoding:utf-8
# mysql 基类

import MySQLdb
import MySQLdb.cursors
import mod_config

configname = "mysql"
dbname = mod_config.getConfig(configname, 'dbname')
dbhost = mod_config.getConfig(configname, 'dbhost')
dbuser = mod_config.getConfig(configname, 'dbuser')
dbpwd = mod_config.getConfig(configname, 'dbpassword')
dbcharset = mod_config.getConfig(configname, 'dbcharset')
dbport = mod_config.getConfig(configname, "dbport")

class db:
    def __init__(self):
        if dbname is None:
            self._dbname = dbname
        else:
            self._dbname = dbname

        if dbhost is None:
            self._dbhost = dbhost
        else:
            self._dbhost = dbhost
            
        self._dbuser = dbuser
        self._dbpassword = dbpwd
        self._dbcharset = dbcharset
        self._dbport = int(dbport)
        self._conn = self.connect()
        
        if(self._conn):
            self._cursor = self._conn.cursor()

    #数据库连接
    def connect(self):
        conn = False
        try:
            conn = MySQLdb.connect(
	            		host=self._dbhost,
	                    user=self._dbuser,
	                    passwd=self._dbpassword,
	                    db=self._dbname,
	                    port=self._dbport,
	                    cursorclass=MySQLdb.cursors.DictCursor,
	                    charset=self._dbcharset,
                    )
        except Exception, data:
            conn = False
            print "connect database failed, %s" % data
        return conn

    #获取查询结果集
    def query(self, sql):
        res = ''
        if(self._conn):
            try:
                self._cursor.execute(sql)
                res = self._cursor.fetchall()
            except Exception, data:
                res = False
                print "query database exception, %s" % data
        return res

    #更新结果
    def dml(self, sql, sql_type):
        flag = False
        if(self._conn):
            try:
                self._cursor.execute(sql)
                insert_id = self._conn.insert_id()
                self._conn.commit()
                if sql_type == 'insert':
                    return insert_id
                flag = True
            except Exception, data:
                flag = False
               	print "dml database exception, %s" % data

        return flag

    #关闭数据库连接
    def close(self):
        if(self._conn):
            try:
                if(type(self._cursor)=='object'):
                    self._cursor.close()
                if(type(self._conn)=='object'):
                    self._conn.close()
            except Exception, data:
                print "close database exception, %s,%s,%s" % (data, type(self._cursor), type(self._conn))                