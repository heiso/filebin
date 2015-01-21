$(function(){
	$(window).ready(function(){
		
		if(window.addEventListener) {
			var kkeys = [], konami = "38,38,40,40,37,39,37,39,66,65";
			window.addEventListener("keydown", function(e){
				kkeys.push(e.keyCode);
				if(kkeys.toString().indexOf(konami) >= 0) {
					$('body').prepend('<img class="kc" src="img/kc.png" alt="Konami Code" style="position:fixed;z-index:999999;top:50%;margin-top:-37px;left:150%"/>');
					$('.kc').animate({left:'-50%'}, 5000, function(){
						$(this).remove();
					});
					kkeys = [];
				}
			}, true);
		}
	
	});
});