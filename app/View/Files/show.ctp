<div class="modal static link">
	<?php if(!empty($file['File'])) : ?>
		<div class="modal-header">
			<h1><?php echo $file['File']['basename'].' <small>'.$this->Number->toReadableSize($file['File']['size']).'</small>'; ?></h1>
		</div>
		<div class="modal-body">
	    	<?php echo $this->QrCode->url(Router::url('/download/'.$file['File']['ref'].'-1', true)); ?>
		</div>
		<div class="modal-footer">
	    	<a href="<?php echo Router::url('/download/'.$file['File']['ref'].'-0'); ?>" class="icon-picture btn primary">Afficher</a>
	    	<a href="<?php echo Router::url('/download/'.$file['File']['ref'].'-1'); ?>" class="icon-link btn primary">Télécharger</a>
		</div>
	<?php else : ?>
		<div class="modal-header">
			<h1>Lien périmé</h1>
		</div>
		<div class="modal-body">
	    	<p>Le fichier n'existe plus.</p>
		</div>
	<?php endif; ?>
</div>