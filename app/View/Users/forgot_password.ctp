<?php $this->Html->css('bootstrap', null, array('inline'=>false)); ?>
<?php $this->Html->css('bootstrap-override', null, array('inline'=>false)); ?>
<?php $this->Html->css('styles-backoffice', null, array('inline'=>false)); ?>

<div id="login" class="modal">
	<div class="modal-header">
		<h2>Mot de passe oublié</h2>
	</div>
	<?php echo $this->Form->create('User', array(
		'inputDefaults' => array(
			'label' => false,
			'div' => array('class' => 'input')
		)
	)); ?>
	<div class="modal-body">
		<div class="clearfix">
			<?php echo $this->Form->label('mail', 'E-mail'); ?>
			<?php echo $this->Form->input('mail'); ?>
		</div>
	</div>
	<?php echo $this->Form->end(array('label' => 'Envoyer', 'class' => 'btn primary', 'div' => array('class' => 'modal-footer'))); ?>
</div>