<div class="modal static upload">
	<div class="modal-header">
		<h1>Ajouter un fichier <small>< 50Mo</small></h1>
	</div>
	<div class="modal-body">
		<a class="icon-cog open-advanced"><span class="cache">Menu avancé</span></a>
		<div class="advanced">
			<?php echo $this->Form->create('File', array(
				'inputDefaults' => array(
					'label' => false,
					'div' => array('class' => 'input')
				)
			)); ?>
			<div class="clearfix">
				<?php echo $this->Form->label('expires', 'Date d\'expiration'); ?>
				<?php echo $this->Form->input('expires', array('value' => date('Y-m-d', mktime(0, 0, 0, date('m')+1, date('d'), date('Y'))), 'class' => 'datepicker', 'type' => 'text', 'after' => '<span>(jj.mm.aaaa)</span>')); ?>
			</div>
			<div class="clearfix">
				<?php echo $this->Form->label('download_limit', 'Nombre de vue limitée'); ?>
				<?php echo $this->Form->input('download_limit', array('value' => '0', 'after' => '<span>(0 : pas de limite)</span>')); ?>
			</div>
			<?php echo $this->Form->end(array('label' => 'Se connecter', 'class' => 'cache')); ?>
		</div>
		<div id="plupload">
			<span class="icon-upload"></span>
			<p>ou</p>
			<a id="browse" class="btn" href="#">Parcourir</a>
		</div>
	</div>
	<div class="modal-footer">
		<table class="zebra-striped" id="filelist">
			<tbody>
				<?php if(!empty($files)) : ?>
					<?php foreach($files as $v) : ?>
						<?php require('file.ctp'); ?>
					<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
		</table>
	</div>
</div>

<?php echo $this->Html->css('jquery-ui-1.9.0.custom', null, array('inline'=>false)); ?>
<?php $this->Html->script('jquery.form',array('inline'=>false)); ?>
<?php $this->Html->script('jquery.textareaAutoResize',array('inline'=>false)); ?>
<?php $this->Html->script('plupload/plupload', array('inline'=>false)); ?>
<?php $this->Html->script('plupload/plupload.html5', array('inline'=>false)); ?>
<?php $this->Html->script('plupload/plupload.flash', array('inline'=>false)); ?>
<?php $this->Html->script('jquery.hoverIntent.minified', array('inline'=>false)); ?>
<script type="text/javascript">
jQuery(function(){

	var filelist = $('#filelist tbody');
	var theFrame = $("#uploaderframe", parent.document.body);
	var droparea = $('#plupload');
	var panel = $('.panel');
	var textarea = panel.find('textarea').val('');
	var urls = new Array();
	var expire = 0;

	init();

	function init() {
		$('a.copy').live('click', function(e){
			e.preventDefault();
			window.prompt('Ctrl+C, Enter', $(this).attr('href'));
		});

		window.addEventListener('keydown', function(e){
			if(e.keyCode == '37')
				url_list();
		}, true);

		$('.open-panel').on('click', function(){
			url_list();
		});

		$('a.del').live('click', function(){
			if(confirm('Voulez vous vraiment supprimer ce fichier ?')) {
				return true;
			} else {
				return false
			}
		});

		init_uploader();
		init_advanced();
	}

	function init_advanced() {
		var advanced = $('.advanced');
		var advanced_button = $('.open-advanced');
		var input_date = $('input.datepicker');

		advanced_button.on('click', function(e){
			e.preventDefault();
			if(!advanced.hasClass('open')) {
				advanced_button.addClass('animate-spin active');
				advanced.addClass('open', 500, function(){
					advanced_button.removeClass('animate-spin');
				});
			} else {
				advanced_button.addClass('animate-spin');
				advanced.removeClass('open', 500, function(){
					advanced_button.removeClass('animate-spin active');
				});
			}
		});

		$(document).ready(function() {
			input_date.dp({
				dateFormat: 'dd.mm.yy',
				altFormat: 'yy-mm-dd',
				minDate: new Date()
			});
		});

		// var now = new Date();
		// current = new Date(now.getFullYear(), now.getMonth()+1, now.getDate());
		// datepicker.attr('value', current);

		$.widget("ui.dp", {
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
	}

	function init_uploader() {
		var uploader = new plupload.Uploader({
			runtimes : 'html5,flash',
			container: 'plupload',		
			browse_button : 'browse',
			flash_swf_url : '<?php echo Router::url('/js/plupload/plupload.flash.swf'); ?>',
			url : '<?php echo Router::url('/upload'); ?>',
			drop_element : 'plupload',
			max_file_size : '50mb',
			multipart:true,
			urlstream_upload:true
		});

		uploader.init();

		uploader.bind('FilesAdded', function(up, files) {
			for (var i in files) {
				filelist.append('\
					<tr class="item" id="' + files[i].id + '">\
						<td><span class="icon-loader animate-spin"></span><span class="progress"></span></td>\
						<td></span>'+files[i].name.substr(0,15)+'... ('+plupload.formatSize(files[i].size)+')</td>\
						<td></td>\
					</tr>'
				);
			}
			uploader.settings.multipart_params = {
				'expires' : $('#FileExpires').val(),
				'download_limit' : $('#FileDownloadLimit').val()
			};
			uploader.start();
			droparea.parents('.modal-body').removeClass('dropping'); 
			theFrame.css({ height:$('body').height() + 117 });
		});

		uploader.bind('UploadProgress', function(up, file) {
			$('#'+file.id).find('.progress').text(file.percent+'%');
			console.log(file.percent+' - '+uploader.total.bytesPerSec);
		});

		uploader.bind('FileUploaded', function(up, file, response){
			$('#'+file.id).after(response.response);
			$('#'+file.id).remove();
		});

		uploader.bind('UploadComplete', function(up, file, response){
			url_list();
		});

		droparea.bind({
	       dragover : function(e){
	           droparea.parents('.modal-body').addClass('dropping');
	       },
	       dragleave : function(e){
	           droparea.parents('.modal-body').removeClass('dropping'); 
	       },
	       mouseleave : function(e){
	           droparea.parents('.modal-body').removeClass('dropping'); 
	       }
		});
	}

	function url_list() {
		var items = filelist.find('.item');

		open();
		add_to_textarea();

		$('a.copy-all').live('click', function(e){
			e.preventDefault();
			textarea.focus();
			textarea.select();
		});

		function open() {
			panel.show().animate({left:0});
			panel.find('.panel-close').on('click', function(){
				close();
			});
		}

		function close() {
			panel.animate({left:'-360px'}, function(){
				panel.hide();
				textarea.val('');
				urls = new Array();
			});
		}

		function add_to_textarea() {
			items.each(function(){
				if($(this).find('.copy').length != 0) {
					var url = $(this).find('.copy').attr('href');
					var val = textarea.val();

					if($.inArray(url, urls) == '-1') {
						textarea.val(val+url+'\n').autoResize();
						urls.push(url);
					}
				}
			});
		}
	}

});
</script>