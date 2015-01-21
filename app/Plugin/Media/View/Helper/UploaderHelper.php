<?php
class UploaderHelper extends AppHelper{

	var $helpers = array('Html','Form');
	var $javascript = false;
	var $explorer = false;

	public function tinymce($field){
		$this->Html->script('/media/js/tinymce/tiny_mce.js',array('inline'=>false));
		return $this->textarea($field, 'tinymce');
	}

	public function ckeditor($field) {
		$model = $this->Form->_models; $model = key($model);
		$this->Html->script('/media/js/ckeditor/ckeditor.js',array('inline'=>false));
		return $this->textarea($field, 'ckeditor');
	}

	public function redactor($field) {
		$model = $this->Form->_models; $model = key($model);
		$this->Html->script('/media/js/redactor/redactor.min.js',array('inline'=>false));
		$this->Html->css('/Media/js/redactor/redactor.css', null, array('inline'=>false));
		return $this->textarea($field, 'redactor');
	}

	public function textarea($field, $editor = false){
		$html = $this->Form->input($field,array('label'=>false,'style'=>'width:100%;height:500px','row' => 160, 'type' => 'textarea', 'class' => "wysiwyg $editor"));
		$models = $this->Form->_models;
		$model = key($models);
        if(isset($this->request->data[$model]['id']) && !$this->explorer){
			$html .= '<input type="hidden" id="explorer" value="' . $this->Html->url('/admin/media/medias/index/'.$model.'/'.$this->request->data[$model]['id']) . '">';
			$this->explorer = true;
    	}
    	return $html;
	}

	public function iframe($ref,$ref_id){
		return '<iframe src="'.Router::url('/').'admin/media/medias/index/'.$ref.'/'.$ref_id.'" style="width:100%;" id="'.$ref.'"></iframe>';
	}
}