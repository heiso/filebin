<?php
class FilesController extends AppController {

	public $components = array();
	public $helper = array('QrCode');

	private $ext_unauth = array('css');
	private $max_size = '52428800';

	function beforeFilter(){
        parent::beforeFilter(); 
        if(in_array($this->request->action, array('upload', 'admin_upload', 'admin_delete')) && array_key_exists('Security', $this->components)){
            $this->Security->validatePost = false;
            $this->Security->csrfCheck = false;
        }
    }

    function upload() {
		$this->set($this->do_upload());
		$render = $this->render('file');
		die($render);
	}

	function index() {
		$data['files'] = $this->File->find('all', array(
			'conditions' => array(
				'ref' => $this->get_session()
			)
		));
		$data['title_for_layout'] = 'FileBin';
		$this->set($data);
	}

	function show($ref = null) {
		if(!$ref)
			throw new NotFoundException();
		$file = $this->File->find('first', array(
			'conditions' => array(
				'ref' => $ref
			)
		));
		if(!empty($file)) {
			// $this->check_password($file);
			if($file['File']['downloaded'] >= $file['File']['download_limit'] && $file['File']['download_limit'] != 0) {
				$this->File->delete($file['File']['id']);
				$file = false;
			} elseif(time() >= strtotime($file['File']['expires'])+86400 && strtotime($file['File']['expires']) != 0) {
				$this->File->delete($file['File']['id']);
				$file = false;
			}
		}
		$data['file'] = $file;
        $data['title_for_layout'] = 'FileBin';
		$this->set($data);
	}

	function download($ref = null, $fd = true) {
		if(!$ref)
			throw new NotFoundException();
		$file = $this->File->find('first', array(
			'conditions' => array(
				'ref' => $ref
			)
		));
		$this->File->incr_download($file['File']['id']);
		$this->viewClass = 'Media';
        $params = array(
            'id'        => $file['File']['basename'],
            'name'      => $file['File']['name'],
            'download'  => $fd,
            'extension' => $file['File']['ext'],
            'path'      => APP.'uploads'.DS.$file['File']['dir']
        );
        $this->set($params);
	}

	function edit($ref = null) {
		$session = $this->Session->read();
		if(empty($session['Uploaded']) || !in_array($ref, $session['Uploaded']))
			throw new NotFoundException();
		if($this->request->is('put') || $this->request->is('post')) {
			if($this->File->save($this->request->data)) {
				$this->Session->setFlash('Fichier édité', 'notif', array('type' => 'success'));
				$this->redirect($this->referer());
			}
		} elseif($ref) {
			$data['file'] = $this->request->data = $this->File->find('first', array(
				'conditions' => array('ref' => $ref)
			));
			$this->set($data);
		}
	}

	function delete($ref = null) {
		$session = $this->Session->read();
		if(empty($session['Uploaded']) || !in_array($ref, $session['Uploaded']))
			throw new NotFoundException();
		$file = $this->File->find('first', array('conditions' => array('ref' => $ref)));
		if(!empty($file)) {
			$this->File->delete($file['File']['id']);
			$this->remove_session($file['File']['ref']);
			$this->Session->setFlash('Fichier supprimé', 'notif', array('type' => 'success'));
		} else {
			$this->Session->setFlash('Erreur lors de la suppression', 'notif', array('type' => 'error'));	
		}
		$this->redirect($this->referer());
	}

    function admin_index() {
		$data['files'] = $this->File->find('all');
		$this->set($data);
    }

    function admin_list() {
		$data['files'] = $this->File->find('all');
		$this->set($data);
    }

	function admin_upload() {
		$this->set($this->do_upload());
		$render = $this->render('file');
		die($render);
	}

	function admin_delete() {
		if(!$this->request->data['File']['files'])
			throw new NotFoundException();
		$files = json_decode($this->request->data['File']['files']);
		if(empty($files))
			throw new NotFoundException();
		foreach ($files as $k=>$v) {
			$this->File->delete($v);
		}
		$this->redirect($this->referer());
	}

	function do_upload() {
		if(!empty($_FILES['file']['tmp_name'])) {
			$upload = $_FILES['file'];
			$infosupload = pathinfo($upload['name']);
			if($_FILES['file']['size'] <= $this->max_size) {
				if(!in_array(strtolower($infosupload['extension']), $this->ext_unauth)) {
					$name = $infosupload['filename'];
					$path = APP.'uploads';
					if(!is_dir($path))
						mkdir($path,0777);
					$path .= DS.date('mY', time());
					if(!is_dir($path))
						mkdir($path,0777);
					$file = $name.'.'.$infosupload['extension'];
					$i = 2;
					while(file_exists($path.'/'.$file)) {
						$file = $name.'-'.$i.'.'.$infosupload['extension'];
						$i ++;
					}
					$url = $path.'/'.$file;
					if(move_uploaded_file($upload['tmp_name'], $url)) {
						if(empty($_POST['expires'])) {
							$_POST['expires'] = null;
						}
						if(empty($_POST['download_limit'])) {
							$_POST['download_limit'] = 0;
						}
 						$this->request->data['File']['file'] = str_replace(APP.'uploads'.DS, '', $url);
						$this->request->data['File']['ref'] = substr(md5('sy9jv8usd4mv5ksuv'.uniqid(rand(), true)), 0, 10);
						$this->request->data['File']['created'] = date('Y-m-d H:i:s');
						$this->request->data['File']['expires'] = $_POST['expires'];
						$this->request->data['File']['download_limit'] = $_POST['download_limit'];
						$this->request->data['File']['size'] = $upload['size'];
						$this->File->save($this->request->data);
						$data['v'] = $this->File->read();
						$this->add_session($data['v']['File']['ref']);
					} else {
						$error = 'Erreur lors du telechargement du fichier';
					}
				} else {
					$error = 'Extension non prise en charge';
				}
			} else {
				$error = 'La taille du fichier ne doit pas dépasser '.$this->max_size.' Octets';
			}
		} else {
			$error = 'Erreur inconnue';
		}
		if(!empty($error)) {
			$data['error'] = $error;
		}
		$this->layout = false;
		return $data;
	}

	function get_session() {
		if($this->Session->check('Uploaded')) {
			$session = $this->Session->read();
			return $session['Uploaded'];
		}
	}

	function add_session($ref) {
		if($this->Session->check('Uploaded')) {
			$session = $this->Session->read();
			array_push($session['Uploaded'], $ref);
			$this->Session->delete('Uploaded');
			$this->Session->write('Uploaded', $session['Uploaded']);
		} else {
			$this->Session->write('Uploaded', array($ref));
		}
	}

	function remove_session($ref) {
		if($this->Session->check('Uploaded')) {
			$session = $this->Session->read();
			$session['Uploaded'] = array_diff($session['Uploaded'], array($ref));
			$this->Session->delete('Uploaded');
			$this->Session->write('Uploaded', $session['Uploaded']);
		}
	}

	// function check_password($file) {
	// 	if(empty($file['File']['password'])) {
	// 		return true;
	// 	} else {

	// 	}
	// }
	
}