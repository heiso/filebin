<?php
if(Configure::read('Media.formats')){
	foreach(Configure::read('Media.formats') as $file => $formats){
		Router::connect(':file_:format.jpg', array('controller' => 'medias', 'action' => 'crop', 'plugin' => 'media' ),array(
		  'file' => $file,
		  'format' => implode('|', $formats)
		));
	}
}
Router::connect('/media/medias/*',array('controller'=>'medias','action'=>'blocked','plugin'=>'media'));