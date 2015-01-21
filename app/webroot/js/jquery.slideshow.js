(function($){

	$.fn.slideshow = function(options){

		var settings = {
			html_slide: '\
				<div class="slide">\
					<div class="infos">\
						<div class="title">{{title}}</div>\
						<div class="nbr">{{nbr}} photos</div>\
						<div class="buttons">\
							<a href="#" class="zoom">&#59204;</a>\
						</div>\
					</div>\
					<img src="{{src}}" alt="{{alt}}">\
				</div>',
			zoom_callback: function(){}
		}

		var options = $.extend(settings, options);
		var _this = this;

		$.getScript('js/mustache.js', function(){
			return _this.each(function(){
				var container = $(this);
				var images = container.find('img');
				var nbr = images.length;
				var width = container.width();
				
				var slides = new Array();
				var actual_id_center = 0;
				var slides_max;
				var autoplay;

				if(nbr != 0) {
					init();
					play();
					container.hover(function(){
						pause();
					}, function(){
						play();
					});
					container.live('click', function(e){
						e.preventDefault();
						zoom();
					});
				}

				function init() {
					var i = 0;
					images.each(function(){
						var img = $(this);
						slides.push({
							title: img.attr('alt'),
							src: img.attr('src'),
							alt: img.attr('alt'),
							obj: false,
							nbr: nbr
						});
						img.remove();
						i++;
					});
					slides_max = slides.length-1;
					var slide = $(Mustache.render(options.html_slide, slides[0]));
					container.append(slide);
					slides[0].obj = slide;
					container.hover(function(){
						container.find('.infos').stop().fadeIn(200);
						container.find('.zoom').stop().animate({right: '20px', bottom: '20px'}, 200);
					}, function(){
						container.find('.infos').stop().fadeOut(200);
						container.find('.zoom').stop().animate({right: '-20px', bottom: '-20px'}, 200);
					});
				}

				function goTo(id, actual_id) {
					if(id != actual_id) {
						if(!slides[id].obj) {
							var slide = $(Mustache.render(options.html_slide, slides[id]))
							slides[id].obj = slide;
						}
						slides[id].obj.css({left: width, display: 'block', zIndex: 10}).appendTo(container);
						slides[id].obj.animate({left: 0}, 'fast', function(){
							slides[actual_id].obj.css({zIndex: 0}).hide();
						});
					}
				}

				function prev() {
					if(actual_id_center - 1 < 0)
						id = slides_max;
					else
						id = actual_id_center - 1;
					goTo(id, actual_id_center);
					actual_id_center = id;
				}

				function next() {
					if(actual_id_center + 1 > slides_max)
						id = 0;
					else
						id = actual_id_center + 1;
					goTo(id, actual_id_center);
					actual_id_center = id;
				}

				function pause() {
					clearInterval(autoplay);
					autoplay = 'p';
				}

				function play() {
					autoplay = setInterval(function(){
						next();
					}, 5000);
				}

				function zoom() {
					options.zoom_callback();
				}
			})
		});
	}


})(jQuery);