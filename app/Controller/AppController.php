<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller{

	public $helpers = array('Text','Form','Html','Session','Cache');
	public $components = array(
		'Session',
		'Cookie',
		'Security',
		'Auth' => array(
			'authenticate' => array(
				'Form' => array(
					'fields' => array(
						'username' => 'mail',
					)
				)
			)
		)
	);

	function beforeFilter(){
		$this->Auth->loginAction = array('controller'=>'users','action'=>'login','admin'=>false);
		$this->Auth->authorize = array('Controller');

		if(isset($this->request->params['prefix']) && $this->request->params['prefix'] == 'admin') {
            $this->layout = 'admin';
		}
		if(!isset($this->request->params['prefix'])) {
			$this->Auth->allow();
		}

		if($this->request->isAjax()){
			$this->layout = 'ajax';
		}
	}

	function isAuthorized($user){
		if (!isset($this->request->params['prefix'])) {
			return true;
		} elseif ($this->request->params['prefix'] == 'admin' && $user != null && $user['role_weight'] >= 10) {
			return true;
		} elseif ($this->request->params['prefix'] == 'user' && $user != null && $user['role_weight'] >= 5) {
			return true;
		}

		return false;
	}



}
