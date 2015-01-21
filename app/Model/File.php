<?php 
class File extends AppModel {
	
	public $actsAs = array(
		'Containable'
	);
	public $recursive = -1;

	public function beforeDelete($cascade = true) {
		$file = $this->find('first', array(
			'conditions' => array('id' => $this->id),
			'fields' => array('file')
		));
		if(!empty($file['File']['file'])) {
			App::uses('Folder', 'Utility');
			$dir = APP.'uploads';
			$file = $dir.DS.$file['File']['file'];
			$folder = new Folder($dir);
			if(file_exists($file)) {
				unlink($file);
				if($folder->dirsize() == 0)
					$folder->delete();
			}
		}
		return true;
	}

	public function afterFind($data, $primary = false) {
		foreach($data as $k=>$v) {
			if(!empty($v['File']['file'])) {
				$data[$k]['File']['basename'] = basename($v['File']['file']);
		        $data[$k]['File']['ext'] = substr(strtolower(strrchr($data[$k]['File']['basename'], '.')), 1);
		        $data[$k]['File']['name'] = str_replace('.'.$data[$k]['File']['ext'], '', $data[$k]['File']['basename']);
		        $data[$k]['File']['dir'] = str_replace($data[$k]['File']['basename'], '', $data[$k]['File']['file']);
				switch (true) {
					case preg_match('/jpeg|jpg|png|bmp|gif/', $data[$k]['File']['ext']):
						$type = 'image';
						break;
					case preg_match('/doc|docx/', $data[$k]['File']['ext']):
						$type = 'doc';
						break;
					case preg_match('/css|js/', $data[$k]['File']['ext']):
						$type = 'web';
						break;
					
					default:
						$type = 'unknown';
						break;
				}
				$data[$k]['File']['type'] = $type;
			}
		}
		return $data;
	}

	public function incr_download($id) {
		$sql = 'UPDATE files SET downloaded = downloaded + 1 WHERE id = "'.$id.'"';
		return current($this->query($sql));
	}
	
}