'use strict';

jQuery(document).ready(function ($) {
	$('.wp-color-picker-field').wpColorPicker();

    //add chooser
    $(".chosen-select").chosen({});

	// Switches option sections
	$('.customtaxfilterinadmin_setting_group').hide();
	var activetab = '';
	if (typeof(localStorage) != 'undefined') {
		activetab = localStorage.getItem("customtaxfilterinadminatab");
	}

	//if url has section id as hash then set it as active or override the current local storage value
	if(window.location.hash){
		if($(window.location.hash).hasClass('customtaxfilterinadmin_setting_group')){
			activetab = window.location.hash;

			if (typeof(localStorage) != 'undefined' ) {
				localStorage.setItem("customtaxfilterinadminatab", activetab);
			}
		}
	}

	if (activetab != '' && $(activetab).length && $(activetab).hasClass('customtaxfilterinadmin_setting_group')) {
			$(activetab).fadeIn();
	} else {
		$('.customtaxfilterinadmin_setting_group:first').fadeIn();
	}

	$('.customtaxfilterinadmin_setting_group .collapsed').each(function () {
		$(this).find('input:checked').parent().parent().parent().nextAll().each(
			function () {
				if ($(this).hasClass('last')) {
					$(this).removeClass('hidden');
					return false;
				}
				$(this).filter('.hidden').removeClass('hidden');
			});
	});




	if (activetab != '' && $(activetab + '-tab').length) {
		$(activetab + '-tab').addClass('nav-tab-active');
	}
	else {
		$('.nav-tab-wrapper a:first').addClass('nav-tab-active');
	}


	$('.nav-tab-wrapper a').click(function (evt) {
		evt.preventDefault();

		$('.nav-tab-wrapper a').removeClass('nav-tab-active');
		$(this).addClass('nav-tab-active').blur();
		var clicked_group = $(this).attr('href');
		if (typeof(localStorage) != 'undefined') {
			localStorage.setItem("customtaxfilterinadminatab", $(this).attr('href'));
		}
		$('.customtaxfilterinadmin_setting_group').hide();
		$(clicked_group).fadeIn();

	});

	$('.wpsa-browse').on('click', function (event) {
		event.preventDefault();

		var self = $(this);

		// Create the media frame.
		var file_frame = wp.media.frames.file_frame = wp.media({
			title: self.data('uploader_title'),
			button: {
				text: self.data('uploader_button_text')
			},
			multiple: false
		});

		file_frame.on('select', function () {
			var attachment = file_frame.state().get('selection').first().toJSON();
			self.prev('.wpsa-url').val(attachment.url);
		});

		// Finally, open the modal
		file_frame.open();
	});


	//make the subheading single row
	$('.setting_subheading').each(function (index, element) {
		var $element = $(element);
		var $element_parent = $element.parent('td');
		$element_parent.attr('colspan', 2);
		$element_parent.prev('th').remove();
	});

	//make the subheading single row
	$('.setting_heading').each(function (index, element) {
		var $element = $(element);
		var $element_parent = $element.parent('td');
		$element_parent.attr('colspan', 2);
		$element_parent.prev('th').remove();
	});

	$('.customtaxfilterinadmin_setting_group').each(function (index, element) {
		var $element = $(element);
		var $form_table = $element.find('.form-table');
		$form_table.prev('h2').remove();
	});

	$('.taxsetting_table').each(function (index, element) {
		var $element = $(element);
		var $element_parent = $element.parent('td');
		$element_parent.attr('colspan', 2);
		$element_parent.prev('th').remove();
	});

});
