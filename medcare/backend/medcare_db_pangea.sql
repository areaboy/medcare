
CREATE TABLE `appointments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pangea_userid` varchar(200) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `fullname` varchar(200) DEFAULT NULL,
  `services_title` varchar(200) DEFAULT NULL,
  `pangea_vault_id` varchar(200) DEFAULT NULL,
  `status` varchar(200) DEFAULT NULL,
  `a_date` varchar(200) DEFAULT NULL,
  `a_time` varchar(200) DEFAULT NULL,
  `timing` varchar(200) DEFAULT NULL,
  `diagnosis` varchar(20) DEFAULT NULL,
  `medication` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

CREATE TABLE `file_scan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pangea_userid` text DEFAULT NULL,
  `fullname` varchar(100) DEFAULT NULL,
  `title` varchar(200) DEFAULT NULL,
  `file_name` varchar(300) DEFAULT NULL,
  `verdict` varchar(100) DEFAULT NULL,
  `score` varchar(100) DEFAULT NULL,
  `timing` varchar(30) DEFAULT NULL,
  `summary` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;
