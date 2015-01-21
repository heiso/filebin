<?php
class Media extends AppModel{

	public $useTable = 'medias';
	public $order    = 'position ASC';

	// Allowed extensions and associated type
	private $exts = array(
		"jpg" => "pic",
		"gif" => "pic",
		"png" => "pic",
		"avi" => "movie",
		"mov" => "movie",
		"mkv" => "movie",
		"mp4" => "movie",
		"wmv" => "movie",
		"mp3" => "music",
		"wma" => "music",
		"zip" => "zip",
		"tar.gz"=> "zip",
		"tgz" => "zip",
		"rar" => "zip"
	);

	function beforeDelete($cascade = true){
		$file = $this->field('file');
		$info = pathinfo($file);
		foreach(glob(WWW_ROOT.$info['dirname'].'/'.$info['filename'].'_*x*.jpg') as $v){
			unlink($v);
		}
		unlink(WWW_ROOT.trim($file,'/'));
		return true;
	}

	function afterFind($data, $primary = false){
		foreach($data as $k=>$v){
			$i = key($v);
			$v = current($v);
			if(isset($v['file'])){
				$data[$k][$i]['filef'] = substr($v['file'],0,-4).'_%dx%d.jpg';
				$pathinfo = pathinfo($v['file']);
				$extension= strtolower($pathinfo['extension']);

				if(in_array($extension, array_keys($this->exts))){
					$data[$k][$i]['type'] = $this->exts[$extension];
					$data[$k][$i]['icon'] = 'Media.' . $this->exts[$extension] . '.png';
				}
				if($data[$k][$i]['type'] == 'pic'){
					$data[$k][$i]['icon'] = $v['file'];
				}
			}
		}
		return $data;
	}

	function beforeSave($options = array()){
		if( isset($this->data['Media']['file']) && is_array($this->data['Media']['file']) && isset($this->data['Media']['ref']) ){
			$model 		= ClassRegistry::init($this->data['Media']['ref']);
			$dir 		= $model->medias['path'];
			$ref_id 	= $this->data['Media']['ref_id'];
			$pathinfo 	= pathinfo($this->data['Media']['file']['name']);
			$filename 	= Inflector::slug($pathinfo['filename'],'-');
			$search 	= array('%id','%mid','%cid','%y','%m','%f');
			$replace 	= array($ref_id,ceil($ref_id/1000),ceil($ref_id/100),date('Y'),date('m'),Inflector::slug($filename));
			$dir  		= str_replace($search,$replace,$dir).'.'.$pathinfo['extension'];
			$this->testDuplicate($dir);
			if(!file_exists(dirname(WWW_ROOT.$dir))){
				mkdir(dirname(WWW_ROOT.$dir),0777,true);
			}
			move_uploaded_file($this->data['Media']['file']['tmp_name'], WWW_ROOT.$dir);
			chmod(WWW_ROOT.$dir,0777);
			$this->data['Media']['file'] = '/' . trim($dir, '/');
		}
		return true;
	}

	/**
	* If the file $dir already exists we add a {n} before the extension
	**/
	function testDuplicate(&$dir,$count = 0){
		$file = $dir;
		if($count > 0){
			$pathinfo = pathinfo($dir);
			$file = $pathinfo['dirname'].'/'.$pathinfo['filename'].'-'.$count.'.'.$pathinfo['extension'];
		}
		if(!file_exists(WWW_ROOT.$file)){
			$dir = $file;
		}else{
			$count++;
			$this->testDuplicate($dir,$count);
		}
	}

}