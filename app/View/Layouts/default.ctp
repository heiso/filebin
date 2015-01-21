<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
	<meta name="keywords" content="">
	<meta name="author" content="Alexandre Moatty">
	<meta http-equiv="Content-Type" content="text/html">
	<title><?php echo $title_for_layout; ?></title>
	<link rel="shortcut icon" href="<?php echo $this->Html->url('/img/favicon.ico'); ?>" />
    <link rel="stylesheet" media="screen" href="<?php echo $this->Html->url('/css/bootstrap.css'); ?>" />
    <link rel="stylesheet" media="screen" href="<?php echo $this->Html->url('/css/bootstrap-override.css'); ?>" />
    <link rel="stylesheet" media="screen" href="<?php echo $this->Html->url('/css/styles-common.css'); ?>" />
    <link rel="stylesheet" media="screen" href="<?php echo $this->Html->url('/css/styles.css'); ?>" />
	<?php echo $this->fetch('css'); ?>
	<?php echo $this->Html->script('jquery-1.8.0.min'); ?>
	<?php echo $this->Html->script('jquery-ui-1.8.23.custom.min'); ?>
</head>
<body>
	<?php echo $this->Session->flash(); ?>
	<div class="panel cache">
		<div class="panel-close icon2-cancel"></div>
		<div class="title">
			<p><span class="panel-icon icon2-doc-text"></span>Liste des liens : </p>
		</div>
		<textarea class="expand"></textarea>
		<div class="buttons">
			<a class="copy-all">Tout selectionner</a>
		</div>
	</div>
	<div class="logo">
		<span class="icon2-cloud"></span>
		<h1><?php echo $this->Html->link('FileBin', '/'); ?><small>Beta</small></h1>
	</div>
	<?php echo $this->fetch('content'); ?>
	<footer>
		<p><?php /*<?php echo $this->Html->link('About', array('controller' => 'pages', 'action' => 'about'), array('class' => 'about')); ?> -*/?> By <a href="http://alexandremoatty.fr" target="_blank" >Alexandre Moatty</a></p>
	</footer>
	<?php //echo $this->element('debug'); ?>
	<?php echo $this->Html->script('scripts'); ?>
	<?php echo $this->fetch('script'); ?>
</body>
</html>