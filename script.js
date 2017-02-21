jQuery(document).ready(function($){




	// declare hex containers
	// declare logo hex\black or white

	function apply_theme(){

		console.log('Applying Theme.');

		color_logo();
		color_backgrounds();
		color_nav_items();
		color_socials();
		color_work_tiles();

	}

	apply_theme();

	function color_logo(){
		// get logo fill color from #logo-color element
		//	apply it to the svg's fill which has class .logo-fill
		var logo_color = '#'+$('#logo-color').attr('data-hex');
		$('.logo-fill').attr('fill', logo_color);
	}

	function color_socials(){
		var socials_color = '#'+$('#nav-items-color').attr('data-hex');
		$('.social-fills').attr('fill', socials_color);
	}

	function color_backgrounds(){
		var background_colors = $('[data-bgc]');
		// console.log(background_colors.length+' background colors found. Changing colors on a case to case basis.');

		background_colors.each(function(){
			var bgc = '#'+$(this).attr('data-bgc');
			if(bgc){
				$(this).css({
					'background-color': bgc
				});
			}
		});
	}

	function color_nav_items(){
		var nav_items = $('#nav-bar .menu-item a, .work-footer .nav-btn, .work-footer-heading');
		var text_color = '#'+$('#nav-items-color').attr('data-hex');
		// console.log(nav_items.length+' nav items found. Changing to color: '+text_color);

		nav_items.each(function(){
			$(this).css({
				'color': text_color
			});
		});
	}

	function color_work_tiles(){
		var view_work_spans = $('.a-home-tile .view-work');
		// console.log(nav_items.length+' nav items found. Changing to color: '+text_color);

		view_work_spans.each(function(){
			// console.log(typeof($(this)));


			var text_color = '#'+$(this).data('hex');
			// console.log($(this));

			$(this).css({
				'color': text_color
			});
		});
	}





});