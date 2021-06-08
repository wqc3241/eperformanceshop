jQuery(document).ready(function ($) {
    "use strict";
    
    if ($('.famiau-slider-for').length) {
        $('.famiau-slider-for').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: false,
            fade: true,
            asNavFor: '.famiau-slider-nav'
        });
    }
    
    if ($('.famiau-slider-nav').length) {
        $('.famiau-slider-nav').slick({
            slidesToShow: 5,
            slidesToScroll: 1,
            asNavFor: '.famiau-slider-for',
            dots: false,
            focusOnSelect: true,
            slidesMargin: 20
        });
    }
    
    // Listing contact form
    $(document).on('submit', '.famiau-listing-contact-wrap form.wpcf7-form', function (e) {
        famiau_update_listing_cf7();
    });
    $(document).on('change', '.famiau-listing-contact-wrap form.wpcf7-form', function () {
        famiau_update_listing_cf7();
    });
    
    function famiau_update_listing_cf7() {
        if (!$('.famiau-listing-contact-wrap form.wpcf7-form').length) {
            return false;
        }
        $('.famiau-listing-contact-wrap form.wpcf7-form').each(function () {
            var $thisForm = $(this);
            var $thisFormWrap = $thisForm.closest('.famiau-listing-contact-wrap');
            var famiau_cf7_info = $thisFormWrap.attr('data-famiau_cf7_info');
            console.log(famiau_cf7_info);
            $thisForm.find('[name="famiau-content"]').val(famiau_cf7_info);
        });
    }
    
    famiau_update_listing_cf7();
    
});