<div class="modal static link">
	<div class="modal-header">
		<h1><?php echo $file['File']['basename'].' <small>'.$this->Number->toReadableSize($file['File']['size']).'</small>'; ?></h1>
	</div>
	<div class="modal-body">
		<?php echo $this->Form->create('File', array(
			'inputDefaults' => array(
				'label' => false,
				'div' => array('class' => 'input')
			)
		)); ?>
			<div class="clearfix">
				<?php echo $this->Form->label('expires', 'Date d\'expiration'); ?>
				<?php echo $this->Form->input('expires', array('value' => date('Y-m-d', strtotime($file['File']['expires'])), 'class' => 'datepicker', 'type' => 'text')); ?>
			</div>
			<div class="clearfix">
				<?php echo $this->Form->label('download_limit', 'Nombre de vue limitée'); ?>
				<?php echo $this->Form->input('download_limit'); ?>
			</div>
			<?php echo $this->Form->input('id'); ?>
		<?php echo $this->Form->end(array('label' => 'Enregistrer', 'class' => 'btn primary', 'style' => 'width:100%;')); ?>
	</div>
</div>

<?php echo $this->Html->css('jquery-ui-1.9.0.custom', null, array('inline'=>false)); ?>
<script type="text/javascript">
jQuery(function(){

	var advanced = $('.advanced');
	var advanced_button = $('.open-advanced');
	var input_date = $('input.datepicker');

	advanced_button.on('click', function(e){
		e.preventDefault();
		advanced.toggleClass('open', 500);
	});

	$(document).ready(function() {
		input_date.dp({
			dateFormat: 'dd.mm.yy',
			altFormat: 'yy-mm-dd',
			minDate: new Date()
		});
	});

	$.widget( "ui.dp", {
        _create: function() {
            var el = this.element.hide();
            this.options.altField = el;
            var input = this.input = $('<input>').insertBefore( el )
            input.focusout(function(){
                    if(input.val() == ''){
                        el.val('');
                    }
                });
            input.datepicker(this.options)
            if(convertDate(el.val()) != null){
                this.input.datepicker('setDate', convertDate(el.val()));
            }
        },
        destroy: function() {
            this.input.remove();
            this.element.show();
            $.Widget.prototype.destroy.call( this );
        }
    });
   
    var convertDate = function(date){
      if(typeof(date) != 'undefined' && date != null && date != ''){
        return new Date(date);
      } else {
        return null;
      }
    }

});
</script>