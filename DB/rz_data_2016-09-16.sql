# ************************************************************
# Sequel Pro SQL dump
# Version 4135
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: localhost (MySQL 5.6.21)
# Database: rz_data
# Generation Time: 2016-09-16 08:31:58 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table zx_order_items
# ------------------------------------------------------------

DROP TABLE IF EXISTS `zx_order_items`;

CREATE TABLE `zx_order_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `pro_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品ID',
  `order_id` int(11) NOT NULL DEFAULT '0' COMMENT '订单ID',
  `rprice` int(11) NOT NULL DEFAULT '0' COMMENT '戎子盾价格',
  `jprice` int(11) NOT NULL DEFAULT '0' COMMENT '奖金币价格',
  `count` int(11) NOT NULL DEFAULT '0' COMMENT '数量',
  `total_price` float(9,2) NOT NULL DEFAULT '0.00' COMMENT '子订单总价',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updated_at` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `is_del` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-正常 1-删除',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='订单子项订单条目描述表';



# Dump of table zx_orders
# ------------------------------------------------------------

DROP TABLE IF EXISTS `zx_orders`;

CREATE TABLE `zx_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `order_code` varchar(20) NOT NULL DEFAULT '0' COMMENT '生成订单编号',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `sendName` varchar(40) NOT NULL DEFAULT '' COMMENT '收货姓名',
  `sendAddress` varchar(50) NOT NULL DEFAULT '' COMMENT '收货地址',
  `memberCode` varchar(20) NOT NULL DEFAULT '' COMMENT '会员编号',
  `sendTel` varchar(20) NOT NULL DEFAULT '' COMMENT '收货人电话',
  `sendCommpany` varchar(20) NOT NULL DEFAULT '' COMMENT '物流公司',
  `total_price` float(9,2) NOT NULL DEFAULT '0.00' COMMENT '总价',
  `notice` varchar(2000) NOT NULL DEFAULT '' COMMENT '备注',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态 0-未付款 1-已付款 2-已发货 3-已完成 4-换货处理中 5-退货处理中 6-等待用户邮寄',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updated_at` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `is_del` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-正常 1-删除',
  `pay_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '支付方式 0-在线 1-现金',
  `logistics_number` varchar(255) NOT NULL DEFAULT '' COMMENT '物流号',
  `logistics_tel` varchar(255) NOT NULL DEFAULT '' COMMENT '物流电话',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='订单表';



# Dump of table zx_products
# ------------------------------------------------------------

DROP TABLE IF EXISTS `zx_products`;

CREATE TABLE `zx_products` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '名称',
  `logo` varchar(100) NOT NULL DEFAULT '' COMMENT '图片',
  `content` varchar(2000) NOT NULL DEFAULT '' COMMENT '介绍',
  `rprice` int(10) DEFAULT NULL COMMENT '戎子盾价格',
  `jprice` float(9,2) DEFAULT NULL COMMENT '奖金币价格',
  `products_code` varchar(20) NOT NULL DEFAULT '' COMMENT '产品编号',
  `surplus` int(4) NOT NULL DEFAULT '0' COMMENT '产品剩余数量',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态 0 启用 1 禁用',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updated_at` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `is_del` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-正常 1-删除',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商品信息表';



# Dump of table zx_user_address
# ------------------------------------------------------------

DROP TABLE IF EXISTS `zx_user_address`;

CREATE TABLE `zx_user_address` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户id',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '姓名',
  `mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '手机号',
  `address` varchar(255) NOT NULL DEFAULT '' COMMENT '详细地址',
  `area` varchar(255) NOT NULL DEFAULT '' COMMENT '所属地区',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updated_at` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `is_default` int(4) NOT NULL DEFAULT '0' COMMENT '是否默认 0-不 1-是',
  `is_del` int(4) NOT NULL DEFAULT '0' COMMENT '是否删除 0-正常 1-删除',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户地址管理表';



# Dump of table zx_admins
# ------------------------------------------------------------

DROP TABLE IF EXISTS `zx_admins`;

CREATE TABLE `zx_admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `password` varchar(50) NOT NULL DEFAULT '' COMMENT '密码',
  `name` varchar(20) NOT NULL DEFAULT '' COMMENT '姓名',
  `mobile` varchar(11) NOT NULL DEFAULT '' COMMENT '手机号',
  `type_str` varchar(15) NOT NULL DEFAULT '' COMMENT '类型标志 admin- super-',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updated_at` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `is_del` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-正常 1-删除',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='管理员表';



# Dump of table zx_bonus_count
# ------------------------------------------------------------

DROP TABLE IF EXISTS `zx_bonus_count`;

CREATE TABLE `zx_bonus_count` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `touserid` int(10) DEFAULT NULL,
  `tousernumber` varchar(16) DEFAULT NULL,
  `bonus1` decimal(10,2) DEFAULT '0.00' COMMENT '分红',
  `bonus2` decimal(10,2) DEFAULT '0.00' COMMENT '管理补贴',
  `bonus3` decimal(10,2) DEFAULT '0.00' COMMENT '互助补贴',
  `bonus4` decimal(10,2) DEFAULT '0.00' COMMENT '拓展补贴',
  `bonus5` decimal(10,2) DEFAULT '0.00' COMMENT '市场补贴',
  `bonus6` decimal(10,2) DEFAULT '0.00' COMMENT '销售补贴',
  `bonus7` decimal(10,2) DEFAULT '0.00' COMMENT '服务补贴',
  `bonus8` decimal(10,2) DEFAULT '0.00' COMMENT '二次消费补贴',
  `bonus9` decimal(10,2) DEFAULT '0.00' COMMENT '福利积分',
  `total` decimal(10,2) DEFAULT '0.00' COMMENT '总奖金',
  `real_total` decimal(10,2) DEFAULT '0.00' COMMENT '实发奖金',
  `count_date` int(8) DEFAULT NULL COMMENT '统计日期',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='奖金日统计表';



# Dump of table zx_bonus_rule
# ------------------------------------------------------------

DROP TABLE IF EXISTS `zx_bonus_rule`;
CREATE TABLE `zx_bonus_rule` (
  `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `category` varchar(255) DEFAULT '' COMMENT '类别',
  `key` int(4) DEFAULT '0' COMMENT '级别',
  `value` double(10,2) DEFAULT '0' COMMENT '数值',
  `createtime` varchar(255) DEFAULT '' COMMENT '时间',
  `remark` varchar(255) DEFAULT '' COMMENT '注释',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='奖金规则表';



# Dump of table zx_finance
# ------------------------------------------------------------

DROP TABLE IF EXISTS `zx_finance`;

CREATE TABLE `zx_finance` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '财务id',
  `income` double(12,2) DEFAULT '0.00' COMMENT '公司报单总收入',
  `expend` double(12,2) DEFAULT '0.00' COMMENT '奖金支出',
  `createtime` int(11) DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='公司财务';



# Dump of table zx_member
# ------------------------------------------------------------

DROP TABLE IF EXISTS `zx_member`;

CREATE TABLE `zx_member` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `usernumber` varchar(16) NOT NULL DEFAULT '' COMMENT '用户编号',
  `realname` varchar(255) DEFAULT NULL COMMENT '会员真实姓名',
  `userrank` int(255) DEFAULT '0' COMMENT '用户级别',
  `usertitle` int(255) DEFAULT '0' COMMENT '用户头衔',
  `tuijianid` int(10) NOT NULL DEFAULT '0' COMMENT '推荐人ID',
  `tuijiannumber` char(16) NOT NULL DEFAULT '0' COMMENT '推荐人帐号',
  `parentid` int(10) NOT NULL DEFAULT '0' COMMENT '位置编号ID',
  `parentnumber` char(16) NOT NULL DEFAULT '0' COMMENT '位置编号帐号',
  `reg_uid` mediumint(8) DEFAULT '0' COMMENT '注册人id',
  `active_uid` mediumint(8) DEFAULT '0' COMMENT '激活人id',
  `billcenterid` mediumint(8) DEFAULT '1' COMMENT '代理商编号ID',
  `billcenternumber` mediumint(8) DEFAULT '1' COMMENT '代理商编号账号',
  `isbill` tinyint(3) DEFAULT '0' COMMENT '是否是代理商编号:0不是，1代理商编号',
  `baodanbi` double(10,2) DEFAULT '0.00' COMMENT '注册币',
  `jiangjinbi` double(10,2) DEFAULT '0.00' COMMENT '奖金币',
  `rongzidun` double(10,2) DEFAULT '0.00' COMMENT '戎子盾',
  `jihuobi` double(10,2) DEFAULT '0.00' COMMENT '激活币',
  `jianglijifen` double(10,2) DEFAULT '0.00' COMMENT '福利积分',
  `isfull` tinyint(2) DEFAULT '0' COMMENT '分红是否封顶',
  `status` int(8) DEFAULT '0' COMMENT '用户状态：-2 删除 ，-1 死了，0 未激活 1 已经激活 -3 账号冻结 -4 账号禁用',
  `bankname` varchar(1000) DEFAULT '' COMMENT '银行名称',
  `bankholder` varchar(50) DEFAULT '' COMMENT '开户人姓名',
  `banknumber` varchar(20) DEFAULT NULL COMMENT '银行卡号',
  `IDcard` char(18) DEFAULT '' COMMENT '用户身份证号',
  `bank_adress` varchar(255) DEFAULT NULL COMMENT '开户行地址',
  `ID_address_face` varchar(255) DEFAULT NULL COMMENT '身份证正面地址',
  `ID_address_back` varchar(255) DEFAULT NULL COMMENT '身份证反面地址',
  `area` text COMMENT '会员所在区域',
  `address` varchar(255) DEFAULT '' COMMENT '地址',
  `mobile` varchar(11) DEFAULT NULL COMMENT '手机号',
  `email` varchar(50) DEFAULT NULL COMMENT '邮箱',
  `reg_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '注册时间',
  `active_time` int(10) DEFAULT NULL COMMENT '激活时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '会员更新时间',
  `psd1` varchar(32) DEFAULT NULL COMMENT '一级密码',
  `psd2` varchar(32) DEFAULT NULL COMMENT '二级密码',
  `recom_num` int(8) DEFAULT '0' COMMENT '推荐人数',
  `zone` int(4) DEFAULT '1' COMMENT 'A部（1），B部(2), C部（3）',
  `znum` mediumint(10) DEFAULT '0' COMMENT '位置编号',
  `left_zone` tinyint(1) DEFAULT '0' COMMENT 'A部是否被占',
  `middle_zone` tinyint(1) DEFAULT '0' COMMENT 'B部是否被占',
  `right_zone` tinyint(1) DEFAULT '0' COMMENT 'C部是否被占',
  `proxy_state` tinyint(2) DEFAULT '0' COMMENT '分红状态， 0 不分红，1 分红',
  `achievement` double(16,2) DEFAULT '0.00' COMMENT '总业绩',
  `achievementstatus` tinyint(1) DEFAULT '0' COMMENT '业绩分红状态 0： 不计算头衔升级的销费商  1： 计算头衔升级推荐销费商',
  `num` int(10) DEFAULT '0' COMMENT '伞下人数',
  `red_wine_number` int(8) DEFAULT NULL COMMENT '数字红酒',
  `last_time` int(10) DEFAULT NULL COMMENT '最后登录时间',
  `max_bonus` double(16,2) DEFAULT '0.00' COMMENT '最大奖金',
  PRIMARY KEY (`uid`),
  UNIQUE KEY `usernumber` (`usernumber`) USING BTREE,
  KEY `status` (`status`) USING BTREE,
  KEY `tuijianid` (`tuijianid`) USING BTREE,
  KEY `recom_num` (`recom_num`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员信息表';



# Dump of table zx_money_change
# ------------------------------------------------------------

DROP TABLE IF EXISTS `zx_money_change`;

CREATE TABLE `zx_money_change` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `moneytype` tinyint(4) DEFAULT NULL COMMENT '币种',
  `status` int(1) unsigned NOT NULL DEFAULT '0' COMMENT '奖金状态 0 失败 1 成功',
  `targetuserid` int(10) NOT NULL DEFAULT '0' COMMENT '目标账户',
  `targetusernumber` char(16) NOT NULL DEFAULT '' COMMENT '目标账户编号',
  `userid` int(10) NOT NULL DEFAULT '0',
  `usernumber` char(16) NOT NULL DEFAULT '' COMMENT '进账用户编号',
  `changetype` int(6) unsigned NOT NULL DEFAULT '0' COMMENT '变更类型 ：',
  `recordtype` int(2) DEFAULT NULL COMMENT '记录类型：减少（0），增加（1）',
  `money` double(10,2) DEFAULT '0.00' COMMENT '变更金额',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `status` (`status`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='财务流水';



# Dump of table zx_transfer
# ------------------------------------------------------------

DROP TABLE IF EXISTS `zx_transfer`;

CREATE TABLE `zx_transfer` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '转账ID',
  `userid` int(10) DEFAULT '0' COMMENT '用户id',
  `usernumber` char(16) DEFAULT '' COMMENT '用户编号',
  `targetuserid` int(10) DEFAULT '0' COMMENT '目标用户id',
  `targetusernumber` char(16) DEFAULT '' COMMENT '目标用户编号',
  `moneytype` int(1) unsigned DEFAULT '0' COMMENT '转账类型 0 注册币',
  `money` double(10,2) DEFAULT '0.00' COMMENT '转币金额',
  `status` int(4) DEFAULT '0' COMMENT '转账提现状态 0 转账成功 ，1 转账失败',
  `createtime` int(10) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `status` (`status`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='转账信息表';



# Dump of table zx_withdrawal
# ------------------------------------------------------------

DROP TABLE IF EXISTS `zx_withdrawal`;

CREATE TABLE `zx_withdrawal` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '提现ID',
  `moneytype` int(1) unsigned DEFAULT '0' COMMENT '提现类型 0 奖金币',
  `userid` int(10) DEFAULT '0' COMMENT '用户id',
  `usernumber` char(16) DEFAULT '' COMMENT '用户编号',
  `realname` varchar(16) DEFAULT '' COMMENT '用户姓名',
  `bankholder` varchar(16) NOT NULL COMMENT '开户人',
  `bankname` varchar(16) DEFAULT NULL COMMENT '开户银行',
  `banknumber` varchar(20) DEFAULT NULL COMMENT '银行卡号',
  `mobile` varchar(12) DEFAULT NULL COMMENT '手机号',
  `money` double(10,2) DEFAULT '0.00' COMMENT '提现金额',
  `fee` double(10,2) DEFAULT '0.00' COMMENT '提现手续费',
  `createtime` int(10) unsigned DEFAULT '0' COMMENT '提现日期',
  `status` int(4) DEFAULT '0' COMMENT '奖金提现状态 0 提现成功 ，1 申请提现 ， 2 提现失败',
  `handtime` int(10) DEFAULT NULL COMMENT '后台操作更新时间',
  PRIMARY KEY (`id`),
  KEY `status` (`status`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='提现申请表';

# 2016-09-17 新增字段
# --------------------------------------------------------------
ALTER TABLE zx_member ADD ID_address VARCHAR(200) NOT NULL DEFAULT "" COMMENT "身份证所在地址";
ALTER TABLE zx_withdrawal ADD bank_address VARCHAR(255) DEFAULT NULL COMMENT '开户行地址';
ALTER TABLE zx_withdrawal ADD realname VARCHAR(255) DEFAULT NULL COMMENT '提现账户姓名';
ALTER TABLE zx_withdrawal ADD arrival_amount DOUBLE(10,2) DEFAULT '0.00' COMMENT '到账金额';

ALTER TABLE zx_transfer ADD username VARCHAR(200) NOT NULL DEFAULT "" COMMENT "转出账户姓名";
ALTER TABLE zx_transfer ADD targetusername VARCHAR(200) NOT NULL DEFAULT "" COMMENT "转出账户姓名";

# Dump of table zx_news
# ------------------------------------------------------------

DROP TABLE IF EXISTS `zx_news`;

CREATE TABLE `zx_news` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `type` tinyint(2) NOT NULL DEFAULT '0' COMMENT '类型',
  `level` tinyint(2) NOT NULL DEFAULT '0' COMMENT '优先级',
  `content` varchar(2000) NOT NULL DEFAULT '' COMMENT '新闻内容',
  `viewnum` int(10) DEFAULT NULL COMMENT '浏览次数',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态 0 启用 1 禁用',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updated_at` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `is_del` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-正常 1-删除',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='新闻公告表';



-- 奖金明细表
-- -----------------------------
-- Table structure for `zx_bonus_detail`
-- -----------------------------
DROP TABLE IF EXISTS `zx_bonus_detail`;
CREATE TABLE `zx_bonus_detail` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `touserid` int(10) DEFAULT NULL,
  `tousernumber` varchar(16) DEFAULT NULL,
  `torealname` varchar(255) DEFAULT NULL COMMENT '会员真实姓名',
  `moneytype` int(1) unsigned DEFAULT '0' COMMENT '奖金类型 1 分红 2 管理补贴 3 互助补贴 4 拓展补贴 5 市场补贴 6 销售补贴 7 服务补贴 8 二次销售补贴 9 福利积分',
  `baodanbi` double(10,2) DEFAULT '0.00' COMMENT '注册币',
  `jiangjinbi` double(10,2) DEFAULT '0.00' COMMENT '奖金币',
  `rongzidun` double(10,2) DEFAULT '0.00' COMMENT '戎子盾',
  `jihuobi` double(10,2) DEFAULT '0.00' COMMENT '激活币',
  `jianglijifen` double(10,2) DEFAULT '0.00' COMMENT '福利积分',
  `lovemoney` double(10,2) DEFAULT '0.00' COMMENT '爱心基金',
  `platmoney` double(10,2) DEFAULT '0.00' COMMENT '平台管理费',
  `taxmoney` double(10,2) DEFAULT '0.00' COMMENT '税费',
  `total` decimal(10,2) DEFAULT '0.00' COMMENT '总奖金',
  `real_total` decimal(10,2) DEFAULT '0.00' COMMENT '实发奖金',
  `createdate` int(11) DEFAULT NULL COMMENT '明细日期',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=493 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='奖金明细表';
# 2016-09-21 新增字段
# --------------------------------------------------------------
ALTER TABLE zx_money_change CHANGE moneytype moneytype TINYINT(4) COMMENT '币种 1-现金币 2-注册币 3-戎子盾 4-激活币 5-福利积分 6-爱心基金 7 平台管理费 8税费';
ALTER TABLE zx_money_change CHANGE changetype changetype INT(6) NOT NULL DEFAULT 0 COMMENT '0-未知 1-公司充值 2-公司扣币 3-分红 4-管理补贴 5-互助补贴 6-拓展补贴 7-市场补贴 8-销售补贴 9-服务补贴 10-二次消费补贴 11-销费商提现 12-处理提现， 13-消费 14-币种转换';


ALTER TABLE zx_money_change CHANGE changetype changetype INT(6) NOT NULL DEFAULT 0 COMMENT '0-未知 1-公司充值 2-公司扣币 3-分红 4-管理补贴 5-互助补贴 6-拓展补贴 7-市场补贴 8-销售补贴 9-服务补贴 10-二次消费补贴 11-销费商提现 12-处理提现 13-消费 14-系统内部转账 15-币种转换';

ALTER TABLE zx_money_change ADD realname VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'realname';
ALTER TABLE zx_money_change ADD targetrealname VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'targetrealname';


ALTER TABLE zx_member ADD leftachievement DOUBLE(16,2) DEFAULT '0.00' COMMENT '左业绩';

ALTER TABLE zx_member ADD middleachievement DOUBLE(16,2) DEFAULT '0.00' COMMENT '中业绩';

ALTER TABLE zx_member ADD rightachievement DOUBLE(16,2) DEFAULT '0.00' COMMENT '右业绩';

ALTER TABLE zx_member ADD contactuserpath VARCHAR(255) NOT NULL DEFAULT '' COMMENT '用户接点路径';
ALTER TABLE zx_member ADD recommenduserpath VARCHAR(255) NOT NULL DEFAULT '' COMMENT '用户推荐路径';
ALTER TABLE zx_member ADD billuserpath VARCHAR(255) NOT NULL DEFAULT '' COMMENT '用户代理商编号路径';

# 2016-09-22 新增相关
# Dump of table achievement_log
# ------------------------------------------------------------

DROP TABLE IF EXISTS `zx_achievement_log`;

CREATE TABLE `zx_achievement_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `zone` tinyint(2) NOT NULL DEFAULT 0 COMMENT '业绩区间',
  `deduct` DOUBLE(16,2) NOT NULL DEFAULT '0.00' COMMENT '业绩金额',
  `fromuid` int(10) NOT NULL DEFAULT '0' COMMENT '业绩来源用户',
  `uid` int(10) NOT NULL DEFAULT '0' COMMENT '业绩用户',
  `produceuid` int(10) NOT NULL DEFAULT '0' COMMENT '业绩产生用户',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='业绩产生记录表';


ALTER TABLE zx_bonus_count ADD torealname VARCHAR(100) NOT NULL DEFAULT '' COMMENT '真实姓名' AFTER tousernumber;
ALTER TABLE zx_products ADD sell_count INT(11) NOT NULL DEFAULT 0 COMMENT '卖出数量' AFTER surplus;
ALTER TABLE zx_products ADD is_free TINYINT(2) NOT NULL DEFAULT 0 COMMENT '是否为消费套餐红酒产品' AFTER created_at;
ALTER TABLE zx_member CHANGE status status int(8) DEFAULT '0' COMMENT '用户状态：-2 账号冻结 ，-1 删除，0 未激活 1 已经激活';


# 2016-09-25
ALTER TABLE zx_money_change CHANGE changetype changetype INT(6) NOT NULL DEFAULT 0 COMMENT '0-未知 1-公司充值 2-公司扣币 3-分红 4-管理补贴 5-互助补贴 6-拓展补贴 7-市场补贴 8-销售补贴 9-服务补贴 10-服务补贴 11-销费商提现 12-处理提现 13-消费';

# 2016-09-26
ALTER TABLE zx_order_items ADD name VARCHAR(255) NOT NULL DEFAULT '' COMMENT '名称' AFTER pro_id;
ALTER TABLE zx_order_items ADD logo VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'LOGO' AFTER name;
ALTER TABLE zx_order_items ADD content TEXT NOT NULL DEFAULT '' COMMENT '详情' AFTER logo;
ALTER TABLE zx_order_items ADD unit_jprice float(9,2) NOT NULL DEFAULT 0 COMMENT '单价-奖金币' AFTER content;
ALTER TABLE zx_order_items ADD unit_rprice float(9,2) NOT NULL DEFAULT 0 COMMENT '单价-戎子盾' AFTER unit_jprice;

# 2016-09-27
ALTER TABLE zx_orders ADD total_jprice FLOAT(9,2) NOT NULL DEFAULT 0 COMMENT '奖金币总额' AFTER total_price;
ALTER TABLE zx_orders ADD total_rprice FLOAT(9,2) NOT NULL DEFAULT 0 COMMENT '戎子盾总额' AFTER total_jprice;

#2016-10-09

#upgrade_log
DROP TABLE IF EXISTS `zx_upgrade_log`;

CREATE TABLE `zx_upgrade_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `uid` int(10) NOT NULL DEFAULT 0 COMMENT '升级用户id',
  `upgrade_uid` int(10) NOT NULL DEFAULT 0 COMMENT '操作升级管理员ID',
  `level` int(10) NOT NULL DEFAULT '0' COMMENT '升级级别',
  `levelago` int(10) NOT NULL DEFAULT '0' COMMENT '升级前级别',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='升级记录表';


ALTER TABLE zx_admins ADD login_number INT(9) NOT NULL DEFAULT 0 COMMENT '登录次数';
ALTER TABLE zx_admins ADD last_login_time INT(11) NOT NULL DEFAULT 0 COMMENT '最后登录时间';
ALTER TABLE zx_admins ADD last_login_ip VARCHAR(50) NOT NULL DEFAULT 0 COMMENT '最后登录IP';
ALTER TABLE zx_admins ADD status TINYINT(9) NOT NULL DEFAULT 0 COMMENT '是否禁用 状态 0 启用 1 禁用';

# Dump of table zx_auth
# ------------------------------------------------------------

DROP TABLE IF EXISTS `zx_auth`;

CREATE TABLE `zx_auth` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(200) NOT NULL DEFAULT '' COMMENT '用户组名称',
  `content` varchar(200) NOT NULL DEFAULT '' COMMENT '描述',
  `auth_action` text NOT NULL COMMENT '授权相关入口',
  `status` tinyint(2) NOT NULL DEFAULT 0 COMMENT '状态',
  `type_str` varchar(15) NOT NULL DEFAULT '' COMMENT '标识',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `is_del` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-正常 1-删除',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='权限管理表';

/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


ALTER TABLE zx_member ADD max_bonus double(16,2) NOT NULL DEFAULT '0.00' COMMENT '最大奖金';


#jenkins http://121.42.26.223:8080/   admin  zxtc999.
-- 121.42.26.223
-- abiding  14:53:37
-- Zhxtch999！@#￥


-- 阿里云账号
-- 账号：zxtc999
-- 密码：zhongxiang999qwer
-- abiding  18:45:55
-- 数据库
-- rm-m5e372r857ot6cl37.mysql.rds.aliyuncs.com    3306
-- member
-- zhqjqzhnn15!
