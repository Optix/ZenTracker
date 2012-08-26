SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `picture` varchar(255) DEFAULT NULL,
  `root_id` bigint(20) DEFAULT NULL,
  `lft` int(11) DEFAULT NULL,
  `rgt` int(11) DEFAULT NULL,
  `level` smallint(6) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_sluggable_idx` (`slug`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `donations`;
CREATE TABLE IF NOT EXISTS `donations` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `donor` bigint(20) DEFAULT NULL,
  `amount` float(18,2) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `donor_idx` (`donor`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `frm_cats`;
CREATE TABLE IF NOT EXISTS `frm_cats` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `frm_cats_sluggable_idx` (`slug`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `frm_forums`;
CREATE TABLE IF NOT EXISTS `frm_forums` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `cat` bigint(20) NOT NULL,
  `name` varchar(60) NOT NULL,
  `description` varchar(140) DEFAULT NULL,
  `minroleread` varchar(3) DEFAULT NULL,
  `minlevelread` bigint(20) DEFAULT NULL,
  `minrolewrite` varchar(3) DEFAULT NULL,
  `minlevelwrite` bigint(20) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `frm_forums_sluggable_idx` (`slug`),
  KEY `cat_idx` (`cat`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `frm_topics`;
CREATE TABLE IF NOT EXISTS `frm_topics` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `forum` bigint(20) NOT NULL,
  `title` varchar(60) NOT NULL,
  `is_locked` tinyint(1) DEFAULT NULL,
  `is_important` tinyint(1) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `frm_topics_sluggable_idx` (`slug`),
  KEY `forum_idx` (`forum`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `frm_topicsusr`;
CREATE TABLE IF NOT EXISTS `frm_topicsusr` (
  `topic` bigint(20) NOT NULL DEFAULT '0',
  `uid` bigint(20) NOT NULL DEFAULT '0',
  `following` tinyint(1) DEFAULT NULL,
  `replied` tinyint(1) DEFAULT NULL,
  `lastmsgid` bigint(20) NOT NULL,
  PRIMARY KEY (`topic`,`uid`),
  KEY `lastmsgid_idx` (`lastmsgid`),
  KEY `frm_topicsusr_uid_users_id` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `invites`;
CREATE TABLE IF NOT EXISTS `invites` (
  `uid` bigint(20) NOT NULL DEFAULT '0',
  `code` varchar(10) NOT NULL DEFAULT '',
  `expire` datetime NOT NULL,
  `multiple` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`uid`,`code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `ips`;
CREATE TABLE IF NOT EXISTS `ips` (
  `ip` varchar(45) NOT NULL DEFAULT '',
  `uid` bigint(20) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`ip`),
  KEY `uid_idx` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `msg_messages`;
CREATE TABLE IF NOT EXISTS `msg_messages` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `author` bigint(20) DEFAULT NULL,
  `content` text NOT NULL,
  `module` varchar(255) DEFAULT NULL,
  `tid` bigint(20) DEFAULT NULL,
  `pmid` bigint(20) DEFAULT NULL,
  `shtid` bigint(20) DEFAULT NULL,
  `pollid` bigint(20) DEFAULT NULL,
  `upid` bigint(20) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `msg_messages_module_idx` (`module`),
  KEY `author_idx` (`author`),
  KEY `msg_messages_shtid_shoutbox_id` (`shtid`),
  KEY `msg_messages_pmid_pm_topics_id` (`pmid`),
  KEY `msg_messages_pollid_poll_polls_id` (`pollid`),
  KEY `msg_messages_tid_frm_topics_id` (`tid`),
  KEY `msg_messages_upid_uploads_id` (`upid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `msg_votes`;
CREATE TABLE IF NOT EXISTS `msg_votes` (
  `uid` bigint(20) NOT NULL DEFAULT '0',
  `mid` bigint(20) NOT NULL DEFAULT '0',
  `vote` tinyint(1) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`uid`,`mid`),
  KEY `msg_votes_mid_msg_messages_id` (`mid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `news`;
CREATE TABLE IF NOT EXISTS `news` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `title` varchar(60) NOT NULL,
  `description` text NOT NULL,
  `author` bigint(20) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `news_sluggable_idx` (`slug`),
  KEY `author_idx` (`author`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `owner` bigint(20) NOT NULL,
  `uid` bigint(20) DEFAULT NULL,
  `readed` tinyint(1) DEFAULT NULL,
  `picture` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `extract` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid_idx` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `pm_participants`;
CREATE TABLE IF NOT EXISTS `pm_participants` (
  `mpid` bigint(20) NOT NULL DEFAULT '0',
  `mpmid` bigint(20) NOT NULL DEFAULT '0',
  `readed` tinyint(1) DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`mpid`,`mpmid`),
  KEY `pm_participants_mpmid_users_id` (`mpmid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `pm_topics`;
CREATE TABLE IF NOT EXISTS `pm_topics` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `title` varchar(60) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pm_topics_sluggable_idx` (`slug`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `poll_choices`;
CREATE TABLE IF NOT EXISTS `poll_choices` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `poll` bigint(20) NOT NULL,
  `choice` varchar(128) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `poll_idx` (`poll`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `poll_polls`;
CREATE TABLE IF NOT EXISTS `poll_polls` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `title` varchar(140) NOT NULL,
  `multiple` tinyint(1) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `poll_polls_sluggable_idx` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `poll_votes`;
CREATE TABLE IF NOT EXISTS `poll_votes` (
  `choice` bigint(20) NOT NULL DEFAULT '0',
  `uid` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`choice`,`uid`),
  KEY `poll_votes_uid_users_id` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `shoutbox`;
CREATE TABLE IF NOT EXISTS `shoutbox` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `author` bigint(20) DEFAULT NULL,
  `description` text,
  `link` varchar(255) DEFAULT NULL,
  `system` tinyint(1) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `author_idx` (`author`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `torrents_peers`;
CREATE TABLE IF NOT EXISTS `torrents_peers` (
  `hash` char(40) NOT NULL DEFAULT '',
  `pid` char(40) NOT NULL DEFAULT '',
  `peer_id` char(40) NOT NULL,
  `uid` bigint(20) DEFAULT NULL,
  `ip` varchar(45) NOT NULL,
  `port` smallint(5) unsigned NOT NULL,
  `download` bigint(20) unsigned NOT NULL,
  `upload` bigint(20) unsigned NOT NULL,
  `remain` bigint(20) unsigned NOT NULL,
  `useragent` varchar(45) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`hash`,`pid`),
  KEY `uid_idx` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `torrents_peers_offset`;
CREATE TABLE IF NOT EXISTS `torrents_peers_offset` (
  `hash` char(40) NOT NULL DEFAULT '',
  `pid` char(40) NOT NULL DEFAULT '',
  `download` bigint(20) unsigned NOT NULL,
  `upload` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`hash`,`pid`),
  KEY `torrents_peers_offset_pid_users_pid` (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `uploads`;
CREATE TABLE IF NOT EXISTS `uploads` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `hash` char(40) DEFAULT NULL,
  `title` varchar(100) NOT NULL,
  `cat` bigint(20) NOT NULL,
  `description` text NOT NULL,
  `nfo` text,
  `author` bigint(20) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `size` bigint(20) unsigned NOT NULL,
  `minlevel` bigint(20) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uploads_sluggable_idx` (`slug`),
  KEY `hash_idx` (`hash`),
  KEY `author_idx` (`author`),
  KEY `cat_idx` (`cat`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `uploads_hits`;
CREATE TABLE IF NOT EXISTS `uploads_hits` (
  `upid` bigint(20) NOT NULL DEFAULT '0',
  `uid` bigint(20) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`upid`,`uid`),
  KEY `uploads_hits_uid_users_id` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `parent` bigint(20) DEFAULT NULL,
  `username` varchar(25) NOT NULL,
  `password` varchar(64) NOT NULL,
  `passexpires` datetime NOT NULL,
  `random` varchar(5) NOT NULL,
  `email` varchar(255) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `lastvisit` datetime DEFAULT NULL,
  `pid` char(40) NOT NULL,
  `role` varchar(3) DEFAULT 'mbr',
  `active` tinyint(1) DEFAULT '1',
  `reason` varchar(255) DEFAULT NULL,
  `ban_expire` datetime DEFAULT NULL,
  `description` text,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_sluggable_idx` (`slug`),
  KEY `pid_idx` (`pid`),
  KEY `parent_idx` (`parent`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;


ALTER TABLE `donations`
  ADD CONSTRAINT `donations_donor_users_id` FOREIGN KEY (`donor`) REFERENCES `users` (`id`) ON DELETE SET NULL;

ALTER TABLE `frm_forums`
  ADD CONSTRAINT `frm_forums_cat_frm_cats_id` FOREIGN KEY (`cat`) REFERENCES `frm_cats` (`id`);

ALTER TABLE `frm_topics`
  ADD CONSTRAINT `frm_topics_forum_frm_forums_id` FOREIGN KEY (`forum`) REFERENCES `frm_forums` (`id`);

ALTER TABLE `frm_topicsusr`
  ADD CONSTRAINT `frm_topicsusr_lastmsgid_msg_messages_id` FOREIGN KEY (`lastmsgid`) REFERENCES `msg_messages` (`id`),
  ADD CONSTRAINT `frm_topicsusr_topic_frm_topics_id` FOREIGN KEY (`topic`) REFERENCES `frm_topics` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `frm_topicsusr_uid_users_id` FOREIGN KEY (`uid`) REFERENCES `users` (`id`) ON DELETE CASCADE;

ALTER TABLE `invites`
  ADD CONSTRAINT `invites_uid_users_id` FOREIGN KEY (`uid`) REFERENCES `users` (`id`) ON DELETE CASCADE;

ALTER TABLE `ips`
  ADD CONSTRAINT `ips_uid_users_id` FOREIGN KEY (`uid`) REFERENCES `users` (`id`) ON DELETE CASCADE;

ALTER TABLE `msg_messages`
  ADD CONSTRAINT `msg_messages_author_users_id` FOREIGN KEY (`author`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `msg_messages_pmid_pm_topics_id` FOREIGN KEY (`pmid`) REFERENCES `pm_topics` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `msg_messages_pollid_poll_polls_id` FOREIGN KEY (`pollid`) REFERENCES `poll_polls` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `msg_messages_shtid_shoutbox_id` FOREIGN KEY (`shtid`) REFERENCES `shoutbox` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `msg_messages_tid_frm_topics_id` FOREIGN KEY (`tid`) REFERENCES `frm_topics` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `msg_messages_upid_uploads_id` FOREIGN KEY (`upid`) REFERENCES `uploads` (`id`) ON DELETE CASCADE;

ALTER TABLE `msg_votes`
  ADD CONSTRAINT `msg_votes_mid_msg_messages_id` FOREIGN KEY (`mid`) REFERENCES `msg_messages` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `msg_votes_uid_users_id` FOREIGN KEY (`uid`) REFERENCES `users` (`id`) ON DELETE CASCADE;

ALTER TABLE `news`
  ADD CONSTRAINT `news_author_users_id` FOREIGN KEY (`author`) REFERENCES `users` (`id`) ON DELETE SET NULL;

ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_uid_users_id` FOREIGN KEY (`uid`) REFERENCES `users` (`id`) ON DELETE CASCADE;

ALTER TABLE `pm_participants`
  ADD CONSTRAINT `pm_participants_mpid_pm_topics_id` FOREIGN KEY (`mpid`) REFERENCES `pm_topics` (`id`),
  ADD CONSTRAINT `pm_participants_mpmid_users_id` FOREIGN KEY (`mpmid`) REFERENCES `users` (`id`);

ALTER TABLE `poll_choices`
  ADD CONSTRAINT `poll_choices_poll_poll_polls_id` FOREIGN KEY (`poll`) REFERENCES `poll_polls` (`id`) ON DELETE CASCADE;

ALTER TABLE `poll_votes`
  ADD CONSTRAINT `poll_votes_choice_poll_choices_id` FOREIGN KEY (`choice`) REFERENCES `poll_choices` (`id`),
  ADD CONSTRAINT `poll_votes_uid_users_id` FOREIGN KEY (`uid`) REFERENCES `users` (`id`) ON DELETE CASCADE;

ALTER TABLE `shoutbox`
  ADD CONSTRAINT `shoutbox_author_users_id` FOREIGN KEY (`author`) REFERENCES `users` (`id`) ON DELETE SET NULL;

ALTER TABLE `torrents_peers`
  ADD CONSTRAINT `torrents_peers_ibfk_1` FOREIGN KEY (`hash`) REFERENCES `uploads` (`hash`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `torrents_peers_uid_users_id` FOREIGN KEY (`uid`) REFERENCES `users` (`id`) ON DELETE SET NULL;

ALTER TABLE `torrents_peers_offset`
  ADD CONSTRAINT `torrents_peers_offset_ibfk_1` FOREIGN KEY (`hash`) REFERENCES `uploads` (`hash`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `torrents_peers_offset_pid_users_pid` FOREIGN KEY (`pid`) REFERENCES `users` (`pid`) ON DELETE CASCADE;

ALTER TABLE `uploads`
  ADD CONSTRAINT `uploads_author_users_id` FOREIGN KEY (`author`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `uploads_cat_categories_id` FOREIGN KEY (`cat`) REFERENCES `categories` (`id`);

ALTER TABLE `uploads_hits`
  ADD CONSTRAINT `uploads_hits_uid_users_id` FOREIGN KEY (`uid`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `uploads_hits_upid_uploads_id` FOREIGN KEY (`upid`) REFERENCES `uploads` (`id`) ON DELETE CASCADE;

ALTER TABLE `users`
  ADD CONSTRAINT `users_parent_users_id` FOREIGN KEY (`parent`) REFERENCES `users` (`id`);
