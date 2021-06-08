jQuery(document).ready(function ($) {
    "use strict";
    
    
    // Delete listing
    $(document).on('click', '.famiau-all-my-listings .famiau-delete-listing', function (e) {
        var $this = $(this);
        var $thisTr = $this.closest('tr');
        var listing_id = $this.attr('data-listing_id');
        
        if ($thisTr.is('.processing')) {
            return false;
        }
        
        var c = confirm(famiau['text']['confirm_del_my_listing']);
        if (!c) {
            return false;
        }
        
        $thisTr.addClass('processing');
        
        var data = {
            action: 'famiau_delete_my_listing_via_ajax',
            listing_id: listing_id,
            nonce: famiau['security']
        };
        
        $.post(famiau['ajaxurl'], data, function (response) {
            
            if (response['err'] == 'no') {
                $thisTr.remove();
            }
            else {
                famiau_display_multi_messages($thisTr.find('.famiau-imgs-td', response, 'bottom'));
            }
            $thisTr.removeClass('processing');
            
        });
        
        e.preventDefault();
        return false;
    });
    
    // Change the listing status to sold
    $(document).on('click', '.famiau-all-my-listings .famiau-to-sold-listing', function (e) {
        var $this = $(this);
        var $thisTr = $this.closest('tr');
        var listing_id = $this.attr('data-listing_id');
        
        if ($thisTr.is('.processing')) {
            return false;
        }
        
        var c = confirm(famiau['text']['confirm_sold_my_listing']);
        if (!c) {
            return false;
        }
        
        $thisTr.addClass('processing');
        
        var data = {
            action: 'famiau_to_sold_my_listing_via_ajax',
            listing_id: listing_id,
            nonce: famiau['security']
        };
        
        $.post(famiau['ajaxurl'], data, function (response) {
            
            console.log(response);
            if (response['err'] == 'no') {
                $thisTr.find('.famiau-status-td .famiau-listing-status').replaceWith(response['new_listing_status_html']);
            }
            else {
                famiau_display_multi_messages($thisTr.find('.famiau-imgs-td', response, 'bottom'));
            }
            $thisTr.removeClass('processing');
            
        });
        
        e.preventDefault();
        return false;
    });
    
    /**
     *
     * @param $form
     * @param response
     * @param position  top or bottom.
     */
    function famiau_display_multi_messages($form, response, position) {
        $form.find('.famiau-message').remove();
        
        var msg_class = '';
        
        if (response['err'] === 'yes') {
            msg_class += 'alert-danger notice notice-error';
        }
        else {
            msg_class += 'alert-success updated notice notice-success';
        }
        
        if ($.type(response['message']) === 'string') {
            if (response['message'] !== '') {
                if (position === 'top') {
                    $form.prepend('<div class="famiau-message alert ' + msg_class + '"><p>' + response['message'] + '</p></div>');
                }
                else {
                    $form.append('<div class="famiau-message alert ' + msg_class + '"><p>' + response['message'] + '</p></div>');
                }
            }
        }
        else {
            $.each(response['message'], function (index, item) {
                if (position === 'top') {
                    $form.prepend('<div class="famiau-message alert ' + msg_class + '"><p>' + item + '</p></div>');
                }
                else {
                    $form.append('<div class="famiau-message alert ' + msg_class + '"><p>' + item + '</p></div>');
                }
            });
        }
    }
    
    // Login via ajax
    $(document).on('submit', 'form[name="famiau_login"]', function (e) {
        var $thisForm = $(this);
        var $thisFormWrap = $thisForm.parent();
        var username = $thisForm.find('input[name="username"]').val();
        var password = $thisForm.find('input[name="password"]').val();
        var rememberme = $thisForm.find('input[name="rememberme"]').is(':checked') ? $thisForm.find('input[name="rememberme"]').val() : '';
        var nonce = $thisForm.find('input[name="famiau-login-nonce"]').val();
        var err = false;
        
        if ($thisFormWrap.is('.processing')) {
            return false;
        }
        
        if ($.trim(username) == '') {
            $thisForm.find('input[name="username"]').addClass('famiau-error');
            err = true;
        }
        else {
            $thisForm.find('input[name="username"]').removeClass('famiau-error');
        }
        
        if ($.trim(password) == '') {
            $thisForm.find('input[name="password"]').addClass('famiau-error');
            err = true;
        }
        else {
            $thisForm.find('input[name="password"]').removeClass('famiau-error');
        }
        
        if (err) {
            return false;
        }
        
        $thisFormWrap.addClass('processing');
        
        var data = {
            action: 'famiau_login_via_ajax',
            username: username,
            password: password,
            rememberme: rememberme,
            nonce: nonce
        };
        
        $.post(famiau['ajaxurl'], data, function (response) {
            
            console.log(response);
            $thisFormWrap.removeClass('processing');
            if (response['err'] == 'no') {
                $thisFormWrap.html('');
                famiau_display_multi_messages($thisFormWrap, response, 'bottom');
                location.reload();
            }
            else {
                famiau_display_multi_messages($thisForm, response, 'bottom');
            }
            
        });
        
        return false;
    });
    
    // Register via ajax
    $(document).on('submit', 'form[name="famiau_register"]', function (e) {
        var $thisForm = $(this);
        var $thisFormWrap = $thisForm.parent();
        var username = $thisForm.find('input[name="famiau_username"]').val();
        var email = $thisForm.find('input[name="famiau_email"]').val();
        var password = $thisForm.find('input[name="famiau_password"]').val();
        var cf_password = $thisForm.find('input[name="famiau_cf_password"]').val();
        var nonce = famiau['security'];
        var err = false;
        
        if ($thisFormWrap.is('.processing')) {
            return false;
        }
        
        if ($.trim(username) == '') {
            $thisForm.find('input[name="famiau_username"]').addClass('famiau-error');
            err = true;
        }
        else {
            $thisForm.find('input[name="famiau_username"]').removeClass('famiau-error');
        }
        
        if ($.trim(email) == '') {
            $thisForm.find('input[name="famiau_email"]').addClass('famiau-error');
            err = true;
        }
        else {
            $thisForm.find('input[name="famiau_email"]').removeClass('famiau-error');
        }
        
        if (password == '') {
            $thisForm.find('input[name="famiau_password"]').addClass('famiau-error');
            err = true;
        }
        else {
            $thisForm.find('input[name="famiau_password"]').removeClass('famiau-error');
        }
        
        if (password !== cf_password || cf_password == '') {
            $thisForm.find('input[name="famiau_password"]').addClass('famiau-error');
            $thisForm.find('input[name="famiau_cf_password"]').addClass('famiau-error');
            err = true;
        }
        else {
            $thisForm.find('input[name="famiau_password"]').removeClass('famiau-error');
            $thisForm.find('input[name="famiau_cf_password"]').removeClass('famiau-error');
        }
        
        if (err) {
            return false;
        }
        
        $thisFormWrap.addClass('processing');
        
        var data = {
            action: 'famiau_reg_new_user_via_ajax',
            username: username,
            email: email,
            password: password,
            cf_password: cf_password,
            nonce: nonce
        };
        
        $.post(famiau['ajaxurl'], data, function (response) {
            
            console.log(response);
            $thisFormWrap.removeClass('processing');
            if (response['err'] == 'no') {
                $thisFormWrap.html('');
                famiau_display_multi_messages($thisFormWrap, response, 'bottom');
                location.reload();
            }
            else {
                famiau_display_multi_messages($thisForm, response, 'bottom');
            }
            
        });
        
        return false;
    });
    
});
