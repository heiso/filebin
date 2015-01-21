<?php if(!empty($error)) : ?>
	<tr class="item error">
		<td class="select"></td>
		<td class="name"><?php echo $error; ?></td>
		<td class="action"><span class="icon2-attention"></span></td>
	</tr>
<?php else : ?>
	<tr class="item" id="<?php echo $v['File']['id']; ?>">
		<td class="select"><span class="fadeIn animated icon2-check"></span></td>
		<?php if(strlen($v['File']['basename']) > 40 ) : ?>
			<td class="name"><?php echo substr($v['File']['basename'], 0, 40); ?>...</td>
		<?php else : ?>
			<td class="name"><?php echo $v['File']['basename']; ?></td>
		<?php endif; ?>
		<td class="action">
			<span class="flipInX animated">
				<a class="icon-link icon" title="Lien" href="<?php echo Router::url('/show/'.$v['File']['ref']); ?>" target="_blank"><span class="cache">Lien</span></a>
				<a class="cache icon-docs copy icon" title="Copier le lien" href="<?php echo Router::url('/show/'.$v['File']['ref'], true); ?>"><span class="cache">Copier le lien</span></a>
				<a class="icon-pencil icon" title="Editer" href="<?php echo Router::url('/edit/'.$v['File']['ref']); ?>"><span class="cache">Edit</span></a>
				<a class="del icon-trash icon" title="Supprimer" href="<?php echo Router::url('/files/delete/'.$v['File']['ref']); ?>"><span class="cache">Suppr</span></a>
			</span>
		</td>
	</tr>
<?php endif; ?>