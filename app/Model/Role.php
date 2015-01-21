<?php 
class Role extends AppModel {
	
	public $actsAs = array('Containable'); 
	public $hasMany = 'User';
	public $order = 'Role.weight DESC';
	public $recursive = -1;
	
	public function beforeSave($options = array()) {
		if(!empty($this->data['Role']['name']))
			$this->data['Role']['slug'] = strtolower(Inflector::slug($this->data['Role']['name'],'-'));
		return true; 
	}
	
}