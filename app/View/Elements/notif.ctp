<div id="notifs" class="animated fadeInRight">
	<div class="notif <?php echo !empty($type)?$type:'info'; ?>">
		<div class="left">
			<div class="icon"></div>
		</div>
		<div class="msg">
			<p><?php echo $message; ?></p>
		</div>
		<a class="close icon2-cancel" href="#"></a>
	</div>
</div>


<script type="text/javascript">
	$(function(){
		
		$(document).ready(function(){
			var notifs = $('#notifs');

			notifs.find('.close').on('click', function(e){
				e.preventDefault();

				notifs.fadeOut(function(){
					notifs.remove();
				});
			});
		});

	});
</script>