<?php

namespace PureLib\FS\Node;
class Directory extends Node {	
	public function get_child_nodes() {
		if(!$this->exists() || !$this->is_readable()) throw new \Exception("Can not get child nodes. FS not readable in path ``{$this->get_fullpath()}``.");
		if($this->opened()) {
			$nodes = array();
			$handle = $this->get_handle();
			while(($node = readdir($handle))) {
				if($node == '.' || $node == '..') continue;
				$node_fullpath = self::trailingslashit($this->get_fullpath()) . $node;
				if(is_dir($node_fullpath)) $node = new Directory($node_fullpath);
				else $node = new File($node_fullpath);
				$nodes[] = $node;
			}	
		}
		return $nodes;	
	}
	public function get_child_nodes_recursive() {
		$result = array();
		$nodes = $this->get_child_nodes();
		foreach($nodes as $node) {
			$result[] = $node;
			if(($node instanceof Directory)) {
				$subnodes = $node->get_child_nodes_recursive();
				foreach($subnodes as $subnode) {
					$result[] = $subnode;
				}
			}
		}	
		return $result;
	}
	/**
	 * This method return a new instance of Directory node, for existing directory, or creates a new one...
	 * @return \PureLib\FS\Node\Directory 
	 * */
	public function maybe_create_directory($name) {
		$new_path = self::trailingslashit($this->get_fullpath()) . $name;
		if(is_dir($new_path)) return new self($new_path);
		$created = @mkdir($new_path);
		if(!$created) {
			throw new \Exception("Seems we can not create a directory ``{$new_path}``");
		}	
		return new self($new_path);
	}

	public function opened() {	
		if(null === $this->_handle) $this->_open();
		if(is_resource($this->_handle)) return true;
		return false;
	}
	protected function _open() {
		$this->_handle = @opendir($this->get_fullpath());
	}
}
