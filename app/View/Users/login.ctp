<?php $this->Html->css('bootstrap', null, array('inline'=>false)); ?>
<?php $this->Html->css('bootstrap-override', null, array('inline'=>false)); ?>
<?php $this->Html->css('styles', null, array('inline'=>false)); ?>

<div id="login" class="modal static">
	<div class="modal-header">
		<h2>Login</h2>
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
		<div class="clearfix">
			<?php echo $this->Form->label('password', 'Mot de passe'); ?>
			<?php echo $this->Form->input('password'); ?>
		</div>
		<div class="clearfix">
			<div class="input">
				<?php echo $this->Html->link('Mot de passe oublié', array('action'=>'forgot_password', 'controller'=>'users')); ?>
			</div>
		</div>
	</div>
	<?php echo $this->Form->end(array('label' => 'Se connecter', 'class' => 'btn primary', 'div' => array('class' => 'modal-footer'))); ?>
</div>