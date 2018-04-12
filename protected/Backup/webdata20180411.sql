-- MySQL dump 10.16  Distrib 10.1.26-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: WebData
-- ------------------------------------------------------
-- Server version	8.0.3-rc-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `Admin`
--

DROP TABLE IF EXISTS `Admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Admin` (
  `Admin_Id` int(4) NOT NULL AUTO_INCREMENT,
  `Admin_UserCode` varchar(36) NOT NULL DEFAULT '',
  `Admin_Password` varchar(64) NOT NULL DEFAULT '',
  `Admin_Name` varchar(36) NOT NULL DEFAULT '',
  `Admin_Power` varchar(255) NOT NULL DEFAULT '0',
  `Admin_BranchId` int(4) NOT NULL DEFAULT '0',
  `Admin_DeptId` int(4) NOT NULL DEFAULT '0',
  `Admin_Close` tinyint(1) NOT NULL DEFAULT '0',
  `Admin_GroupId` int(4) NOT NULL DEFAULT '0',
  `Admin_AddTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Admin_Id`),
  UNIQUE KEY `Admin_UserCode` (`Admin_UserCode`) USING BTREE,
  KEY `Admin_MDeptID` (`Admin_BranchId`),
  KEY `Admin_DepartmentId` (`Admin_DeptId`) USING BTREE,
  KEY `Admin_Close` (`Admin_Close`) USING BTREE,
  KEY `Admin_GroupId` (`Admin_GroupId`) USING BTREE,
  KEY `Admin_Power` (`Admin_Power`),
  CONSTRAINT `Admin_ibfk_1` FOREIGN KEY (`Admin_BranchId`) REFERENCES `Branch` (`branch_id`),
  CONSTRAINT `Admin_ibfk_2` FOREIGN KEY (`Admin_DeptId`) REFERENCES `Dept` (`dept_id`),
  CONSTRAINT `Admin_ibfk_3` FOREIGN KEY (`Admin_GroupId`) REFERENCES `AdminGroup` (`admingroup_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Admin`
--

LOCK TABLES `Admin` WRITE;
/*!40000 ALTER TABLE `Admin` DISABLE KEYS */;
INSERT INTO `Admin` VALUES (1,'admin','admin','系统管理员','0',1,1,0,1,'2018-04-10 17:40:34');
/*!40000 ALTER TABLE `Admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `AdminGroup`
--

DROP TABLE IF EXISTS `AdminGroup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AdminGroup` (
  `AdminGroup_Id` int(4) NOT NULL AUTO_INCREMENT,
  `AdminGroup_Name` varchar(32) NOT NULL DEFAULT '',
  `AdminGroup_Power` varchar(255) CHARACTER SET latin1 NOT NULL DEFAULT '',
  PRIMARY KEY (`AdminGroup_Id`),
  KEY `Admin_GroupPower` (`AdminGroup_Power`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `AdminGroup`
--

LOCK TABLES `AdminGroup` WRITE;
/*!40000 ALTER TABLE `AdminGroup` DISABLE KEYS */;
INSERT INTO `AdminGroup` VALUES (1,'系统管理组','0');
/*!40000 ALTER TABLE `AdminGroup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `App`
--

DROP TABLE IF EXISTS `App`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `App` (
  `App_Id` int(4) NOT NULL AUTO_INCREMENT,
  `App_ClassId` int(4) NOT NULL DEFAULT '0',
  `App_ClassQty` tinyint(2) NOT NULL DEFAULT '0',
  `App_Title` varchar(30) NOT NULL DEFAULT '',
  `App_Path` varchar(128) NOT NULL DEFAULT '',
  `App_Ico` varchar(60) NOT NULL DEFAULT '',
  `App_Remarks` varchar(128) NOT NULL DEFAULT '',
  `App_Close` tinyint(1) NOT NULL DEFAULT '0',
  `App_AddTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`App_Id`),
  KEY `App_Close` (`App_Close`) USING BTREE,
  KEY `App_ClassId` (`App_ClassId`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `App`
--

LOCK TABLES `App` WRITE;
/*!40000 ALTER TABLE `App` DISABLE KEYS */;
INSERT INTO `App` VALUES (1,0,0,'878','989','90980','979',0,'2017-06-08 05:43:31');
/*!40000 ALTER TABLE `App` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `AppAction`
--

DROP TABLE IF EXISTS `AppAction`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AppAction` (
  `AppAction_Id` int(4) NOT NULL AUTO_INCREMENT,
  `AppAction_AppId` int(4) NOT NULL DEFAULT '0',
  `AppAction_TableId` int(4) NOT NULL DEFAULT '0',
  `AppAction_Name` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `AppAction_File` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `AppAction_Ico` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `AppAction_Color` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `AppAction_Close` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`AppAction_Id`),
  KEY `AppAction_AppId` (`AppAction_AppId`),
  KEY `AppAction_Close` (`AppAction_Close`),
  KEY `AppAction_ibfk_2` (`AppAction_TableId`),
  CONSTRAINT `AppAction_ibfk_1` FOREIGN KEY (`AppAction_AppId`) REFERENCES `App` (`app_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `AppAction_ibfk_2` FOREIGN KEY (`AppAction_TableId`) REFERENCES `Table` (`table_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `AppAction`
--

LOCK TABLES `AppAction` WRITE;
/*!40000 ALTER TABLE `AppAction` DISABLE KEYS */;
/*!40000 ALTER TABLE `AppAction` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `AppField`
--

DROP TABLE IF EXISTS `AppField`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AppField` (
  `AppField_Id` int(11) NOT NULL AUTO_INCREMENT,
  `AppField_Name` varchar(60) NOT NULL DEFAULT '',
  `AppField_FieldId` int(4) NOT NULL DEFAULT '0',
  `AppField_AppActionId` int(4) NOT NULL DEFAULT '0',
  `AppField_AdminId` int(4) NOT NULL DEFAULT '0',
  `AppField_View` tinyint(1) NOT NULL DEFAULT '0',
  `AppField_Width` varchar(6) NOT NULL DEFAULT '0',
  `AppField_Frozen` tinyint(1) NOT NULL DEFAULT '0',
  `AppField_Align` varchar(16) NOT NULL DEFAULT '',
  `AppField_Type` varchar(30) NOT NULL DEFAULT '',
  `AppField_Api` tinyint(1) NOT NULL DEFAULT '0',
  `AppField_FieldSort` tinyint(1) NOT NULL DEFAULT '0',
  `AppField_Minimum` varchar(4) NOT NULL DEFAULT '0',
  `AppField_Maximum` varchar(4) NOT NULL DEFAULT '',
  `AppField_Readonly` tinyint(1) NOT NULL DEFAULT '0' COMMENT '只读',
  `AppField_Required` tinyint(1) NOT NULL DEFAULT '0' COMMENT '不为空',
  `AppField_Sort` tinyint(1) NOT NULL DEFAULT '0' COMMENT '排序开关',
  `AppField_Autocomplete` tinyint(1) NOT NULL DEFAULT '0' COMMENT '自动完成',
  `AppField_Power` tinyint(1) NOT NULL DEFAULT '0',
  `AppField_Close` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`AppField_Id`),
  KEY `AppField_Sort` (`AppField_Sort`),
  KEY `AppField_Frozen` (`AppField_Frozen`),
  KEY `AppField_FieldSort` (`AppField_FieldSort`),
  KEY `AppField_Close` (`AppField_Close`) USING BTREE,
  KEY `AppField_AdminId` (`AppField_AdminId`) USING BTREE,
  KEY `AppField_Table` (`AppField_AppActionId`) USING BTREE,
  KEY `AppField_FieldId` (`AppField_FieldId`) USING BTREE,
  CONSTRAINT `AppField_ibfk_2` FOREIGN KEY (`AppField_AppActionId`) REFERENCES `AppAction` (`appaction_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `AppField_ibfk_3` FOREIGN KEY (`AppField_AdminId`) REFERENCES `Admin` (`admin_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `AppField_ibfk_4` FOREIGN KEY (`AppField_FieldId`) REFERENCES `TableField` (`tablefield_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `AppField`
--

LOCK TABLES `AppField` WRITE;
/*!40000 ALTER TABLE `AppField` DISABLE KEYS */;
/*!40000 ALTER TABLE `AppField` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Article`
--

DROP TABLE IF EXISTS `Article`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Article` (
  `Article_Id` int(11) NOT NULL AUTO_INCREMENT,
  `Article_ChannleId` int(11) NOT NULL DEFAULT '1',
  `Article_Title` varchar(60) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `Article_Subtitle` varchar(60) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `Article_Titleimg` varchar(60) NOT NULL DEFAULT '',
  `Article_TypeId` int(11) NOT NULL DEFAULT '1',
  `Article_Desc` varchar(200) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `Article_Key` varchar(100) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `Article_AddTime` int(10) NOT NULL DEFAULT '0',
  `Article_Content` text CHARACTER SET latin1 NOT NULL,
  `Article_Visit` varchar(8) NOT NULL DEFAULT '0',
  `Article_Tag` varchar(45) NOT NULL DEFAULT '' COMMENT '标签',
  `Article_Remark` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否评论',
  `Article_Remarks` varchar(8) NOT NULL DEFAULT '0' COMMENT '评论数',
  `Article_Source` varchar(45) NOT NULL DEFAULT '' COMMENT '来源',
  `Article_AdminId` int(11) NOT NULL DEFAULT '1',
  `Article_Del` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`Article_Id`),
  KEY `Article_Index` (`Article_ChannleId`,`Article_TypeId`,`Article_AdminId`,`Article_Del`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Article`
--

LOCK TABLES `Article` WRITE;
/*!40000 ALTER TABLE `Article` DISABLE KEYS */;
/*!40000 ALTER TABLE `Article` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Branch`
--

DROP TABLE IF EXISTS `Branch`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Branch` (
  `Branch_Id` int(4) NOT NULL AUTO_INCREMENT,
  `Branch_ClassId` int(4) NOT NULL DEFAULT '0',
  `Branch_Code` varchar(10) NOT NULL DEFAULT '',
  `Branch_Name` varchar(60) NOT NULL DEFAULT '',
  `Branch_Bank` varchar(60) NOT NULL DEFAULT '',
  `Branch_BankAccon` varchar(32) NOT NULL DEFAULT '',
  `Branch_Tel` varchar(60) NOT NULL DEFAULT '',
  `Branch_Address` varchar(120) NOT NULL DEFAULT '',
  `Branch_Close` tinyint(1) NOT NULL DEFAULT '0',
  `Branch_Type` tinyint(1) NOT NULL DEFAULT '0',
  `Branch_AddTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Branch_Id`),
  UNIQUE KEY `MDept_Code` (`Branch_Code`) USING BTREE,
  KEY `MDept_Close` (`Branch_Close`) USING BTREE,
  KEY `Branch_Type` (`Branch_Type`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Branch`
--

LOCK TABLES `Branch` WRITE;
/*!40000 ALTER TABLE `Branch` DISABLE KEYS */;
INSERT INTO `Branch` VALUES (1,0,'01','总部','','','','',0,1,'2017-06-06 03:09:57');
/*!40000 ALTER TABLE `Branch` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Channels`
--

DROP TABLE IF EXISTS `Channels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Channels` (
  `Channels_Id` int(11) NOT NULL AUTO_INCREMENT,
  `Channels_ClassId` int(11) NOT NULL DEFAULT '0',
  `Channels_Name` varchar(60) NOT NULL DEFAULT '',
  `Channels_TypeId` int(11) NOT NULL DEFAULT '1',
  `Channels_Url` varchar(60) NOT NULL DEFAULT '',
  `Channels_Sort` varchar(45) NOT NULL DEFAULT '0',
  `Channels_Close` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`Channels_Id`),
  UNIQUE KEY `Channels_Name_UNIQUE` (`Channels_Name`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Channels`
--

LOCK TABLES `Channels` WRITE;
/*!40000 ALTER TABLE `Channels` DISABLE KEYS */;
INSERT INTO `Channels` VALUES (1,0,'新闻中心',1,'','2',0),(2,1,'公司新闻',1,'','1',0),(3,1,'业内新闻',1,'','2',0),(4,0,'产品中心',1,'','3',0),(5,0,'公司概况',1,'','1',0),(6,0,'典型案例',1,'','4',0),(7,0,'联系我们',1,'','5',0);
/*!40000 ALTER TABLE `Channels` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Dept`
--

DROP TABLE IF EXISTS `Dept`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Dept` (
  `Dept_Id` int(4) NOT NULL AUTO_INCREMENT,
  `Dept_ClassId` int(4) NOT NULL DEFAULT '0',
  `Dept_Code` varchar(10) NOT NULL DEFAULT '',
  `Dept_Name` varchar(60) NOT NULL DEFAULT '',
  `Dept_BranchId` int(4) NOT NULL,
  `Dept_Close` tinyint(1) NOT NULL DEFAULT '0',
  `Dept_AddTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Dept_Id`),
  UNIQUE KEY `Department_Code` (`Dept_Code`) USING BTREE,
  KEY `Department_ClassId` (`Dept_ClassId`) USING BTREE,
  KEY `Department_MDeptId` (`Dept_BranchId`) USING BTREE,
  KEY `Department_Close` (`Dept_Close`) USING BTREE,
  CONSTRAINT `Dept_ibfk_1` FOREIGN KEY (`Dept_BranchId`) REFERENCES `Branch` (`branch_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Dept`
--

LOCK TABLES `Dept` WRITE;
/*!40000 ALTER TABLE `Dept` DISABLE KEYS */;
INSERT INTO `Dept` VALUES (1,0,'001','默认部门',1,0,'2018-04-10 17:38:56');
/*!40000 ALTER TABLE `Dept` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Employ`
--

DROP TABLE IF EXISTS `Employ`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Employ` (
  `Employ_Id` int(4) NOT NULL AUTO_INCREMENT,
  `Employ_Code` varchar(32) NOT NULL DEFAULT '',
  `Employ_Name` varchar(32) NOT NULL DEFAULT '',
  `Employ_DeptId` int(4) NOT NULL DEFAULT '0',
  `Employ_BranchId` int(4) NOT NULL DEFAULT '0',
  `Employ_Pay` decimal(11,0) NOT NULL DEFAULT '0',
  `Employ_Sex` tinyint(1) NOT NULL,
  `Employ_Idcard` varchar(32) NOT NULL DEFAULT '',
  `Employ_Birthday` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `Employ_EntryDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `Employ_Culture` varchar(12) NOT NULL DEFAULT '',
  `Employ_Phone` varchar(32) NOT NULL DEFAULT '',
  `Employ_HomeTel` varchar(32) NOT NULL DEFAULT '',
  `Employ_WorkTel` varchar(32) NOT NULL DEFAULT '',
  `Employ_Mail` varchar(64) NOT NULL DEFAULT '',
  `Employ_Bank` varchar(32) NOT NULL DEFAULT '',
  `Employ_Bankno` varchar(32) NOT NULL DEFAULT '',
  `Employ_Address` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`Employ_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Employ`
--

LOCK TABLES `Employ` WRITE;
/*!40000 ALTER TABLE `Employ` DISABLE KEYS */;
/*!40000 ALTER TABLE `Employ` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Login_Logs`
--

DROP TABLE IF EXISTS `Login_Logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Login_Logs` (
  `Login_Logs_Id` int(11) NOT NULL AUTO_INCREMENT,
  `Login_Logs_AdminId` varchar(100) NOT NULL DEFAULT '0',
  `Login_Logs_Ip` varchar(15) NOT NULL,
  `Login_Logs_Os` varchar(30) NOT NULL,
  `Login_Logs_Lang` varchar(30) NOT NULL,
  `Login_Logs_Browser` varchar(30) NOT NULL,
  `Login_Logs_Time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Login_Logs_Address` varchar(45) NOT NULL,
  `Login_Logs_Fail` tinyint(1) NOT NULL COMMENT '失败',
  `Login_Logs_Close` tinyint(1) NOT NULL DEFAULT '0',
  `Login_Logs_MDeptId` varchar(1) NOT NULL DEFAULT '2',
  PRIMARY KEY (`Login_Logs_Id`),
  KEY `Login_UserId` (`Login_Logs_AdminId`,`Login_Logs_Ip`,`Login_Logs_Os`,`Login_Logs_Lang`,`Login_Logs_Browser`,`Login_Logs_Time`,`Login_Logs_Fail`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Login_Logs`
--

LOCK TABLES `Login_Logs` WRITE;
/*!40000 ALTER TABLE `Login_Logs` DISABLE KEYS */;
INSERT INTO `Login_Logs` VALUES (1,'232','32','32','323','32','2017-06-07 09:49:12','32',3,0,'2');
/*!40000 ALTER TABLE `Login_Logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Power`
--

DROP TABLE IF EXISTS `Power`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Power` (
  `Power_AppId` int(4) NOT NULL,
  `Power_View` int(4) NOT NULL DEFAULT '0',
  `Power_Add` int(4) NOT NULL DEFAULT '0',
  `Power_Edit` int(4) NOT NULL DEFAULT '0',
  `Power_Del` int(4) NOT NULL DEFAULT '0',
  `Power_Approve` int(4) NOT NULL DEFAULT '0',
  `Power_Veto` int(4) NOT NULL DEFAULT '0',
  `Power_Print` int(4) NOT NULL DEFAULT '0',
  `Power_Export` int(4) NOT NULL DEFAULT '0',
  `Power_import` int(4) NOT NULL DEFAULT '0',
  `Power_Operate` int(4) NOT NULL DEFAULT '0',
  `Power_Setting` int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`Power_AppId`),
  UNIQUE KEY `Power_AppId` (`Power_AppId`) USING BTREE,
  CONSTRAINT `Power_ibfk_1` FOREIGN KEY (`Power_AppId`) REFERENCES `App` (`app_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Power`
--

LOCK TABLES `Power` WRITE;
/*!40000 ALTER TABLE `Power` DISABLE KEYS */;
/*!40000 ALTER TABLE `Power` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `SiteTypes`
--

DROP TABLE IF EXISTS `SiteTypes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SiteTypes` (
  `SiteTypes_Id` int(11) NOT NULL AUTO_INCREMENT,
  `SiteTypes_Name` varchar(45) NOT NULL DEFAULT '',
  `SiteTypes_Tpl` varchar(60) NOT NULL DEFAULT '',
  `SiteTypes_Type` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`SiteTypes_Id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `SiteTypes`
--

LOCK TABLES `SiteTypes` WRITE;
/*!40000 ALTER TABLE `SiteTypes` DISABLE KEYS */;
INSERT INTO `SiteTypes` VALUES (1,'文章','article',1),(2,'图片','photo',1),(3,'产品','goods',1);
/*!40000 ALTER TABLE `SiteTypes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Sysconf`
--

DROP TABLE IF EXISTS `Sysconf`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Sysconf` (
  `Sysconf_Id` int(11) NOT NULL AUTO_INCREMENT,
  `Sysconf_Name` varchar(45) NOT NULL DEFAULT '',
  `Sysconf_Value` varchar(200) NOT NULL DEFAULT '',
  `Sysconf_Memo` varchar(60) NOT NULL DEFAULT '',
  `Sysconf_Type` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`Sysconf_Id`),
  UNIQUE KEY `Sysconf_Name_UNIQUE` (`Sysconf_Name`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Sysconf`
--

LOCK TABLES `Sysconf` WRITE;
/*!40000 ALTER TABLE `Sysconf` DISABLE KEYS */;
INSERT INTO `Sysconf` VALUES (1,'accessKey','023a7cffa7f0c2f4b4c61ef2fb1','',0),(2,'secretKey','79dc9b8206721570b24a1f58dac62a9','',0),(3,'SiteName','珠海协和成信息技术有限公司','',1),(4,'Keywords','珠海协和成信息技术有限公司','',1),(5,'Description','珠海协和成信息技术有限公司','',1),(6,'Email','zhxsglhq@163.com','',1),(7,'Address','广东珠海市香洲区湾仔沙电脑城1-32号','',1),(8,'ICP','粤ICP备13057277号','',1),(9,'Tel','0756-2118665','',1),(10,'QQ','308540244','',1),(11,'Phone','13928044710','',1);
/*!40000 ALTER TABLE `Sysconf` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Table`
--

DROP TABLE IF EXISTS `Table`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Table` (
  `Table_Id` int(4) NOT NULL AUTO_INCREMENT,
  `Table_Name` varchar(255) CHARACTER SET latin1 NOT NULL,
  `Table_Remarks` varchar(64) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`Table_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Table`
--

LOCK TABLES `Table` WRITE;
/*!40000 ALTER TABLE `Table` DISABLE KEYS */;
/*!40000 ALTER TABLE `Table` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-04-11  1:41:57
