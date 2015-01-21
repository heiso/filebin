<?php
$page = $this->request->params['controller'].'_'.$this->request->params['action'];
$active = 'class = "active"';
$inactive = '';
?>
<ul class="nav">
	<li <?php echo ($page == 'pages_home') ? $active : $inactive; ?> ><?php echo $this->Html->link('Accueil', array('action'=>'home', 'controller'=>'pages', 'admin' => true)); ?></li>
	<li <?php echo ($page == 'files_index') ? $active : $inactive; ?> ><?php echo $this->Html->link('Fichiers', array('action'=>'index', 'controller'=>'files', 'admin' => true)); ?></li>
</ul>