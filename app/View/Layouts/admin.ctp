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
    <link rel="stylesheet" media="screen" href="<?php echo $this->Html->url('/css/styles-backoffice.css'); ?>" />
	<?php echo $this->fetch('css'); ?>
	<?php echo $this->Html->script('jquery-1.8.0.min'); ?>
	<?php echo $this->Html->script('jquery-ui-1.8.23.custom.min'); ?>
</head>
<body>
	<?php echo $this->Session->flash(); ?>
	<div class="goto-header"><a href="#header"></a></div>
	<div class="topbar">
		<div class="fill">
			<div class="container">
				<?php echo $this->Html->link('Administration', '/admin', array('class' => 'brand')); ?>
				<div class="pull-right">
					<ul>
						<?php if(AuthComponent::user('id')) : ?>
							<li><?php echo $this->Html->link('Se deconnecter',array('controller' => 'users', 'action' => 'logout', 'admin' => false)); ?></li>
						<?php endif; ?>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="content">
			<?php echo $this->fetch('content'); ?>
		</div>
		<footer>
			<p>Webmaster <a href="http://alexandremoatty.fr" target="_blank" >Alexandre Moatty</a></p>
		</footer>
	</div>
	<?php echo $this->fetch('script'); ?>
</body>
</html>