-- --------------------------------------------------------
--
-- Table structure for table `#__re_agentrealty`
--
CREATE TABLE IF NOT EXISTS `#__re_agentrealty` (
`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `realty_id` int(10) unsigned NOT NULL,
  `agent_id` int(10) unsigned NOT NULL,
  `plan_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `params` text,
  PRIMARY KEY (`id`)
)DEFAULT CHARSET=utf8;

--
-- Table structure for table `#__re_agents`
--

CREATE TABLE IF NOT EXISTS `#__re_agents` (
`id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `payment_method` varchar(100) NOT NULL,
  `token` varchar(100) NOT NULL,
  `transaction_id` varchar(100) DEFAULT NULL,
  `username` varchar(50) NOT NULL DEFAULT '',
  `alias` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `country_id` int(11) NOT NULL,
  `address` varchar(250) DEFAULT '',
  `locstate` tinyint(3) unsigned NOT NULL,
  `phone` varchar(255) DEFAULT '',
  `mobile` varchar(255) DEFAULT '',
  `email` varchar(50) DEFAULT '',
  `organization` varchar(255) DEFAULT NULL,
  `avatar` text,
  `sub_desc` varchar(255) NOT NULL,
  `description` text,
  `count` int(11) DEFAULT '0',
  `count_limit` int(11) DEFAULT '0' COMMENT 'tác dung=?, bảng plan cũng có ?',
  `date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_paid` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `approved` tinyint(4) NOT NULL,
  `published` tinyint(4) NOT NULL DEFAULT '0',
  `featured` tinyint(3) unsigned NOT NULL,
  `ordering` int(11) NOT NULL,
  `checked_out` int(11) DEFAULT '0',
  `checked_out_time` datetime DEFAULT '0000-00-00 00:00:00',
  `fax` varchar(25) NOT NULL DEFAULT '',
  `msn` varchar(100) NOT NULL,
  `skype` varchar(100) NOT NULL,
  `googleplus` varchar(100) NOT NULL,
  `linkedin` varchar(100) NOT NULL,
  `facebook` varchar(100) NOT NULL,
  `twitter` varchar(100) NOT NULL,
  `website` varchar(100) NOT NULL DEFAULT '',
  `params` text,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table structure for table `#__re_amenities`
--

CREATE TABLE IF NOT EXISTS `#__re_amenities` (
`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(125) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `cat` tinyint(3) NOT NULL DEFAULT '1',
  `params` text,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table structure for table `#__re_configs`
--

CREATE TABLE IF NOT EXISTS `#__re_configs` (
  `key` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `value` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `params` text,
  PRIMARY KEY (`key`)
) DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__re_countries`
--

CREATE TABLE IF NOT EXISTS `#__re_countries` (
`id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `published` tinyint(4) NOT NULL DEFAULT '1',
  `params` text,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__re_currencies`
--

CREATE TABLE IF NOT EXISTS `#__re_currencies` (
`id` int(4) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `sign` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `position` tinyint(11) NOT NULL DEFAULT '0',
  `description` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `published` tinyint(4) NOT NULL DEFAULT '0',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `checked_out` int(11) NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `params` text,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__re_field_value`
--

CREATE TABLE IF NOT EXISTS `#__re_fields` (
`id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT '',
  `title` varchar(255) DEFAULT '',
  `description` text,
  `field_type` tinyint(3) DEFAULT '0',
  `required` tinyint(3) DEFAULT '0',
  `values` text,
  `default_values` text,
  `rows` int(11) DEFAULT NULL,
  `cols` int(11) DEFAULT NULL,
  `size` int(11) DEFAULT NULL,
  `css_class` varchar(50) DEFAULT NULL,
  `ordering` int(11) NOT NULL DEFAULT '0',
  `published` tinyint(1) DEFAULT '0',
  `access` tinyint(1) DEFAULT '0',
  `core` tinyint(1) DEFAULT '0',
  `search_field` tinyint(1) DEFAULT '1',
  `checked_out` int(11) NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `language` char(7) NOT NULL DEFAULT '',
  `params` text,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table structure for table `#__re_field_value_new`
--

CREATE TABLE IF NOT EXISTS `#__re_field_value` (
`id` int(11) NOT NULL AUTO_INCREMENT,
  `field_id` int(11) DEFAULT NULL,
  `realty_id` int(11) DEFAULT NULL,
  `field_value` varchar(255) DEFAULT NULL,
  `params` text,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;



-- --------------------------------------------------------

--
-- Table structure for table `#__re_fields`
--

CREATE TABLE IF NOT EXISTS `#__re_field_value_new` (
`id` int(11) NOT NULL AUTO_INCREMENT,
  `realty_id` int(11) DEFAULT NULL,
  `field_extra` varchar(500) DEFAULT NULL,
  `params` text,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;



-- --------------------------------------------------------

--
-- Table structure for table `#__re_messages`
--

CREATE TABLE IF NOT EXISTS `#__re_messages` (
`id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT '0',
  `receive_id` int(11) DEFAULT NULL,
  `realty_id` int(11) DEFAULT '0',
  `name` varchar(255) DEFAULT '',
  `email` varchar(255) DEFAULT '',
  `subject` varchar(255) DEFAULT '',
  `content` text,
  `creator` tinyint(1) DEFAULT '0',
  `status` tinyint(1) DEFAULT '0',
  `date_created` datetime DEFAULT '0000-00-00 00:00:00',
  `checked_out` int(11) NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_phone` varchar(15) DEFAULT NULL,
  `params` text,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table structure for table `#__re_plans`
--

CREATE TABLE IF NOT EXISTS `#__re_plans` (
`id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(155) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `image` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `price` float(10,2) NOT NULL DEFAULT '0.00',
  `currency_id` int(11) NOT NULL,
  `days` int(10) NOT NULL DEFAULT '0',
  `days_type` set('day','month') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'day',
  `count_limit` int(10) NOT NULL DEFAULT '0',
  `date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `published` tinyint(1) NOT NULL,
  `featured` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `checked_out` int(11) NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `params` text,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table structure for table `#__re_realtie_images`
--

CREATE TABLE IF NOT EXISTS `#__re_realties` (
`id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `alias` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `preview_image` text,
  `keywords` varchar(250) DEFAULT NULL,
  `type_id` int(11) NOT NULL,
  `cat_id` int(10) unsigned NOT NULL DEFAULT '0',
  `user_id` int(11) DEFAULT '0',
  `agent_id` int(11) DEFAULT '0',
  `currency_id` int(11) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_ended` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `price` decimal(12,2) NOT NULL,
  `price2` decimal(12,2) unsigned NOT NULL,
  `price_freq` varchar(200) DEFAULT NULL,
  `sub_desc` varchar(255) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `address` varchar(255) NOT NULL DEFAULT '',
  `latitude` varchar(255) NOT NULL,
  `longitude` varchar(255) NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `approved` tinyint(1) NOT NULL DEFAULT '0',
  `sale` tinyint(1) DEFAULT '0',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `count` int(11) DEFAULT '0',
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `meta_keywords` varchar(255) NOT NULL,
  `meta_desc` text NOT NULL,
  `checked_out` int(11) NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `access` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'The access level required to view the menu item.',
  `language` char(7) NOT NULL DEFAULT '',
  `show_map` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `terms` text NOT NULL,
  `locstate` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `country_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `call_for_price` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `beds` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `baths` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `sqft` int(10) unsigned NOT NULL DEFAULT '0',
  `lot_type` varchar(100) NOT NULL DEFAULT '0',
  `video` longtext,
  `featured` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(10) unsigned NOT NULL DEFAULT '0',
  `publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `state` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `garages` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `extra_field` varchar(1000) DEFAULT NULL,
  `params` text,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table structure for table `#__re_realties`
--

CREATE TABLE IF NOT EXISTS `#__re_realtie_images` (
`Id` int(11) NOT NULL AUTO_INCREMENT,
  `realty_id` varchar(11) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `path_image` varchar(255) NOT NULL DEFAULT '',
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `params` text,
  PRIMARY KEY (`Id`)
) DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__re_states`
--

CREATE TABLE IF NOT EXISTS `#__re_realtyamid` (
`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `realty_id` int(11) unsigned NOT NULL DEFAULT '0',
  `cat_id` int(11) unsigned NOT NULL DEFAULT '0',
  `amen_id` int(11) unsigned NOT NULL DEFAULT '0',
  `params` text,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table structure for table `#__re_types`
--

CREATE TABLE IF NOT EXISTS `#__re_states` (
`id` int(11) NOT NULL AUTO_INCREMENT,
  `country_id` int(11) NOT NULL DEFAULT '0',
  `state_name` varchar(255) DEFAULT NULL,
  `state_code` varchar(50) DEFAULT NULL,
  `params` text,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__re_realtyamid`
--

CREATE TABLE IF NOT EXISTS `#__re_types` (
`id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `alias` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `icon` varchar(255) NOT NULL,
  `description` text,
  `published` tinyint(1) NOT NULL,
  `ordering` int(11) NOT NULL DEFAULT '0',
  `checked_out` int(11) NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `language` char(7) NOT NULL DEFAULT '',
  `access` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'The access level required to view the menu item.',
  `banner_image` varchar(255) NOT NULL DEFAULT '',
  `banner_color` varchar(7) NOT NULL DEFAULT '',
  `show_banner` tinyint(1) NOT NULL DEFAULT '1',
  `params` text,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;
