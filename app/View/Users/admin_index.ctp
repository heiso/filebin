<div class="page-header">
	<h1>Utilisateurs</h1>
</div>
<div class="row">
	<div class="span16">
		<div class="well">
			<?php echo $this->Html->link('Ajouter', array('action'=>'edit', 'admin' => true), array('class' => 'btn primary')); ?>
			<input class="search" type="text"/>
		</div>
		<?php if(!empty($users)) : ?>
			<h3>Tous les utilisateurs</h3>
			<table class="sort zebra-striped">
				<thead>
					<tr>
						<th>Activé</th>
						<th>Email</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($users as $k=>$v) :?>
						<tr>
							<td class="activé" ><?php echo $v['User']['active']; ?></td>
							<td class="mail" ><?php echo $v['User']['mail']; ?></td>
							<td>
								<?php echo $this->Html->link('Modifier', array('action'=>'edit', $v['User']['id'], 'admin' => true), array('class' => 'btn primary')); ?>
								<?php echo $this->Html->link('Supprimer', array('action'=>'delete', $v['User']['id'], 'admin' => true), array('class' => 'btn primary'), 'Voulez vous vraiment supprimer cet utilisateur ?'); ?>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			<?php echo $this->Html->script('jquery.tablesorter.min'); ?>
			<script type="text/javascript">
				$(function() {

					$('.sort').tablesorter();

				});
			</script>
		<?php else : ?>
			<span class="label notice">Aucun utilisateur enregistré</span>
		<?php endif; ?>
	</div>
</div>
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
		search.push(elmnt.find('td.mail').text());
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
				if($.inArray(elmnt.find('.mail').text(), results) != -1) {
					elmnt.show();
				}
			});
			clearInterval(update);
			$('ul.search').empty();
		}
	}

});	
</script>