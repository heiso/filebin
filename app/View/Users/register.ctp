<?php $this->Html->css('bootstrap', null, array('inline'=>false)); ?>
<?php $this->Html->css('bootstrap-override', null, array('inline'=>false)); ?>
<?php $this->Html->css('styles-backoffice', null, array('inline'=>false)); ?>

<div id="login" class="modal">
	<div class="modal-header">
		<h2>Inscription</h2>
	</div>
	<?php echo $this->Form->create('User', array(
		'inputDefaults' => array(
			'label' => false,
			'div' => array('class' => 'input')
		)
	)); ?>
	<div class="modal-body">
		<div class="clearfix">
			<?php echo $this->Form->label('mail', 'E-mail <span class="required">*</span>'); ?>
			<?php echo $this->Form->input('mail'); ?>
		</div>
		<div class="clearfix">
			<?php echo $this->Form->label('password', 'Mot de passe <span class="required">*</span>'); ?>
			<?php echo $this->Form->input('password'); ?>
		</div>
		<div class="clearfix">
			<?php echo $this->Form->label('password2', 'Confirmation du mot de passe <span class="required">*</span>'); ?>
			<?php echo $this->Form->input('password2', array('type' => 'password')); ?>
		</div>
		<div class="clearfix">
			<div class="input">
				<span class="required">* : Champs obligatoires</span>
			</div>
		</div>
	</div>
	<?php echo $this->Form->end(array('label' => 'Se connecter', 'class' => 'btn primary', 'div' => array('class' => 'modal-footer'))); ?>
</div>