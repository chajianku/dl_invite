<?php if (!defined('SYSTEM_ROOT')) { die('Insufficient Permissions'); } 

function callback_init() {
	global $m;
	$m->query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX."dl_invite` (
  `id` int(10) NOT NULL,
  `code` varchar(100) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
	option::set('dl_intive_enable',1);
	
	}
function callback_inactive() {
	global $m;
	option::set('dl_intive_enable','');
	$m->query('DROP TABLE `'.DB_NAME.'`.`'.DB_PREFIX.'dl_invite`');
}
?>