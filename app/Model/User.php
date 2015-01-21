<?php 
class User extends AppModel {
	
	public $actsAs = array('Containable'); 
	public $recursive = -1;
	public $belongsTo = array('Role');
    public $validate = array(
        'mail' => array(
        	array(
	        	'rule' => 'isUnique',
				'allowEmpty' => false,
	        	'required' => true,
				'message' => 'Cette adresse email est déjà utilisée'
			),
			array(
				'rule' => 'email',
				'message' => 'L\'adresse email n\'est pas valide' 
			),
			array(
				'rule' => array('maxLength', 100),
				'message' => '100 charactères max autorisés' 
			)
        ),
		'password' => array(
			array(
				'rule' => 'notEmpty',
				'allowEmpty' => false,
				'required' => true,
				'message' => 'Le mot de passe n\'est pas valide'
			),
			array(
				'rule' => array('validatePassword'),
				'message' => '',
			)
		)
    );

    public function setValidation($rule) {
    	switch ($rule) {
    		case 'edit':
    			$new_validate = array(
					'password' => array(
						'rule' => array('validatePassword'),
						'message' => ''
					)
		        );
		        $this->validate = array_merge($this->validate, $new_validate);
    			break;
    		default:
    			break;
    	}
    }

	public function validatePassword() {
		if(empty($this->data['User']['password'])) {
			unset($this->data['User']['password']);
			return true;
		} else if(isset($this->data['User']['password']) && isset($this->data['User']['password2'])) {
			if($this->data['User']['password'] == $this->data['User']['password2'])
				return true;
			else
				$this->validationErrors['password2'] = array('Les mots de passe ne correspondent pas');
		}
		else
			return true;
	}
	
	public function beforeSave($options = array()) {
		if(!empty($this->data['User']['password']))
			$this->data['User']['password'] = AuthComponent::password($this->data['User']['password']);
		return true;
	}
	
	public function afterFind($data, $primary = false) {
		foreach($data as $k=>$v) {
			if(!empty($v['Role']['slug'])) {
				$data[$k]['User']['role'] = $v['Role']['slug'];
				$data[$k]['User']['role_weight'] = $v['Role']['weight'];
			}
		}
		return $data;
	}
	
}