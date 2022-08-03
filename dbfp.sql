-- --------------------------------------------------------
-- 主機:                           localhost
-- 伺服器版本:                        10.6.0-MariaDB - mariadb.org binary distribution
-- 伺服器操作系統:                      Win64
-- HeidiSQL 版本:                  10.2.0.5599
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- 傾印 dbfp 的資料庫結構
DROP DATABASE IF EXISTS `dbfp`;
CREATE DATABASE IF NOT EXISTS `dbfp` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `dbfp`;

-- 傾印  檢視 dbfp.available_product_items 結構
DROP VIEW IF EXISTS `available_product_items`;
-- 創建臨時表格，以解決檢視依存性錯誤
CREATE TABLE `available_product_items` (
	`id` INT(11) NOT NULL COMMENT '商品流水號',
	`name` VARCHAR(50) NOT NULL COMMENT '商品名稱' COLLATE 'latin1_swedish_ci',
	`description` VARCHAR(255) NOT NULL COMMENT '商品說明' COLLATE 'latin1_swedish_ci',
	`price` INT(11) NOT NULL COMMENT '商品價格',
	`avail` DECIMAL(22,0) NULL
) ENGINE=MyISAM;

-- 傾印  表格 dbfp.categories 結構
DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `value` varchar(255) CHARACTER SET ucs2 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- 正在傾印表格  dbfp.categories 的資料：~1 rows (約數)
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
REPLACE INTO `categories` (`id`, `value`) VALUES
	(8, '討論區');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;

-- 傾印  表格 dbfp.forums 結構
DROP TABLE IF EXISTS `forums`;
CREATE TABLE IF NOT EXISTS `forums` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `value` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `category_id` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `FK_forums_categories` (`category_id`),
  CONSTRAINT `FK_forums_categories` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

-- 正在傾印表格  dbfp.forums 的資料：~4 rows (約數)
/*!40000 ALTER TABLE `forums` DISABLE KEYS */;
REPLACE INTO `forums` (`id`, `value`, `icon`, `category_id`) VALUES
	(15, 'C++', 'uploads/document-icon.png', 8),
	(16, 'C#', 'uploads/document-icon.png', 8),
	(17, 'Java', 'uploads/document-icon.png', 8),
	(18, 'IDE', 'uploads/document-icon.png', 8);
/*!40000 ALTER TABLE `forums` ENABLE KEYS */;

-- 傾印  檢視 dbfp.last_six_threads 結構
DROP VIEW IF EXISTS `last_six_threads`;
-- 創建臨時表格，以解決檢視依存性錯誤
CREATE TABLE `last_six_threads` (
	`id` INT(11) NOT NULL COMMENT '文章流水號',
	`title` VARCHAR(100) NOT NULL COMMENT '文章標題' COLLATE 'utf8_general_ci',
	`tags` VARCHAR(255) NOT NULL COMMENT '標籤' COLLATE 'utf8_general_ci',
	`category_id` INT(11) NOT NULL COMMENT '分類id',
	`forum_id` INT(11) NOT NULL COMMENT '發文區id',
	`user_id` INT(11) NOT NULL COMMENT '發文者id',
	`content` MEDIUMTEXT NOT NULL COMMENT '文章內容' COLLATE 'utf8_general_ci',
	`charge` INT(11) NOT NULL COMMENT '收費金額(0=不收費)',
	`date` DATETIME NOT NULL COMMENT '發文時間',
	`locked` VARCHAR(3) NOT NULL COMMENT '鎖定狀態' COLLATE 'utf8_general_ci',
	`edited_by` INT(11) NULL COMMENT '編輯者',
	`edited_on` DATETIME NULL COMMENT '編輯日期'
) ENGINE=MyISAM;

-- 傾印  檢視 dbfp.last_thread 結構
DROP VIEW IF EXISTS `last_thread`;
-- 創建臨時表格，以解決檢視依存性錯誤
CREATE TABLE `last_thread` (
	`id` INT(11) NOT NULL COMMENT '文章流水號',
	`title` VARCHAR(100) NOT NULL COMMENT '文章標題' COLLATE 'utf8_general_ci',
	`tags` VARCHAR(255) NOT NULL COMMENT '標籤' COLLATE 'utf8_general_ci',
	`category_id` INT(11) NOT NULL COMMENT '分類id',
	`forum_id` INT(11) NOT NULL COMMENT '發文區id',
	`user_id` INT(11) NOT NULL COMMENT '發文者id',
	`content` MEDIUMTEXT NOT NULL COMMENT '文章內容' COLLATE 'utf8_general_ci',
	`charge` INT(11) NOT NULL COMMENT '收費金額(0=不收費)',
	`date` DATETIME NOT NULL COMMENT '發文時間',
	`locked` VARCHAR(3) NOT NULL COMMENT '鎖定狀態' COLLATE 'utf8_general_ci',
	`edited_by` INT(11) NULL COMMENT '編輯者',
	`edited_on` DATETIME NULL COMMENT '編輯日期'
) ENGINE=MyISAM;

-- 傾印  檢視 dbfp.last_user 結構
DROP VIEW IF EXISTS `last_user`;
-- 創建臨時表格，以解決檢視依存性錯誤
CREATE TABLE `last_user` (
	`id` INT(11) NOT NULL,
	`usern` VARCHAR(30) NOT NULL COLLATE 'utf8_general_ci',
	`passwd` VARCHAR(30) NOT NULL COLLATE 'utf8_general_ci',
	`email` VARCHAR(50) NOT NULL COLLATE 'utf8_general_ci',
	`display_name` VARCHAR(30) NOT NULL COLLATE 'utf8_general_ci',
	`level` VARCHAR(10) NOT NULL COMMENT '權限層級' COLLATE 'utf8_general_ci',
	`avatar` VARCHAR(255) NULL COMMENT '頭像圖位置' COLLATE 'utf8_general_ci',
	`signature` VARCHAR(255) NULL COMMENT '個人簡介' COLLATE 'utf8_general_ci',
	`reg_date` DATETIME NOT NULL COMMENT '註冊日期',
	`online_time` INT(11) NULL COMMENT '上線時間',
	`points` INT(11) NOT NULL COMMENT ' 個人點數'
) ENGINE=MyISAM;

-- 傾印  表格 dbfp.products 結構
DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '商品流水號',
  `name` varchar(50) CHARACTER SET latin1 NOT NULL COMMENT '商品名稱',
  `description` varchar(255) CHARACTER SET latin1 NOT NULL COMMENT '商品說明',
  `price` int(11) NOT NULL COMMENT '商品價格',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- 正在傾印表格  dbfp.products 的資料：~5 rows (約數)
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
REPLACE INTO `products` (`id`, `name`, `description`, `price`) VALUES
	(2, 'Microsoft Office 2018 Professional Plus', 'Microsoft Office 2018 Professional Plus Licence (1 Year)', 2000),
	(3, 'Microsoft Office 2016 Professional Plus', 'Microsoft Office 2016 Professional Plus Licence (1 Year)', 2000),
	(4, 'Microsoft Office 2019 Professional Plus', 'Microsoft Office 2019 Professional Plus Licence (1 Year)', 2000),
	(6, 'Windows 10 Home', 'Windows 10 Home Key', 10000),
	(7, 'Windows 10 Professional', 'Windows 10 Professional Key', 15000);
/*!40000 ALTER TABLE `products` ENABLE KEYS */;

-- 傾印  表格 dbfp.product_items 結構
DROP TABLE IF EXISTS `product_items`;
CREATE TABLE IF NOT EXISTS `product_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '產品流水號',
  `product_id` int(11) DEFAULT NULL COMMENT '產品資料',
  `key` varchar(255) NOT NULL DEFAULT '' COMMENT '產品序號',
  `PUR_on` datetime DEFAULT NULL COMMENT '購買時間',
  `PUR_by` int(11) DEFAULT NULL COMMENT '購買人',
  PRIMARY KEY (`id`),
  KEY `FK_product_order_users` (`PUR_by`),
  KEY `FK_product_item_products` (`product_id`),
  CONSTRAINT `FK_product_item_products` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  CONSTRAINT `FK_product_order_users` FOREIGN KEY (`PUR_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1 COMMENT='PUR = purchased';

-- 正在傾印表格  dbfp.product_items 的資料：~7 rows (約數)
/*!40000 ALTER TABLE `product_items` DISABLE KEYS */;
REPLACE INTO `product_items` (`id`, `product_id`, `key`, `PUR_on`, `PUR_by`) VALUES
	(1, 4, 'XQNVK-8JYDB-WJ9W3-YJ8YR-WFG99', NULL, NULL),
	(2, 2, 'V7QKV-4XVVR-XYV4D-F7DFM-8R6BM', NULL, NULL),
	(3, 4, 'XQNVK-8JYDB-WJ9W3-YJ8YR-WFG89', NULL, NULL),
	(6, 4, 'XQNVK-8JYDB-WJ9W3-YJ8YR-WFG89', NULL, NULL),
	(7, 4, 'XQNVK-8JYDB-WJ9W3-YJ8YR-WFG89', '2021-06-17 19:56:39', 3),
	(8, 6, '2F77B-TNFGY-69QQF-B8YKP-D69TJ', NULL, NULL),
	(9, 7, 'WNMTR-4C88C-JK8YV-HQ7T2-76DF9', '2021-06-17 23:30:58', 3);
/*!40000 ALTER TABLE `product_items` ENABLE KEYS */;

-- 傾印  檢視 dbfp.product_item_full_data 結構
DROP VIEW IF EXISTS `product_item_full_data`;
-- 創建臨時表格，以解決檢視依存性錯誤
CREATE TABLE `product_item_full_data` (
	`id` INT(11) NOT NULL COMMENT '產品流水號',
	`product_id` INT(11) NULL COMMENT '產品資料',
	`key` VARCHAR(255) NOT NULL COMMENT '產品序號' COLLATE 'latin1_swedish_ci',
	`PUR_on` DATETIME NULL COMMENT '購買時間',
	`PUR_by` INT(11) NULL COMMENT '購買人',
	`name` VARCHAR(50) NOT NULL COMMENT '商品名稱' COLLATE 'latin1_swedish_ci',
	`display_name` VARCHAR(30) NULL COLLATE 'utf8_general_ci'
) ENGINE=MyISAM;

-- 傾印  表格 dbfp.replies 結構
DROP TABLE IF EXISTS `replies`;
CREATE TABLE IF NOT EXISTS `replies` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '留言流水號',
  `thread_id` int(11) NOT NULL COMMENT '留言文章id',
  `forum_id` int(11) NOT NULL COMMENT '留言論壇id',
  `category_id` int(11) NOT NULL COMMENT '留言分類id',
  `user_id` int(11) NOT NULL COMMENT '留言者id',
  `date` datetime NOT NULL COMMENT '發佈時間',
  `content` mediumtext NOT NULL COMMENT '留言內容',
  `edited_by` int(11) DEFAULT NULL COMMENT '編輯者',
  `edited_on` datetime DEFAULT NULL COMMENT '編輯時間',
  PRIMARY KEY (`id`),
  KEY `FK_replies_forums` (`forum_id`),
  KEY `FK_replies_categories` (`category_id`),
  KEY `FK_replies_threads` (`thread_id`),
  KEY `FK_replies_users` (`user_id`),
  KEY `FK_replies_users_2` (`edited_by`),
  CONSTRAINT `FK_replies_categories` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  CONSTRAINT `FK_replies_forums` FOREIGN KEY (`forum_id`) REFERENCES `forums` (`id`),
  CONSTRAINT `FK_replies_threads` FOREIGN KEY (`thread_id`) REFERENCES `threads` (`id`),
  CONSTRAINT `FK_replies_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `FK_replies_users_2` FOREIGN KEY (`edited_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- 正在傾印表格  dbfp.replies 的資料：~6 rows (約數)
/*!40000 ALTER TABLE `replies` DISABLE KEYS */;
REPLACE INTO `replies` (`id`, `thread_id`, `forum_id`, `category_id`, `user_id`, `date`, `content`, `edited_by`, `edited_on`) VALUES
	(2, 5, 17, 8, 4, '2021-06-17 11:20:39', '<p>It&#39;s perfect!</p>\r\n', NULL, NULL),
	(3, 5, 17, 8, 3, '2021-06-17 11:21:32', '<p>It&#39;s perfect!</p>\r\n', NULL, NULL),
	(4, 6, 17, 8, 3, '2021-06-17 13:20:22', '<pre>\r\nclass <em>MyClass</em> {\r\n    // field, constructor, and \r\n    // method declarations\r\n}</pre>\r\n', NULL, NULL),
	(5, 6, 17, 8, 5, '2021-06-17 13:20:32', '<pre>\r\n<code>Take account as example.\r\n\r\npublic class Account {\r\n    private String accountNumber;\r\n    private double balance;\r\n\r\n    public Account() {\r\n        this(&quot;empty&quot;, 0.0);\r\n    }\r\n\r\n    public Account(String accountNumber, double balance) {\r\n        this.accountNumber = accountNumber;\r\n        this.balance = balance;\r\n    }\r\n\r\n    public String getAccountNumber() {\r\n        return accountNumber;\r\n    }\r\n\r\n    public double getBalance() {\r\n        return balance;\r\n    }\r\n\r\n    public void deposit(double money) {\r\n        balance += money;\r\n    }\r\n\r\n    public double withdraw(double money) {\r\n        balance -= money;\r\n        return money;\r\n    }\r\n}</code></pre>\r\n', NULL, NULL),
	(6, 6, 17, 8, 1, '2021-06-17 13:20:32', '<p>I don&#39;t know,</p>\r\n', NULL, NULL),
	(7, 6, 17, 8, 4, '2021-06-17 21:20:28', '<p>[quote=SpicyRat]</p>\r\n\r\n<p>I don&#39;t know,</p>\r\n\r\n<p><br />\r\n[/quote]</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>Same as me!</p>\r\n', NULL, NULL);
/*!40000 ALTER TABLE `replies` ENABLE KEYS */;

-- 傾印  表格 dbfp.settings 結構
DROP TABLE IF EXISTS `settings`;
CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `forum_title` varchar(255) NOT NULL COMMENT '網站標題',
  `forum_description` varchar(255) NOT NULL COMMENT '網站說明',
  `forum_url` varchar(255) NOT NULL COMMENT '網站網址',
  `forum_visits` int(11) NOT NULL COMMENT '網站瀏覽次數',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- 正在傾印表格  dbfp.settings 的資料：~1 rows (約數)
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
REPLACE INTO `settings` (`id`, `forum_title`, `forum_description`, `forum_url`, `forum_visits`) VALUES
	(1, '程式碼學術論壇', '你將可以在這裡找到你所需要的知識與技術!', 'https://127.0.0.1/', 62);
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;

-- 傾印  檢視 dbfp.statistics 結構
DROP VIEW IF EXISTS `statistics`;
-- 創建臨時表格，以解決檢視依存性錯誤
CREATE TABLE `statistics` (
	`THREAD_COUNT` BIGINT(21) NULL,
	`USER_COUNT` BIGINT(21) NULL,
	`FORUM_VISITS` INT(11) NULL
) ENGINE=MyISAM;

-- 傾印  表格 dbfp.threads 結構
DROP TABLE IF EXISTS `threads`;
CREATE TABLE IF NOT EXISTS `threads` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '文章流水號',
  `title` varchar(100) NOT NULL COMMENT '文章標題',
  `tags` varchar(255) NOT NULL DEFAULT '' COMMENT '標籤',
  `category_id` int(11) NOT NULL COMMENT '分類id',
  `forum_id` int(11) NOT NULL COMMENT '發文區id',
  `user_id` int(11) NOT NULL COMMENT '發文者id',
  `content` mediumtext NOT NULL COMMENT '文章內容',
  `charge` int(11) NOT NULL DEFAULT 0 COMMENT '收費金額(0=不收費)',
  `date` datetime NOT NULL COMMENT '發文時間',
  `locked` varchar(3) NOT NULL COMMENT '鎖定狀態',
  `edited_by` int(11) DEFAULT NULL COMMENT '編輯者',
  `edited_on` datetime DEFAULT NULL COMMENT '編輯日期',
  PRIMARY KEY (`id`),
  KEY `FK_threads_users` (`edited_by`),
  CONSTRAINT `FK_threads_users` FOREIGN KEY (`edited_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- 正在傾印表格  dbfp.threads 的資料：~5 rows (約數)
/*!40000 ALTER TABLE `threads` DISABLE KEYS */;
REPLACE INTO `threads` (`id`, `title`, `tags`, `category_id`, `forum_id`, `user_id`, `content`, `charge`, `date`, `locked`, `edited_by`, `edited_on`) VALUES
	(3, '請問 Java 的 Hello world 的程式碼是?', 'Java,Hello world', 8, 17, 1, '<p>如標題。</p>\r\n', 0, '2021-06-17 08:56:39', 'no', NULL, NULL),
	(4, 'How to set language of visual studio 2019?', 'visual studio 2019,language', 8, 18, 1, '<p>How to set language of visual studio 2019?</p>\r\n', 0, '2021-06-17 10:20:40', 'no', NULL, NULL),
	(5, 'Java for loop code', 'Java', 8, 17, 1, '<pre>\r\n<code>for (<em>statement 1</em>;<em> statement 2</em>;<em> statement 3</em>) {\r\n  <em>// code block to be executed</em>\r\n}</code></pre>\r\n', 10, '2021-06-17 14:23:45', 'no', NULL, NULL),
	(6, 'Class problem', 'class,java', 8, 17, 3, '<p>How to declare a class in java?</p>\r\n', 0, '2021-06-17 20:55:37', 'no', NULL, NULL),
	(7, 'Help me pls!', 'error,java', 8, 17, 3, '<pre>\r\n<code>This is my code, and it has some errors.</code></pre>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<pre>\r\n<code>int number = 0;\r\nSystem.out.println(&quot;You entered: &quot; + (10/number++));</code></pre>\r\n', 0, '2021-06-17 21:24:23', 'no', 2, '2021-06-17 21:24:32');
/*!40000 ALTER TABLE `threads` ENABLE KEYS */;

-- 傾印  表格 dbfp.thread_events 結構
DROP TABLE IF EXISTS `thread_events`;
CREATE TABLE IF NOT EXISTS `thread_events` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '事件流水號',
  `thread_id` int(11) NOT NULL COMMENT '發生文章id',
  `reply_id` int(11) DEFAULT NULL COMMENT '發生留言id',
  `event_name` varchar(255) NOT NULL COMMENT '事件名稱',
  `event_value` int(11) NOT NULL COMMENT '事件參數',
  `executed_by` int(11) NOT NULL COMMENT '事件執行者',
  PRIMARY KEY (`id`),
  KEY `FK_thread_events_users` (`executed_by`),
  KEY `FK_thread_events_threads` (`thread_id`),
  KEY `FK_thread_events_replies` (`reply_id`),
  CONSTRAINT `FK_thread_events_replies` FOREIGN KEY (`reply_id`) REFERENCES `replies` (`id`),
  CONSTRAINT `FK_thread_events_threads` FOREIGN KEY (`thread_id`) REFERENCES `threads` (`id`),
  CONSTRAINT `FK_thread_events_users` FOREIGN KEY (`executed_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COMMENT='文章內發生事件(解鎖文章, 留言贊助)';

-- 正在傾印表格  dbfp.thread_events 的資料：~1 rows (約數)
/*!40000 ALTER TABLE `thread_events` DISABLE KEYS */;
REPLACE INTO `thread_events` (`id`, `thread_id`, `reply_id`, `event_name`, `event_value`, `executed_by`) VALUES
	(1, 5, NULL, 'charge', 20, 3);
/*!40000 ALTER TABLE `thread_events` ENABLE KEYS */;

-- 傾印  表格 dbfp.users 結構
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usern` varchar(30) NOT NULL,
  `passwd` varchar(30) NOT NULL,
  `email` varchar(50) NOT NULL,
  `display_name` varchar(30) NOT NULL,
  `level` varchar(10) NOT NULL COMMENT '權限層級',
  `avatar` varchar(255) DEFAULT NULL COMMENT '頭像圖位置',
  `signature` varchar(255) DEFAULT NULL COMMENT '個人簡介',
  `reg_date` datetime NOT NULL COMMENT '註冊日期',
  `online_time` int(11) DEFAULT NULL COMMENT '上線時間',
  `points` int(11) NOT NULL DEFAULT 20 COMMENT ' 個人點數',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- 正在傾印表格  dbfp.users 的資料：~5 rows (約數)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
REPLACE INTO `users` (`id`, `usern`, `passwd`, `email`, `display_name`, `level`, `avatar`, `signature`, `reg_date`, `online_time`, `points`) VALUES
	(1, 'admin', 'password', '40743160@nfu.edu.tw', '管理員', 'admin', '', '', '2021-06-12 11:55:39', 1623940716, 200000),
	(2, '40743160', '40743160', '40743160@gm.nfu.edu.tw', '謝文祥', 'member', NULL, '', '2021-06-14 12:56:39', 1623923628, 20),
	(3, 'j84637587', 'j75873648', 'j84637587@gmail.com', 'SpicyRat', 'member', NULL, '', '2021-06-17 04:56:32', 1623943890, 185000),
	(4, '40743112', '40743112', '40743112@gm.nfu.edu.tw', '吳睿騰', 'member', NULL, '', '2021-06-17 20:18:50', 1623931838, 20),
	(5, '40743131', '40743131', '40743131@gm.nfu.edu.tw', '張凱迪', 'member', NULL, '', '2021-06-17 14:22:53', 1623931838, 20);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

-- 傾印  表格 dbfp.visits 結構
DROP TABLE IF EXISTS `visits`;
CREATE TABLE IF NOT EXISTS `visits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_ip` varchar(255) NOT NULL COMMENT '訪客ip',
  `last_update` date NOT NULL COMMENT '最後來訪時間(Y-m-d)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- 正在傾印表格  dbfp.visits 的資料：~5 rows (約數)
/*!40000 ALTER TABLE `visits` DISABLE KEYS */;
REPLACE INTO `visits` (`id`, `user_ip`, `last_update`) VALUES
	(1, '127.0.0.1', '2021-06-14'),
	(2, '140.130.34.14', '2021-06-13'),
	(3, '140.130.34.24', '2021-06-13'),
	(4, '140.130.34.25', '2021-06-12'),
	(5, '140.130.35.25', '2021-06-12');
/*!40000 ALTER TABLE `visits` ENABLE KEYS */;

-- 傾印  檢視 dbfp.available_product_items 結構
DROP VIEW IF EXISTS `available_product_items`;
-- 移除臨時表格，並創建最終檢視結構
DROP TABLE IF EXISTS `available_product_items`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `available_product_items` AS #查詢產品當前剩餘數量跟資訊
SELECT products.*, SUM(CASE WHEN (product_items.id IS NOT NULL AND product_items.PUR_by IS NULL) then 1 else 0 END) AS avail FROM products
LEFT JOIN product_items ON products.id = product_items.product_id
GROUP BY product_items.product_id ;

-- 傾印  檢視 dbfp.last_six_threads 結構
DROP VIEW IF EXISTS `last_six_threads`;
-- 移除臨時表格，並創建最終檢視結構
DROP TABLE IF EXISTS `last_six_threads`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `last_six_threads` AS # 取得最後6筆文章資料
SELECT * FROM THREADS ORDER BY ID DESC LIMIT 6 ;

-- 傾印  檢視 dbfp.last_thread 結構
DROP VIEW IF EXISTS `last_thread`;
-- 移除臨時表格，並創建最終檢視結構
DROP TABLE IF EXISTS `last_thread`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `last_thread` AS #取得最後一筆文章
SELECT * FROM threads ORDER BY id DESC LIMIT 1 ;

-- 傾印  檢視 dbfp.last_user 結構
DROP VIEW IF EXISTS `last_user`;
-- 移除臨時表格，並創建最終檢視結構
DROP TABLE IF EXISTS `last_user`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `last_user` AS #取得最後註冊的會員
SELECT * FROM users ORDER BY id DESC LIMIT 1 ;

-- 傾印  檢視 dbfp.product_item_full_data 結構
DROP VIEW IF EXISTS `product_item_full_data`;
-- 移除臨時表格，並創建最終檢視結構
DROP TABLE IF EXISTS `product_item_full_data`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `product_item_full_data` AS #取得商品資訊以及序號

SELECT product_items.*, products.name, users.display_name FROM product_items
INNER JOIN products ON product_items.product_id = products.id
LEFT JOIN users ON product_items.PUR_by = users.id
ORDER BY PUR_on DESC ;

-- 傾印  檢視 dbfp.statistics 結構
DROP VIEW IF EXISTS `statistics`;
-- 移除臨時表格，並創建最終檢視結構
DROP TABLE IF EXISTS `statistics`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `statistics` AS #取得文章總數, 使用者總數, 網站設定
SELECT ( SELECT COUNT(*) FROM THREADS ) AS THREAD_COUNT,
       ( SELECT COUNT(*) FROM USERS ) AS USER_COUNT,
		 ( SELECT FORUM_VISITS FROM SETTINGS ) AS FORUM_VISITS 
FROM dual ;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
