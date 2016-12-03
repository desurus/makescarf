<?php
/**
 * @version 0.1
 * @author Shell
 * This module provide a functionality to create and store, manipulate with a content backups. 
 * */
namespace Pure\Module\Backup;
class Module extends \Pure\Module {
	public function _init() {
		
	}
	public function maybe_backup_file($file) {
		if(!($file instanceof \PureLib\FS\Node\Node)) {
			if(!is_string($file)) throw new \Pure\Exception(__("The source file for backup must be a valid string. Or File node.", WAMT_DOMAIN));
			$file = new \PureLib\FS\Node\File($file);
		}
		if(!$file->exists()) {
			throw new \Pure\Exception(__("Can not create a backup of unexistent file.", WAMT_DOMAIN));
		}
		$data_directory = PM()->data_directory();
		var_dump($data_directory); exit();	
		return true;
	}
}
