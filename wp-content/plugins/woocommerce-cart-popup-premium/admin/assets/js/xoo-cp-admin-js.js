jQuery(document).ready(function($){

	$(function(){
		$('.color-field').wpColorPicker();
	})

	//Sidebar JS
	$(function(){
		var show_class 	= 'xoo-sidebar-show';
		var sidebar 	= $('.xoo-sidebar');
		var togglebar 	= $('.xoo-sidebar-toggle');

		//Show / hide sidebar
		if(localStorage.xoo_admin_sidebar_display){
			if(localStorage.xoo_admin_sidebar_display == 'shown'){
				sidebar.removeClass(show_class);
			}
			else{
				sidebar.addClass(show_class);
			}
			on_sidebar_toggle();
		}

		togglebar.on('click',function(){
			sidebar.toggleClass(show_class);
			on_sidebar_toggle();
		})

		function on_sidebar_toggle(){
			if(sidebar.hasClass(show_class)){
				togglebar.text('Show');
				var display = "hidden";
			}else{
				togglebar.text('Hide');
				var display = "shown";
			}
			localStorage.setItem("xoo_admin_sidebar_display",display);
		}
	});


	//Tabs change
	$('.xoo-tabs li').on('click',function(){
		var tab_class = $(this).attr('class').split(' ')[0];
		$('li').removeClass('active-tab');
		$('.settings-tab').removeClass('settings-tab-active');
		$(this).addClass('active-tab');
		var class_c = $('[tab-class='+tab_class+']').attr('class');
		$('[tab-class='+tab_class+']').attr('class',class_c+' settings-tab-active');
	})

	//Remove Image
	$('.xoo-remove-media').click(function(e){
		e.preventDefault();
		$('#xoo-cp-ad-sy-cbimg').val('');
		$('.xoo-media-name').html('');

	})

	//Media name
	function xoo_medianame(){
		var image_url = $('#xoo-cp-ad-sy-cbimg').val();
		var index = image_url.lastIndexOf('/') + 1;
		var image_name = image_url.substr(index);
		$('.xoo-media-name').html(image_name);
		return image_name;
	}
	xoo_medianame();

	//Media uploader
	var xoo_media;
	$('#xmedia-btn').on('click',function(e){
		e.preventDefault();
		if(xoo_media){
			xoo_media.open();
			return;
		}
		xoo_media = wp.media.frames.file_frame = wp.media({
			title: 'Select Image',
			button: {
				text: 'Choose Image'
			},
			multiple: false
		});

		xoo_media.on('select',function(){
			attachment = xoo_media.state().get('selection').first().toJSON();
			console.log(attachment);
			var allowed_types = ['jpeg','jpg','png'];
			if(allowed_types.indexOf(attachment.subtype) === -1){
				alert('Only jpeg/jpg & png allowed.');
				return false;
			}
			$('#xoo-cp-ad-sy-cbimg').val(attachment.url);
			 xoo_medianame();
		})
		xoo_media.open();
	})
})