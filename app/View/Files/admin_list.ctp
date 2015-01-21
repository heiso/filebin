<?php if(!empty($files)) : ?>
	<?php foreach($files as $v) : ?>
		<?php require('admin_file.ctp'); ?>
	<?php endforeach; ?>
<?php endif; ?>