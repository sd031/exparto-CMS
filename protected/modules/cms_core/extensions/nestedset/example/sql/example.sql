--
-- Table structure for table `tree`
--

CREATE TABLE IF NOT EXISTS `tree` (
  `id` int(11) NOT NULL auto_increment,
  `lft` int(11) NOT NULL,
  `rgt` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `name` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `lft` (`lft`),
  KEY `rgt` (`rgt`),
  KEY `level` (`level`),
  KEY `name` (`name`)
) 

-- When using MySQL: add this:
-- ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `tree`
--

INSERT INTO `tree` (`id`, `lft`, `rgt`, `level`, `name`) VALUES
(1, 0, 1, 0, 'Root');