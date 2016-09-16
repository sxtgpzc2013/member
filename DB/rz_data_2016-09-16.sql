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
  `bonus6` decimal(10,2) DEFAULT '0.00' COMMENT '消费补贴',
  `bonus7` decimal(10,2) DEFAULT '0.00' COMMENT '服务补贴',
  `bonus8` decimal(10,2) DEFAULT '0.00' COMMENT '消费提成',
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
  `title` varchar(255) DEFAULT '' COMMENT '规则名称',
  `money` double(10,2) DEFAULT '0.00' COMMENT '报单金额(单级别)',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='奖金规则表';



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
  `parentid` int(10) NOT NULL DEFAULT '0' COMMENT '接点人ID',
  `parentnumber` char(16) NOT NULL DEFAULT '0' COMMENT '接点人帐号',
  `reg_uid` mediumint(8) DEFAULT '0' COMMENT '注册人id',
  `active_uid` mediumint(8) DEFAULT '0' COMMENT '激活人id',
  `billcenterid` mediumint(8) DEFAULT '1' COMMENT '报单中心ID',
  `billcenternumber` mediumint(8) DEFAULT '1' COMMENT '报单中心账号',
  `isbill` tinyint(3) DEFAULT '0' COMMENT '是否是报单中心:0不是，1报单中心',
  `baodanbi` double(10,2) DEFAULT '0.00' COMMENT '报单币',
  `jiangjinbi` double(10,2) DEFAULT '0.00' COMMENT '奖金币',
  `rongzidun` double(10,2) DEFAULT '0.00' COMMENT '戎子盾',
  `jihuobi` double(10,2) DEFAULT '0.00' COMMENT '激活币',
  `jianglijifen` double(10,2) DEFAULT '0.00' COMMENT '奖励积分',
  `isfull` tinyint(2) DEFAULT '0' COMMENT '分红是否封顶',
  `status` int(8) DEFAULT '0' COMMENT '用户状态：-2 删除 ，-1 死了，0 未激活 1 已经激活 ',
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
  `zone` int(4) DEFAULT '1' COMMENT '左区（1），中区(2), 右区（3）',
  `znum` mediumint(10) DEFAULT '0' COMMENT '接点人数',
  `left_zone` tinyint(1) DEFAULT '0' COMMENT '左区是否被占',
  `middle_zone` tinyint(1) DEFAULT '0' COMMENT '中区是否被占',
  `right_zone` tinyint(1) DEFAULT '0' COMMENT '右区是否被占',
  `proxy_state` tinyint(2) DEFAULT '0' COMMENT '分红状态， 0 不分红，1 分红',
  `achievement` double(16,2) DEFAULT '0.00' COMMENT '总业绩',
  `num` int(10) DEFAULT '0' COMMENT '伞下人数',
  `red_wine_number` int(8) DEFAULT NULL COMMENT '数字红酒',
  `last_time` int(10) DEFAULT NULL COMMENT '最后登录时间',
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
  `hasmoney` double(10,2) DEFAULT '0.00' COMMENT '账户余额',
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
  `moneytype` int(1) unsigned DEFAULT '0' COMMENT '转账类型 0 报单币',
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




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
