<div class="page-header">
	<h1>Utilisateur</h1>
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
				<?php echo $this->Form->label('role_id', 'Role'); ?>
				<?php echo $this->Form->input('role_id'); ?>
			</div>
			<div class="clearfix">
				<?php echo $this->Form->label('active', 'Activé'); ?>
				<?php echo $this->Form->input('active'); ?>
			</div>
			<?php echo $this->Form->input('id'); ?>
		<?php echo $this->Form->end(array('label' => 'Enregistrer', 'class' => 'btn primary', 'style' => 'width:100%;')); ?>
	</div>
</div>