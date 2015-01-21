<div class="page-header">
	<h1>User</h1>
</div>
<div class="row">
	<div class="span16">
		<h2>Modifier utilisateur</h2>
		<?php echo $this->Form->create('User', array(
			'inputDefaults' => array(
				'label' => false,
				'div' => array('class' => 'input')
			)
		)); ?>
			<div class="clearfix">
				<?php echo $this->Form->label('mail', 'E-mail'); ?>
				<?php echo $this->Form->input('mail'); ?>
			</div>
			<div class="clearfix">
				<?php echo $this->Form->label('password', 'Changez de Mot de passe'); ?>
				<?php echo $this->Form->input('password'); ?>
			</div>
			<div class="clearfix">
				<?php echo $this->Form->label('password2', 'Confirmer le nouveau mot de passe'); ?>
				<?php echo $this->Form->input('password2', array('type' => 'password')); ?>
			</div>
			<?php echo $this->Form->input('id'); ?>
		<?php echo $this->Form->end(array('label' => 'Enregistrer', 'class' => 'btn primary', 'style' => 'width:100%;')); ?>
	</div>
</div>