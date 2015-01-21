<?php
class PagesController extends AppController {

	function home() {
		$data['title_for_layout'] = 'Filebin - Accueil';
		$this->set($data);
	}

	function about() {
		$data['title_for_layout'] = 'FileBin - A propos';
		$this->set($data);
	}

	function admin_home() {
		
	}
	
}
