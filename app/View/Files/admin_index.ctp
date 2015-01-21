<div class="page-header">
	<h1>Files</h1>
</div>
<div class="row">
	<div class="span16">
		<h2>Listing des fichiers</h2>
		<div class="sidebar">
			<div class="count"><span class="selected"></span>/<?php echo count($files); ?></div>
			<div class="sep"></div>
			<a class="selectall btn" href="">Tous selectionner</a>
			<div class="sep"></div>
			<a class="unselectall btn" href="">Tous deselectionner</a>
			<div class="sep"></div>
			<input class="search" type="text"/>
			<div class="sep"></div>
			<?php echo $this->Form->create('File', array(
				'action' => 'delete',
				'class' => 'ajax-submit'
			)); ?>
				<?php echo $this->Form->hidden('files', array('class' => 'ajax-filled')); ?>
				<a class="del btn primary" href="">Supprimer</a>
			<?php echo $this->Form->end(array('label' => 'del', 'class' => 'cache')); ?>
		</div>
		<table class="zebra-striped" id="filelist">
			<tbody>
				<?php require('admin_list.ctp'); ?>
			</tbody>
		</table>
	</div>
</div>

<?php $this->Html->script('jquery.form',array('inline'=>false)); ?>
<script type="text/javascript">
jQuery(function(){

	/*
	* Search
	*/
	var input = $('input.search');
	var list = $('table tbody tr');
	var search = new Array();

	list.each(function(){
		var elmnt = $(this);
		search.push(elmnt.find('td.name').text());
	});
	input.autocomplete({
		source: search,
		minLength: 1,
		open: function(event, ui){
			update = setInterval(function(){search_update()}, 250);
		},
		change: function(event, ui){
			clearInterval(update);
		},
		close: function(event, ui){
			clearInterval(update);
		}
	});
	input.autocomplete('widget').addClass('search');
	input.keyup(function(){
		if($(this).val() == "") {
			list.show();
		}
	});

	function search_update() {
		results = new Array();
		$('ul.search a').each(function(){
			var text = $(this).text();
			text = text.split(' - ');
			results.push(text[0]);
		});
		if(results.length != 0) {
			list.each(function(){
				var elmnt = $(this);
				elmnt.hide();
				if($.inArray(elmnt.find('.name').text(), results) != -1) {
					elmnt.show();
				}
			});
			clearInterval(update);
			$('ul.search').empty();
		}
	}

	/**
	* Filelist
	**/

	var filelist = $('#filelist tbody');
	var items = filelist.find('.item');
	var theFrame = $("#uploaderframe", parent.document.body);
	// var droparea = $('#droparea');
	var sidebar = $('.sidebar');
	var files = new Array();

	init();

	function init() {
		$('.ajax-submit .del').on('click', function(e){
			e.preventDefault();
			var button = $(this);
		 	var form = button.parents('form');
		 	var input = form.find('.ajax-filled');

		 	if(files.length > 0) {
			 	input.val(JSON.stringify(files));
			 	
			 	if(button.hasClass('del')) {
			 		if(confirm('Voulez vous vraiment supprimer ce fichier ?')) {
			 			button.prepend('<span class="icon-loader animate-spin">');
			 			form.ajaxSubmit(function(){
					 		reload(function(){button.find('.icon-loader').remove();});
					 	});		
			 		}
			 	}
			 	
			 }
		});

		$('.selectall').on('click', function(e){
			e.preventDefault();
			select_all();
		});

		$('.unselectall').on('click', function(e){
			e.preventDefault();
			unselect_all();
		});

		var init_top = sidebar.offset().top-40;
		pos_sidebar();
		$(window).scroll(function(){
			pos_sidebar();
		});

		function pos_sidebar() {
			if($(window).scrollTop() >= init_top)
				sidebar.addClass('floatable');
			else if(sidebar.position().top != init_top)
				sidebar.removeClass('floatable');
		}

		// init_uploader();
		init_filelist();
	}

	function init_uploader() {
		var uploader = new plupload.Uploader({
			runtimes : 'html5,flash',
			container: 'plupload',		
			browse_button : 'browse',
			flash_swf_url : '<?php echo Router::url('/js/plupload/plupload.flash.swf'); ?>',
			url : '<?php echo Router::url(array('controller'=>'files','action'=>'upload','admin'=>true)); ?>',
			drop_element : 'droparea',
			max_file_size : '50mb',
			multipart:true,
			urlstream_upload:true
		});

		uploader.init();

		uploader.bind('FilesAdded', function(up, files) {
			for (var i in files) {
				filelist.append('\
					<tr class="item" id="' + files[i].id + '">\
						<td></td>\
						<td><span class="icon-loader animate-spin"></span></td>\
						<td>'+files[i].name+' ('+plupload.formatSize(files[i].size)+')</td>\
						<td></td>\
					</tr>'
				);
			}
			uploader.start();
			droparea.removeClass('dropping'); 
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
			reload();
		});

		droparea.bind({
	       dragover : function(e){
	           $(this).addClass('dropping'); 
	       },
	       dragleave : function(e){
	           $(this).removeClass('dropping'); 
	       }
		});
	}

	function init_filelist() {
		items = filelist.find('.item');
		var items_bind = items.find('.select, .visu, .name');
		unselect_all();

		sidebar.find('.count').text('0/'+items.size());

		items_bind.on('click', function(){
			var item = $(this).parents('.item');
			var id = item.attr('id');

			if($.inArray(id, files) == -1) {
				files.push(id);
				sidebar.find('.count').text(files.length+'/'+items.size());
				item.addClass('active');
			} else {
				files.splice(files.indexOf(id), 1);
				sidebar.find('.count').text(files.length+'/'+items.size());
				item.removeClass('active');
			}
		});

		$('a.copy').on('click', function(e){
			e.preventDefault();
			window.prompt('Ctrl+C, Enter', $(this).attr('href'));
		});
	}

	function reload(callback) {
		$.get('<?php echo Router::url(array('controller'=>'files','action'=>'list')); ?>', {}, function(data){
			filelist.empty().append(data);
			init_filelist();
			if(typeof(callback) == 'function')
				callback();
		});
	}

	function unselect_all() {
		items.removeClass('active');
		files = new Array();
		$('.ajax-submit .ajax-filled').val('');
		sidebar.find('.count').text(files.length+'/'+items.size());
	}

	function select_all() {
		items.each(function(){
			var item = $(this);
			var id = item.attr('id');

			item.addClass('active');
			files.push(id);
		});
		sidebar.find('.count').text(files.length+'/'+items.size());
	}

});
</script>