<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
	<meta name="keywords" content="">
	<meta name="author" content="Alexandre Moatty">
	<meta http-equiv="Content-Type" content="text/html">
	<title><?php echo $title_for_layout; ?></title>
	<link rel="shortcut icon" href="<?php echo $this->Html->url('/img/favicon.ico'); ?>" />
	<link rel="stylesheet" media="screen" href="<?php echo $this->Html->url('/css/styles-common.css'); ?>" />
	<?php echo $this->fetch('css'); ?>
	<?php echo $this->Html->script('jquery-1.8.0.min'); ?>
	<?php echo $this->Html->script('jquery-ui-1.8.23.custom.min'); ?>
</head>
<body>
	<?php echo $this->Session->flash(); ?>
	<?php echo $this->fetch('content'); ?>
	<?php echo $this->fetch('script'); ?>
</body>
</html>