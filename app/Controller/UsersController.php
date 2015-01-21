<?php
class UsersController extends AppController{

	function register() {
		$this->layout = 'empty';
		if($this->request->is('put') || $this->request->is('post')) {
			$this->request->data['User']['active'] = 0;
			$this->request->data['User']['token'] = md5(uniqid(rand(),true));
			if($this->User->save($this->request->data)) {
				$user = $this->request->data;
				unlink($user['User']['password']);
				$this->send_mail_verif($user);
				$this->Session->setFlash('Vous êtes inscrit, un mail d\'activation devrait vous parvenir d\'ici peu', 'notif', array('type' => 'success'));
				$this->redirect('/');
			}
		}
		$data['title_for_layout'] = 'Objectif Photos - Inscription';
		$this->set($data);
	}

	function login() {
		$this->layout = 'empty';
		if($this->request->is('post')) {
			if($this->Auth->login()) {
				$this->User->id = $this->Auth->user('id');
				$this->User->saveField('last_login',date('Y-m-d H:i:s')); 
				$this->Session->setFlash('Vous êtes maintenant connecté', 'notif');
				$this->redirect($this->Auth->redirect());
			}
			else {
				$this->Session->setFlash('Votre identifiant ou votre mot de passe est incorrect', 'notif', array('type'=>'error'));
			}
		}
		$data['title_for_layout'] = 'Objectif Photos - Connexion';
		$this->set($data);
	}

	function activate($id = null, $token = null) {
		if(!$id || !$token)
			throw new NotFoundException();
		$user = $this->User->find('first', array(
			'conditions' => array('active' => '0', 'id' => $id, 'token' => $token))
		);
		if(!empty($user)) {
			$user['User']['active'] = '1';
			$user['User']['last_login'] = date('Y-m-d H:i:s');
			$user['User']['token'] = '';
			$this->Auth->login($user['User']);
			$this->User->save($user);
			$this->Session->setFlash('Votre compte est maintenant activé', 'notif');
			$this->redirect('/');
		} else {
			throw new NotFoundException();
		}
	}

	function forgot_password() {
		$this->layout = 'empty';
		if($this->request->is('post')) {
			$mail = $this->request->data['User']['mail'];
			$user = $this->User->find('first', array('conditions' => array('mail' => $mail)));
			if(!empty($user)) {
				$user['User']['token'] = md5(uniqid(rand(),true));
				$this->User->save($user);
				$this->send_mail_forgot($mail, $user['User']['token']);
				$this->Session->setFlash('Mail envoyé', 'notif');
			}
			else {
				$this->Session->setFlash('Cet identifiant n\'existe pas', 'notif', array('type'=>'error'));
			}
		}
		$data['title_for_layout'] = 'Objectif Photos - Mot de passe oublié';
		$this->set($data);
	}

	function forgot_password_login($id = null, $timestamp = null, $token = null) {
		if(!$id || !$token || !$timestamp || $timestamp+1800 < time())
			throw new NotFoundException();
		$user = $this->User->find('first', array(
			'conditions' => array('id' => $id, 'token' => $token)
		));
		if(!empty($user)) {
			$user['User']['last_login'] = date('Y-m-d H:i:s');
			$user['User']['token'] = '';
			$this->Auth->login($user['User']);
			$this->User->save($user);
			$this->Session->setFlash('Vous pouvez maintenant choisir un nouveau mot de passe pour votre compte', 'notif');
			$this->redirect(array('controller' => 'users', 'action' => 'edit'));
		} else {
			throw new NotFoundException();
		}
	}

	function send_mail_verif($user = null) {
		if(!$user)
			throw new NotFoundException();
		$from = 'inscription@objectif-photos.com';
		$to = $user['User']['mail'];
		$subject = '[Filebin] vérification d\'adresse email';

		// App::uses('CakeEmail','Network/Email');
		// $mail = new CakeEmail();
		// $mail->from($from)
		// 	->to($to)
		// 	->subject($subject)
		// 	->emailFormat('html')
		// 	->template('user_verif')
		// 	->viewVars(array('user' => $user))
		// 	->send();
	}

	function send_mail_forgot($mail = null, $token = null) {
		if(!$mail || !$token)
			throw new NotFoundException();
		$from = 'inscription@objectif-photos.com';
		$to = $mail;
		$subject = '[Filebin] récupération de mot de passe';

		// App::uses('CakeEmail','Network/Email');
		// $mail = new CakeEmail();
		// $mail->from($from)
		// 	->to($to)
		// 	->subject($subject)
		// 	->emailFormat('html')
		// 	->template('user_reinit_pwd')
		// 	->viewVars(array('mail' => $mail, 'hash' => $hash))
		// 	->send();
	}

	/**
	* USER
	**/

	function logout() {
		$this->Auth->logout();
		$this->Session->setFlash('Vous êtes maintenant déconnecté', 'notif');
		$this->redirect('/');
	}

	// function user_edit() {
	// 	$session = $this->Session->read();
	// 	if(empty($session['Auth']['User']))
	// 		throw new NotFoundException();
	// 	if($this->request->is('put') || $this->request->is('post')) {
	// 		$this->User->setValidation('edit');
	// 		if($this->User->save($this->request->data)) {
	// 			$this->Session->setFlash('L\'utilisateur à bien été modifié', 'notif', array('type' => 'success'));
	// 			$this->redirect(array('action' => 'edit'));
	// 		}
	// 	} else {
	// 		$this->request->data = $this->User->find('first', array(
	// 			'conditions' => array('User.id' => $session['Auth']['User']['id'])
	// 		));
	// 		unset($this->request->data['User']['password']);
	// 	}
	// }

	/**
	* ADMIN
	**/

	function admin_index() {
		$data['users'] = $this->User->find('all');
		$this->set($data);
	}

	function admin_show($id = null) {
		if(!$id)
			throw new NotFoundException();
		$data['user'] = $this->User->find('first', array(
			'conditions' => array('User.id' => $id)
		));
		$this->set($data);
	}

	function admin_edit($id = null) {
		$data['roles'] = $this->User->Role->find('list', array('order' => 'name'));
		$this->set($data);
		if($this->request->is('put') || $this->request->is('post')) {
			$this->User->setValidation('edit');
			if($this->User->save($this->request->data)) {
				$this->Session->setFlash('L\'utilisateur à bien été modifié', 'notif', array('type' => 'success'));
				$this->redirect(array('action' => 'index'));
			}
		}
		elseif($id) {
			$this->request->data = $this->User->find('first', array(
				'contain' => 'Role',
				'conditions' => array('User.id' => $id)
			));
			unset($this->request->data['User']['password']);
		}
	}

	function admin_delete($id = null) {
		if(!$id)
			throw new NotFoundException();
		$this->User->delete($id);
		$this->Session->setFlash('L\'utilisateur a bien été supprimé', 'notif');
		$this->redirect($this->referer());
	}

}
