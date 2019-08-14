<?php
$installer=$this;
$installer->startSetup();

$installer->run("
-- DROP TABLE IF EXISTS {$this->getTable('rejuni_optimizer')};
CREATE TABLE {$this->getTable('rejuni_optimizer')} (
  `rejuni_optimizer_id` int(11) unsigned NOT NULL auto_increment COMMENT 'Q&A ID',
  `image_name` varchar(255) NOT NULL COMMENT 'Image Name',
  `image_path` varchar(255) NOT NULL  COMMENT 'Image Path',
  `filesize_before` text NOT NULL  COMMENT 'Filesize Before',
  `filesize_after` text NOT NULL  COMMENT 'Filesize After',
  `created_time` datetime NULL,
  `update_time` datetime NULL,
  PRIMARY KEY (`custommodule_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
$installer->endSetup();
?>