<tr class="item" id="<?php echo $v['File']['id']; ?>">
	<td class="select"></td>
	<?php if(strlen($v['File']['basename']) > 40 ) : ?>
		<td class="name"><?php echo substr($v['File']['basename'], 0, 40); ?>...</td>
	<?php else : ?>
		<td class="name"><?php echo $v['File']['basename']; ?></td>
	<?php endif; ?>
	<td class="size"><?php echo $this->Number->toReadableSize($v['File']['size']); ?></td>
	<td class="created"><?php echo date('d.m.Y H:i', strtotime($v['File']['created'])); ?></td>
	<td class="created">
		<?php if($v['File']['expires']) : ?>
			<?php echo date('d.m.Y', strtotime($v['File']['expires'])); ?>
		<?php else: ?>
			-
		<?php endif; ?>
	</td>
	<td class="downloaded"><?php echo $v['File']['downloaded'].'/'.$v['File']['download_limit']; ?></td>
	<td class="action">
		<a class="icon-copy copy btn primary" href="<?php echo Router::url('/show/'.$v['File']['ref'], true); ?>">Copier le lien</a>
		<a class="icon-link btn primary" href="<?php echo Router::url('/show/'.$v['File']['ref']); ?>" target="_blank">Lien</a>
	</td>
</tr>