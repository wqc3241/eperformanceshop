jQuery(document).ready(function ($) {
    "use strict";
    
    // Account tabs nav
    $(document).on('click', '.famiau-account-nav-link a', function (e) {
        var $this = $(this);
        var $thisTabsNav = $this.closest('.famiau-tabs-nav');
        var target = $this.attr('href');
        if (typeof target != 'undefined' && typeof target != false) {
            $thisTabsNav.find('.active').removeClass('active');
            $this.parent().addClass('active');
            if ($(target).length && !$(target).is('.active')) {
                $('.famiau-acc-tab-content').removeClass('active');
                $(target).addClass('active');
                e.preventDefault();
                return false;
            }
        }
    });
    
    // Update models select by selected make
    function famiau_update_models_select() {
        if ($('select[name="_famiau_make"]').length && $('select[name="_famiau_model"]').length) {
            var models = $('select[name="_famiau_make"] option:selected').attr('data-models');
            var models_select_html = '<option data-model="" value="">' + famiau['text']['select_model'] + '</option>';
            if ($.trim(models) != '') {
                models = JSON.parse(models);
                for (var i = 0; i < models.length; i++) {
                    models_select_html += '<option data-model="' + models[i] + '" value="' + models[i] + '">' + models[i] + '</option>';
                }
            }
            $('select[name="_famiau_model"]').html(models_select_html);
        }
    }
    
    famiau_update_models_select();
    
    $(document).on('change', 'select[name="_famiau_make"]', function () {
        famiau_update_models_select();
    });
    
    // Date time
    $('.famiau-date-field').datepicker({});
    
    // Min/Max select number
    function famiau_min_max_select_number(is_selected_max) {
        $('.famiau-select-min-max-group').each(function () {
            var $this = $(this);
            var $minSelect = $this.find('.famisp-select-num-min');
            var $maxSelect = $this.find('.famisp-select-num-max');
            var min_val = parseFloat($minSelect.val());
            var max_val = parseFloat($maxSelect.val());
            
            if (isNaN(min_val)) {
                min_val = 0;
            }
            if (isNaN(max_val)) {
                max_val = min_val;
            }
            
            // $maxSelect.attr('min', min_val);
            
            if (max_val < min_val) {
                if (is_selected_max) {
                    min_val = max_val;
                }
                else {
                    max_val = min_val;
                }
            }
            $minSelect.val(min_val);
            $maxSelect.val(max_val);
            
        });
    }
    
    famiau_min_max_select_number();
    
    $(document).on('change', '.famiau-select-min-max-group select', function () {
        var is_selected_max = $(this).is('.famisp-select-num-max');
        famiau_min_max_select_number(is_selected_max);
    });
    
    // Submit dropdown filter (old) ----------------
    $(document).on('click', '.famiau-listting-dropdown-wrap .famiau-submit-filter-btn', function (e) {
        var $this = $(this);
        var $thisFilter = $this.closest('.famiau-listting-dropdown-wrap');
        var filters = {};
        $thisFilter.find('.famiau-field').each(function () {
            var filter_key = $(this).attr('name');
            if (typeof filter_key != 'undefined' && typeof filter_key != false) {
                if ($.trim(filter_key) != '') {
                    filters[filter_key] = $(this).val();
                }
            }
        });
        
        var data = {
            action: 'famiau_dropdown_filter_frontend',
            filters: filters,
            nonce: famiau['security']
        };
        
        $.post(famiau['ajaxurl'], data, function (response) {
            
            if (response['err'] != 'yes') {
                $('.famiau-listing-content-wrap').html(response['html']);
            }
            else {
            
            }
            
        });
        
        e.preventDefault();
    });
    
    /* CAR MEGA FILTER ====================================== */
    $(document).on('click', '.famiau-mega-filter-wrap .famiau-filter-item', function (e) {
        var $this = $(this);
        if ($this.is('select')) {
            return false;
        }
        var $thisFiltering = $this.closest('.famiau-criteria-filtering');
        if ($this.is('.famiau-active-filter')) {
            $thisFiltering.find('.famiau-filter-item').removeClass('famiau-active-filter');
            return false;
        }
        if ($thisFiltering.closest('.famiau-filter-menu').length) {
            $thisFiltering.closest('.famiau-filter-menu').find('.famiau-filter-item').removeClass('famiau-active-filter');
            $thisFiltering.closest('.parent').find('> .famiau-filter-item').addClass('famiau-active-filter');
        }
        $thisFiltering.find('.famiau-filter-item').removeClass('famiau-active-filter');
        $this.addClass('famiau-active-filter');
        e.preventDefault();
        return false;
    });
    $(document).on('change', '.famiau-mega-filter-wrap select', function (e) {
        var $this = $(this);
        var this_val = $this.val();
        
        if ($this.is('.famiau-filter-item')) {
            $this.attr('data-filter_val', this_val);
            
            if ($.trim(this_val) == '') {
                $this.removeClass('famiau-active-filter');
                return false;
            }
            else {
                this_val = parseFloat(this_val);
                if (!isNaN(this_val)) {
                    if (this_val <= 0) {
                        $this.removeClass('famiau-active-filter');
                        return false;
                    }
                }
            }
            $this.addClass('famiau-active-filter');
        }
        else if ($this.is('.famiau-filter-select')) {
            var $thisParent = $this.parent();
            $thisParent.find('.famiau-filter-hidden').val(this_val).attr('data-filter_val', this_val);
        }
        
        
    });
    $(document).on('click', '.famiau-mega-filter-submit-btn', function (e) {
        var $thisFilterForm = $(this).closest('.famiau-mega-filter-wrap');
        famiau_mega_filter_submit($thisFilterForm);
        e.preventDefault();
        return false;
    });
    
    $(document).on('click', '.famiau-mega-filter-wrap.has-instant-filter a.famiau-filter-item', function (e) {
        var $thisFilterForm = $(this).closest('.famiau-mega-filter-wrap');
        famiau_mega_filter_submit($thisFilterForm);
        e.preventDefault();
        return false;
    });
    
    $(document).on('change', '.famiau-mega-filter-wrap.has-instant-filter select.famiau-filter-item, .famiau-mega-filter-wrap.has-instant-filter input.famiau-filter-item', function (e) {
        var $thisFilterForm = $(this).closest('.famiau-mega-filter-wrap');
        famiau_mega_filter_submit($thisFilterForm);
        e.preventDefault();
        return false;
    });
    
    // Filter dropdown submit (request)
    if ($('.famiau-mega-filter-wrap.have-filter-dropdown-request').length || $('.famiau-mega-filter-wrap.filter-after-load').length) {
        famiau_mega_filter_submit($('.famiau-mega-filter-wrap'));
    }
    
    var famiauCurrentFilterRequest = null;
    
    // Click on pagination
    $(document).on('click', '.famiau-listings-wrap .pagination a.page-numbers', function (e) {
        var $this = $(this);
        var $famiauWrap = $this.closest('.famiau-listings-wrap');
        var $filterForm = $famiauWrap.find('.famiau-mega-filter-wrap');
        var cur_page = parseInt($famiauWrap.find('.pagination .page-numbers.current').text());
        if (isNaN(cur_page)) {
            cur_page = 1;
        }
        var new_page = cur_page;
        if ($this.is('.prev') || $this.is('.next')) {
            if ($this.is('.prev')) {
                if (cur_page > 1) {
                    $famiauWrap.find('.pagination .page-numbers.current').removeClass('current').prev().addClass('current');
                    new_page--;
                }
            }
            if ($this.is('.next')) {
                if (!$famiauWrap.find('.pagination .page-numbers.current').next().is('.next')) {
                    $famiauWrap.find('.pagination .page-numbers.current').removeClass('current').next().addClass('current');
                    new_page++;
                }
            }
        }
        else {
            $famiauWrap.find('.pagination .page-numbers.current').removeClass('current');
            $this.addClass('current');
            new_page = parseInt($this.text());
        }
        if (isNaN(new_page)) {
            new_page = 1;
        }
        
        famiau_mega_filter_submit($filterForm, new_page);
        e.preventDefault();
        return false;
    });
    
    // Filter shorting
    $(document).on('change', '.famiau-listings-wrap .famiau-select-sorting', function (e) {
        var $this = $(this);
        var $famiauWrap = $this.closest('.famiau-listings-wrap');
        var $filterForm = $famiauWrap.find('.famiau-mega-filter-wrap');
        var cur_page = parseInt($famiauWrap.find('.pagination .page-numbers.current').text());
        if (isNaN(cur_page)) {
            cur_page = 1;
        }
        famiau_mega_filter_submit($filterForm, cur_page);
    });
    
    // Show/Hide clear filter button
    function famiau_show_hide_mega_filer_clear_btn() {
        if (!$('.famiau-mega-filter-wrap .famiau-mega-filter-clear-btn').length) {
            return false;
        }
        $('.famiau-mega-filter-wrap').each(function () {
            if ($(this).find('.famiau-active-filter').length) {
                $(this).find('.famiau-clear-filter-wrap').removeClass('famiau-hidden').show();
            }
            else {
                $(this).find('.famiau-clear-filter-wrap').addClass('famiau-hidden').hide();
            }
        });
    }
    
    famiau_show_hide_mega_filer_clear_btn();
    
    // Click clear filter
    $(document).on('click', '.famiau-mega-filter-wrap .famiau-mega-filter-clear-btn', function (e) {
        $('.famiau-mega-filter-wrap .famiau-active-filter').each(function () {
            if ($(this).is('.famiau-slider-range-hidden')) {
                var $thisSliderWrap = $(this).closest('.famiau-slider-range-wrap');
                if ($(this).is('.famiau-min-range-hidden')) {
                    var this_min = $(this).attr('data-min');
                    console.log(this_min);
                    $(this).val(this_min).attr('data-filter_val', this_min);
                    $thisSliderWrap.find('.famiau-slider-range').slider('values', 0, this_min);
                }
                if ($(this).is('.famiau-max-range-hidden')) {
                    var this_max = $(this).attr('data-max');
                    console.log(this_max);
                    $(this).val(this_max).attr('data-filter_val', this_max);
                    $thisSliderWrap.find('.famiau-slider-range').slider('values', 1, this_max);
                }
                $(this).trigger('change');
            }
            else {
                if ($(this).is('.famiau-filter-item[data-filter_type="popup"]')) {
                    $(this).attr('data-filter_val', '');
                    $(this).find('.famiau-select-popup-result').removeClass('famiau-has-value').text(famiau['text']['all']);
                }
                $(this).removeClass('famiau-active-filter');
            }
        });
        var $thisFilterForm = $(this).closest('.famiau-mega-filter-wrap');
        famiau_mega_filter_submit($thisFilterForm);
        e.preventDefault();
        return false;
    });
    
    function famiau_mega_filter_submit($filterForm, cur_page = 1) {
        var $famiauWrap = $filterForm.closest('.famiau-listings-wrap');
        var $resultElem = $famiauWrap.find('.famiau-listings');
        if ($filterForm.is('.processing') && !$filterForm.is('.has-instant-filter')) {
            return false;
        }
        famiau_show_hide_mega_filer_clear_btn();
        
        var filter_data = {
            'layout': '',
            'paged': cur_page,
            'shorting': 'default'
        };
        $filterForm.find('.famiau-filter-item.famiau-active-filter').each(function () {
            var this_filter_key = $(this).attr('data-filter_key');
            var this_filter_val = $(this).attr('data-filter_val');
            filter_data[this_filter_key] = this_filter_val;
        });
        
        // Shorting
        if ($famiauWrap.find('.famiau-select-sorting').length) {
            var shorting = $famiauWrap.find('.famiau-select-sorting').val();
            filter_data['shorting'] = shorting;
        }
        
        // Filter layout
        if ($('.famiau-list-shorting-wrap .layout-type.active').length) {
            var current_layout = $('.famiau-list-shorting-wrap .layout-type.active').attr('data-layout');
            filter_data['layout'] = current_layout;
        }
        
        $filterForm.addClass('processing');
        $famiauWrap.addClass('processing');
        var data = {
            action: 'famiau_frontend_mega_filter_results_via_ajax',
            filter_data: filter_data,
            nonce: famiau['security']
        };
        
        famiauCurrentFilterRequest = $.ajax({
            type: 'POST',
            url: famiau['ajaxurl'],
            data: data,
            success: function (response) {
                if ($resultElem.length) {
                    $resultElem.html(response['html']);
                    if (!$resultElem.find('.famiau-message-wrap').length) {
                        $resultElem.append('<div class="famiau-message-wrap"></div>');
                    }
                    famiau_display_multi_messages($resultElem.find('.famiau-message-wrap'), response, 'bottom');
                }
                else {
                
                }
                $filterForm.removeClass('processing');
                $famiauWrap.removeClass('processing');
            },
            dataType: 'json',
            beforeSend: function () {
                if (famiauCurrentFilterRequest != null) {
                    famiauCurrentFilterRequest.abort();
                }
            }
        });
    }
    
    // Expand/Collapse mega filter html
    $(document).on('click', '.famiau-mega-filter-wrap .famiau-exp-collapse-filter', function (e) {
        var $this = $(this);
        var $thisWrap = $this.closest('.famiau-mega-filter-wrap');
        var $thisExpFilter = $thisWrap.find('.famiau-ext-filter-wrap');
        $thisExpFilter.slideToggle();
        $this.toggleClass('famiau-is-filter-collapse');
        if ($this.is('.famiau-is-filter-collapse')) {
            $this.text(famiau['text']['expand_search']);
        }
        else {
            $this.text(famiau['text']['collapse_search']);
        }
        e.preventDefault();
        return false;
    });
    
    // Add new listing (Submit new listing)
    $(document).on('click', '.famiau-my-listing-wrap .famiau-toogle-add-new-listing-btn', function (e) {
        var $thisParent = $(this).closest('.famiau-my-listing-wrap');
        $thisParent.find('.famiau-add-new-listing-form-wrap').toggleClass('show');
    });
    
    $(document).on('submit', 'form[name="famiau_add_listing_form"]', function (e) {
        var $thisForm = $(this);
        var err = false;
        var $errElem;
        
        if ($thisForm.is('.submitting')) {
            return false;
        }
        
        var listing_data = {'attachment_ids': ''};
        
        $thisForm.find('.famiau-field').each(function () {
            var listing_key = $(this).attr('name');
            if (typeof listing_key != 'undefined' && typeof listing_key != false) {
                if ($.trim(listing_key) != '') {
                    var value_type = $(this).attr('data-value_type');
                    var this_val = $.trim($(this).val());
                    if ($(this).is('.famiau-required')) {
                        if (this_val == '') {
                            $(this).addClass('famiau-error');
                            $errElem = $(this);
                            err = true;
                        }
                        else {
                            $(this).removeClass('famiau-error');
                        }
                    }
                    if (typeof value_type == 'undefined' || typeof value_type == false) {
                        value_type = 'string';
                    }
                    if (value_type == 'array') {
                        if (this_val != '') {
                            this_val = JSON.parse(this_val);
                            listing_data[listing_key] = this_val;
                        }
                    }
                    else {
                        listing_data[listing_key] = this_val;
                    }
                }
            }
        });
        var attachment_ids = '';
        $thisForm.find('.famiau-gallery-box .famiau-img-preview').each(function () {
            var this_attachment_id = parseInt($(this).attr('data-attachment_id'));
            if (!isNaN(this_attachment_id)) {
                if ($.trim(attachment_ids) == '') {
                    attachment_ids += this_attachment_id;
                }
                else {
                    attachment_ids += ',' + this_attachment_id;
                }
            }
        });
        listing_data['attachment_ids'] = attachment_ids;
        
        if (err) {
            if ($errElem.length) {
                famiau_scroll_to_elem($errElem);
            }
            return false;
        }
        
        $thisForm.addClass('submitting');
        
        var data = {
            action: 'famiau_add_new_listing_via_ajax',
            listing_data: listing_data,
            nonce: famiau['security']
        };
        
        $.post(famiau['ajaxurl'], data, function (response) {
            
            $thisForm.removeClass('submitting');
            if (response['err'] == 'no') {
                $thisForm.find('.famiau-field').val('');
                $thisForm.find('.famiau-field-for-hidden').prop('checked', false);
                if ($('.famiau-my-listing-content .famiau-all-my-listings').length) {
                    $('.famiau-my-listing-content .famiau-all-my-listings').html(response['my_listings_html']);
                }
            }
            famiau_display_multi_messages($thisForm, response, 'bottom');
            
        });
        
        e.preventDefault();
        return false;
    });
    
    // Checkbox list with hidden value
    $(document).on('change', '.famiau-field-for-hidden', function (e) {
        var $this = $(this);
        var meta_key = $this.attr('data-meta_key');
        var $targetElem = $('.famiau-field-hidden[data-meta_key="' + meta_key + '"]');
        var list_items = Array();
        $('.famiau-field-for-hidden[data-meta_key="' + meta_key + '"]').each(function () {
            if ($(this).is(':checked')) {
                var this_val = $(this).val();
                list_items.push(this_val);
            }
        });
        list_items = JSON.stringify(list_items);
        $targetElem.val(list_items);
    });
    
    // Suggestion click
    $(document).on('click', '.famiau-suggestion-lb', function (e) {
        var $this = $(this);
        var suggest_val = $this.attr('data-suggest_val');
        var suggest_target = $this.attr('data-suggest_for');
        // Escape html
        suggest_val = $("<div>").text(suggest_val).html().replace(/\"/g, '').replace(/\'/g, '');
        if ($('[name="' + suggest_target + '"]').length) {
            var target_val = $('[name="' + suggest_target + '"]').val();
            if ($.trim(target_val) == '') {
                target_val += suggest_val;
            }
            else {
                target_val += ', ' + suggest_val;
            }
            $('[name="' + suggest_target + '"]').val(target_val);
        }
        e.preventDefault();
        return false;
    });
    
    // Famiau checkbox
    $(document).on('change', '.famiau-switch input[type="checkbox"]', function () {
        var $this = $(this);
        var $thisWrap = $this.closest('.famiau-switch');
        if ($this.is(':checked')) {
            $thisWrap.find('input[type="hidden"]').val('yes');
            if ($this.is('.famiau-filter-cb')) {
                $thisWrap.find('input[type="hidden"]').attr('data-filter_val', 'yes');
            }
        }
        else {
            $thisWrap.find('input[type="hidden"]').val('no');
            if ($this.is('.famiau-filter-cb')) {
                $thisWrap.find('input[type="hidden"]').attr('data-filter_val', 'no');
            }
        }
    });
    
    function famiau_scroll_to_elem($elem) {
        if ($elem.length) {
            $('html, body').animate({
                scrollTop: $elem.offset().top - 120
            }, 400);
        }
    }
    
    // Tabs
    if ($('.famiau-tabs').length) {
        $('.famiau-tabs').tabs();
    }
    
    // Upload media
    if (famiau['is_account_page'] == 'yes') {
        var famiau_file_frame; // variable for the wp.media famiau_file_frame
        
    }
    
    // GMap
    function famiau_init_gmap() {
        if (!$('.famiau-gmap:not(.gmap-loaded)').length) {
            return false;
        }
        $('.famiau-gmap:not(.gmap-loaded)').each(function () {
            var this_map_data = JSON.parse($(this).attr('data-map_info'));
            var map_id = $(this).attr('id');
            var this_map_pos = new google.maps.LatLng(this_map_data['center']);
            var this_map = new google.maps.Map(document.getElementById(map_id), this_map_data);
            // The marker, positioned at Uluru
            var this_marker = new google.maps.Marker({position: this_map_data['center'], map: this_map});
            
            var coordInfoWindow = new google.maps.InfoWindow();
            coordInfoWindow.setContent(famiau_create_gmap_info_window_content(this_map_data['info_window'], this_map_pos, this_map.getZoom()));
            coordInfoWindow.setPosition(this_map_pos);
            // coordInfoWindow.open(this_map);
            
            this_map.addListener('zoom_changed', function () {
                coordInfoWindow.setContent(famiau_create_gmap_info_window_content(this_map_data['info_window'], this_map_pos, this_map.getZoom()));
                // coordInfoWindow.open(this_map);
            });
            
            this_marker.addListener('click', function () {
                coordInfoWindow.setContent(famiau_create_gmap_info_window_content(this_map_data['info_window'], this_map_pos, this_map.getZoom()));
                coordInfoWindow.open(this_map, this_marker);
            });
            
        }).addClass('gmap-loaded');
    }
    
    famiau_init_gmap();
    
    var famiau_map_title_size = 64; // 256
    
    function famiau_create_gmap_info_window_content(info, latLng, zoom) {
        var scale = 1 << zoom;
        
        var worldCoordinate = famiau_gmap_project(latLng);
        
        var pixelCoordinate = new google.maps.Point(
            Math.floor(worldCoordinate.x * scale),
            Math.floor(worldCoordinate.y * scale));
        
        var tileCoordinate = new google.maps.Point(
            Math.floor(worldCoordinate.x * scale / famiau_map_title_size),
            Math.floor(worldCoordinate.y * scale / famiau_map_title_size));
        
        return [
            '<h5 class="famiau-map-info-title">' + info['title'] + '</h5>',
            '<p class="famiau-map-info-address">' + info['address'] + '</p>',
            '<div class="famiau-map-seller-notes">' + info['seller_notes'] + '</div>'
        ].join('');
    }
    
    // The mapping between latitude, longitude and pixels is defined by the web
    // mercator projection.
    function famiau_gmap_project(latLng) {
        var siny = Math.sin(latLng.lat() * Math.PI / 180);
        
        // Truncating to 0.9999 effectively limits latitude to 89.189. This is
        // about a third of a tile past the edge of the world tile.
        siny = Math.min(Math.max(siny, -0.9999), 0.9999);
        
        return new google.maps.Point(
            famiau_map_title_size * (0.5 + latLng.lng() / 360),
            famiau_map_title_size * (0.5 - Math.log((1 + siny) / (1 - siny)) / (4 * Math.PI)));
    }
    
    // Listings filter
    // Sub menu position
    function famiau_cacl_sub_menu_pos($menuElem) {
        if (!$menuElem.is('.menu-item-has-sub-menu')) {
            return false;
        }
        var $subNav = $menuElem.find('> .famiau-sub-nav');
        var ww = $(window).innerWidth();
        var sub_nav_w = $subNav.innerWidth();
        var menuElemOffset = $menuElem.offset();
        var menu_elem_w = $menuElem.innerWidth();
        var subNavOffset = $subNav.offset();
        var sub_nav_left = subNavOffset.left;
        var left_space = (ww - sub_nav_w) / 2;
        
        if (sub_nav_left + sub_nav_w > ww - 5) {
            $subNav.css({
                'left': 'auto',
                'right': 0
            });
            subNavOffset = $subNav.offset();
            sub_nav_left = subNavOffset.left;
            if (sub_nav_left < 5) {
                $subNav.css({
                    'left': '-' + (menuElemOffset.left - left_space + menu_elem_w / 2) + 'px',
                    'right': 'auto'
                });
            }
        }
        else {
            if (sub_nav_left < 5) {
                $subNav.css({
                    'left': 0,
                    'right': 'auto'
                });
            }
            subNavOffset = $subNav.offset();
            sub_nav_left = subNavOffset.left;
            if (sub_nav_left + sub_nav_w > ww - 5) {
                $subNav.css({
                    'left': '-' + (menuElemOffset.left - left_space + menu_elem_w / 2) + 'px',
                    'right': 'auto'
                });
            }
        }
    }
    
    $(document).on('hover', '.famiau-menu .famiau-menu-item', function (e) {
        famiau_cacl_sub_menu_pos($(this));
    });
    
    $(document).on('click', '.famiau-filter-group .famiau-filter-box-left', function (e) {
        var $this = $(this);
        var $thisGroup = $this.closest('.famiau-filter-group');
        $thisGroup.toggleClass('show-filter');
        e.preventDefault();
        return false;
    });
    
    $(document).on('click', '.famiau-menu .famiau-menu-item.menu-item-has-sub-menu > a', function () {
        var $this = $(this);
        var $thisLi = $this.closest('.famiau-menu-item');
        var $thisMenu = $this.closest('.famiau-menu');
        if ($thisLi.is('.famiau-active-clicked')) {
            $thisLi.removeClass('famiau-active-clicked');
        }
        else {
            $thisMenu.find('.famiau-menu-item.famiau-active-clicked').removeClass('famiau-active-clicked');
            $thisLi.addClass('famiau-active-clicked');
        }
    });
    
    $(document).on('click', function (e) {
        var $menuItems = $('.famiau-menu > .famiau-menu-item.menu-item-has-sub-menu');
        if ($(e.target).find('.famiau-menu-item.famiau-active-clicked').length || !$(e.target).closest('.famiau-menu-item.famiau-active-clicked').length) {
            $('.famiau-menu > .famiau-menu-item.famiau-active-clicked').removeClass('famiau-active-clicked');
        }
    });
    
    // Real mobile filter select popup
    $(document).on('click', '.famiau-show-mobile-filter-popup', function (e) {
        var $this = $(this);
        if (!$('.famiau-mobile-filter-frontend-wrap').length) {
            return false;
        }
        if ($('.famiau-mobile-filter-frontend-wrap').is('.famiau-show-popup')) {
            $('.famiau-mobile-filter-frontend-wrap').addClass('fadeOut').removeClass('fadeInUp famiau-show-popup');
            if (!$('.famiau-show-popup').length) {
                $('body').removeClass('has-famiau-popup');
            }
        }
        else {
            $('.famiau-mobile-filter-frontend-wrap').addClass('famiau-show-popup fadeInUp').removeClass('fadeOut');
            $('body').addClass('has-famiau-popup');
        }
        e.preventDefault();
        return false;
    });
    
    $(document).on('click', '.famiau-mobile-filter-group[data-filter_type="popup"]', function (e) {
        var $thisGroup = $(this);
        var $thisPopupFrontendWrap = $thisGroup.closest('.famiau-mobile-filter-frontend-wrap');
        var $thisSelectPopup = $thisGroup.find('.famiau-select-popup');
        var current_filter_val = $thisGroup.attr('data-filter_val');
        var popup_data = JSON.parse(decodeURIComponent($thisSelectPopup.attr('data-popup_data')));
        var popup_text = $thisSelectPopup.attr('data-popup_text');
        var depends_key = '';
        var depends_id = '';
        var has_depends = $thisSelectPopup.attr('data-has_depends');
        var this_group_id = $thisGroup.attr('id');
        has_depends = (typeof has_depends != 'undefined' && typeof has_depends != false) ? has_depends == 'yes' : false;
        
        if (!$thisPopupFrontendWrap.find('.famiau-popup-items-wrap').length) {
            $thisPopupFrontendWrap.append('<div data-popup_for="' + this_group_id + '" class="famiau-popup-items-wrap famiau-popup-window famiau-animated"></div>');
        }
        $thisPopupFrontendWrap.find('.famiau-popup-window').attr('data-popup_for', this_group_id);
        
        if (typeof popup_text == 'undefined' || popup_text == false) {
            popup_text = $thisGroup.find('.part-left').text();
        }
        
        var popup_html = '';
        var back_popup_html = '<div class="famiau-filter-top-nav"><a class="famiau-back-filter"><i class="fa fa-arrow-left"></i></a> <span class="famiau-top-nav-text">' + popup_text + '</span></div>';
        var clear_popup_selected_html = '';
        var has_clear_popup_selected_class = '';
        
        if (has_depends) {
            depends_key = $thisSelectPopup.attr('data-depends_key');
            depends_id = $thisSelectPopup.attr('data-depends_id');
            var key_0 = $thisSelectPopup.attr('data-key_0');
            var key_1 = $thisSelectPopup.attr('data-key_1');
            var depends_text = $thisSelectPopup.attr('data-depends_text');
            var popup_data_0 = Array();
            var popup_data_1 = {};
            var i = 0;
            
            for (i = 0; i < popup_data.length; i++) {
                popup_data_0.push(popup_data[i][key_0]);
                popup_data_1[popup_data[i][key_0]] = popup_data[i][key_1];
            }
            
            for (i = 0; i < popup_data_0.length; i++) {
                if (current_filter_val == popup_data_0[i]) {
                    popup_html += '<div data-item_val="' + popup_data_0[i] + '" class="famiau-popup-item famiau-selected-item">' + popup_data_0[i] + '</div>';
                    clear_popup_selected_html += famiau['html']['clear_popup_select'];
                    has_clear_popup_selected_class += ' famiau-has-clear-popup-selected';
                }
                else {
                    popup_html += '<div data-item_val="' + popup_data_0[i] + '" class="famiau-popup-item">' + popup_data_0[i] + '</div>';
                }
            }
            popup_html = back_popup_html + '<div data-child_data="' + encodeURIComponent(JSON.stringify(popup_data_1)) + '" data-depends_id="' + depends_id + '" data-depends_key="' + depends_key + '" data-depends_text="' + depends_text + '" data-default_text="' + famiau['text']['all'] + '" class="famiau-popup-items famiau-has-popup-child ' + has_clear_popup_selected_class + '">' + popup_html + '</div>' + clear_popup_selected_html;
            
            if (!$thisPopupFrontendWrap.find('.famiau-mobile-filter-group.famiau-depends-' + depends_id).length) {
                $thisGroup.after('<div data-filter_type="popup" data-filter_val="" data-filter_key="' + depends_key + '" id="' + depends_id + '" class="famiau-mobile-filter-group famiau-filter-item famiau-active-filter famiau-hidden famiau-depends-' + depends_id + '"></div>');
            }
            
            $thisPopupFrontendWrap.find('.famiau-popup-items-wrap').html(popup_html).addClass('famiau-show-popup fadeInUp').removeClass('fadeOut');
            $('body').addClass('has-famiau-popup');
        }
        else {
            for (i = 0; i < popup_data.length; i++) {
                if (current_filter_val == popup_data[i]) {
                    popup_html += '<div data-item_val="' + popup_data[i] + '" class="famiau-popup-item famiau-selected-item">' + popup_data[i] + '</div>';
                    clear_popup_selected_html += famiau['html']['clear_popup_select'];
                    has_clear_popup_selected_class += ' famiau-has-clear-popup-selected';
                }
                else {
                    popup_html += '<div data-item_val="' + popup_data[i] + '" class="famiau-popup-item">' + popup_data[i] + '</div>';
                }
            }
            popup_html = back_popup_html + '<div data-depends_key="' + depends_key + '" class="famiau-popup-items ' + has_clear_popup_selected_class + '">' + popup_html + '</div>' + clear_popup_selected_html;
            $thisPopupFrontendWrap.find('.famiau-popup-items-wrap').html(popup_html).addClass('famiau-show-popup fadeInUp').removeClass('fadeOut');
            $('body').addClass('has-famiau-popup');
        }
        
        e.preventDefault();
        return false;
    });
    
    $(document).on('click', '.famiau-mobile-filter-group[data-filter_type="popup"] .famiau-select-popup-result', function (e) {
        $(this).closest('.famiau-mobile-filter-group[data-filter_type="popup"]').trigger('click');
        e.preventDefault();
        return false;
    });
    
    $(document).on('click', '.famiau-clear-popup-select', function (e) {
        var $this = $(this);
        var $thisPopupWindow = $this.closest('.famiau-popup-window');
        var $thisPopupItems = $thisPopupWindow.find('.famiau-popup-items');
        var $thisPopupFrontendWrap = $this.closest('.famiau-mobile-filter-frontend-wrap');
        var depends_key = '';
        var depends_id = '';
        var depends_text = '';
        var default_text = famiau['text']['all']; // $thisPopupItems.attr('data-default_text');
        var this_val = $this.attr('data-item_val');
        var group_id = $thisPopupWindow.attr('data-popup_for');
        $thisPopupItems.find('.famiau-popup-item').removeClass('famiau-selected-item');
        
        $('#' + group_id).attr('data-filter_val', '').find('.famiau-select-popup-result').removeClass('famiau-has-value').text(default_text);
        
        if ($thisPopupItems.is('.famiau-has-popup-child')) {
            depends_id = $thisPopupItems.attr('data-depends_id');
            var $thisChildGroup = $thisPopupFrontendWrap.find('.famiau-mobile-filter-group.famiau-depends-' + depends_id);
            $thisChildGroup.addClass('famiau-hidden').find('.famiau-select-popup-result').removeClass('famiau-has-value').text(default_text);
        }
        
        e.preventDefault();
        return false;
    });
    
    // Real mobile filter: Choose make
    $(document).on('click', '.famiau-mobile-filter-frontend-wrap .famiau-popup-items .famiau-popup-item', function (e) {
        var $this = $(this);
        var $thisPopupItems = $this.closest('.famiau-popup-items');
        var $thisPopupWindow = $this.closest('.famiau-popup-window');
        var $thisPopupFrontendWrap = $this.closest('.famiau-mobile-filter-frontend-wrap');
        var depends_key = '';
        var depends_id = '';
        var depends_text = '';
        var default_text = '';
        var this_val = $this.attr('data-item_val');
        var group_id = $thisPopupWindow.attr('data-popup_for');
        $thisPopupItems.find('.famiau-popup-item').removeClass('famiau-selected-item');
        $this.addClass('famiau-selected-item');
        
        $('#' + group_id).attr('data-filter_val', this_val).find('.famiau-select-popup-result').addClass('famiau-has-value').text(this_val);
        
        if ($thisPopupItems.is('.famiau-has-popup-child')) {
            depends_id = $thisPopupItems.attr('data-depends_id');
            depends_key = $thisPopupItems.attr('data-depends_key');
            depends_text = $thisPopupItems.attr('data-depends_text');
            default_text = famiau['text']['all']; // $thisPopupItems.attr('data-default_text');
            var $thisChildGroup = $thisPopupFrontendWrap.find('.famiau-mobile-filter-group.famiau-depends-' + depends_id);
            var child_data = JSON.parse(decodeURIComponent($thisPopupItems.attr('data-child_data')));
            var this_child_data = child_data[this_val];
            
            $thisChildGroup.removeClass('famiau-hidden');
            var depends_html = '';
            var depends_items_html = '';
            for (var i = 0; i < this_child_data.length; i++) {
                depends_items_html += '<div data-item_val="' + this_child_data[i] + '" class="famiau-popup-item">' + this_child_data[i] + '</div>';
            }
            depends_html += '<div class="part-left"><label>' + depends_text + '</label></div>';
            depends_html += '<div class="part-right"><div data-filter_val="" class="famiau-select-popup" data-popup_text="' + depends_text + '" data-popup_data="' + encodeURIComponent(JSON.stringify(this_child_data)) + '"><div class="famiau-select-popup-result">' + default_text + '</div></div></div>';
            $thisChildGroup.html(depends_html);
        }
        
        // Close after select
        $thisPopupWindow.addClass('fadeOut').removeClass('fadeInUp famiau-show-popup');
        if (!$('.famiau-show-popup').length) {
            $('body').removeClass('has-famiau-popup');
        }
        
        e.preventDefault();
        return false;
    });
    
    $(document).on('click', '.famiau-back-filter', function (e) {
        var $this = $(this);
        var $thisPopupWindow = $this.closest('.famiau-popup-window');
        $thisPopupWindow.addClass('fadeOut').removeClass('fadeInUp famiau-show-popup');
        if (!$('.famiau-show-popup').length) {
            $('body').removeClass('has-famiau-popup');
        }
        e.preventDefault();
        return false;
    });
    
    $(document).on('click', '.famiau-mega-mobile-filter-submit-btn', function (e) {
        var $this = $(this);
        var $thisPopupFrontendWrap = $this.closest('.famiau-mobile-filter-frontend-wrap');
        
        $thisPopupFrontendWrap.find('.famiau-back-filter').trigger('click');
        
    });
    
    
    // Listings map (GMap Listings)
    function famiau_init_gmap_listings() {
        // return false;
        if (!$('.famiau-listings-map-display').length) {
            return false;
        }
        
        $('.famiau-listings-map-display:not(.famiau-gmap-loaded)').each(function (e) {
            var $thisMapElem = $(this);
            var $thisMapFilers = $thisMapElem.closest('.famiau-map-filters');
            var this_id = $thisMapElem.attr('id');
            var this_map_data = JSON.parse($thisMapElem.attr('data-map_data'));
            var this_listings_info = JSON.parse($thisMapElem.attr('data-listings_info'));
            var this_cluster_styles = JSON.parse($thisMapElem.attr('data-cluster_styles'));
            var this_marker_url = $thisMapElem.attr('data-marker_url');
            
            this_map_data['center']['lat'] = parseFloat(this_map_data['center']['lat']);
            this_map_data['center']['lng'] = parseFloat(this_map_data['center']['lng']);
            
            // GMAP LISTINGS -----------------
            var famiauGmapListings = {};
            
            famiauGmapListings.listings_info = null;
            famiauGmapListings.map = null;
            famiauGmapListings.markerClusterer = null;
            famiauGmapListings.markers = [];
            famiauGmapListings.infoWindow = null;
            
            famiauGmapListings.init = function () {
                var options = this_map_data;
                options['mapTypeId'] = google.maps.MapTypeId.ROADMAP;
                
                famiauGmapListings.map = new google.maps.Map(document.getElementById(this_id), options);
                famiauGmapListings.listings_info = this_listings_info;
                
                if ($thisMapFilers.find('.map_location').length) {
                    // Create the search box and link it to the UI element.
                    var search_location_id = $thisMapFilers.find('.map_location').attr('id');
                    
                    // Create the autocomplete object and associate it with the UI input control.
                    // Restrict the search to the default country, and to place type "cities".
                    var autocomplete = new google.maps.places.Autocomplete(
                        /** @type {!HTMLInputElement} */ (
                            document.getElementById(search_location_id)), {
                            types: ['(cities)']
                            //  componentRestrictions: countryRestrict
                        });
                    var places = new google.maps.places.PlacesService(famiauGmapListings.map);
                    
                    autocomplete.addListener('place_changed', function () {
                        var place = autocomplete.getPlace();
                        if (typeof place != 'undefined' && typeof place != false) {
                            if (place.geometry) {
                                famiauGmapListings.map.panTo(place.geometry.location);
                                famiauGmapListings.map.setZoom(9);
                            } else {
                                document.getElementById('autocomplete').placeholder = famiau['enter_location'];
                            }
                        }
                    });
                    
                }
                
                famiauGmapListings.infoWindow = new google.maps.InfoWindow();
                famiauGmapListings.showMarkers();
            };
            
            famiauGmapListings.showMarkers = function () {
                famiauGmapListings.markers = [];
                
                if (famiauGmapListings.markerClusterer) {
                    famiauGmapListings.markerClusterer.clearMarkers();
                }
                
                var numMarkers = this_listings_info.length;
                
                for (var i = 0; i < numMarkers; i++) {
                    var titleText = famiauGmapListings.listings_info[i]['listing_title'];
                    
                    var item = document.createElement('div');
                    var title = document.createElement('a');
                    title.href = '#';
                    title.className = 'title';
                    title.innerHTML = titleText;
                    
                    item.appendChild(title);
                    
                    var latLng = new google.maps.LatLng(famiauGmapListings.listings_info[i]['latitude'], this_listings_info[i]['longitude']);
                    var markerImage = new google.maps.MarkerImage(this_marker_url);
                    // var markerImage = new google.maps.MarkerImage(this_marker_url, new google.maps.Size(24, 32));
                    
                    var marker = new google.maps.Marker({
                        'position': latLng,
                        'icon': markerImage
                    });
                    
                    var fn = famiauGmapListings.markerClickFunction(famiauGmapListings.listings_info[i], latLng);
                    google.maps.event.addListener(marker, 'click', fn);
                    google.maps.event.addDomListener(title, 'click', fn);
                    famiauGmapListings.markers.push(marker);
                }
                
                window.setTimeout(famiauGmapListings.time, 0);
            };
            
            // listings_info, latLng
            famiauGmapListings.markerClickFunction = function (listings_info, latlng) {
                return function (e) {
                    e.cancelBubble = true;
                    e.returnValue = false;
                    if (e.stopPropagation) {
                        e.stopPropagation();
                        e.preventDefault();
                    }
                    
                    var car_status_html = '<span class="famiau-car-status famiau-car-status-' + listings_info['car_status'] + '">' + listings_info['car_status_text'] + '</span>';
                    var info_title_html = '<h3 class="famiau-car-info-title"><a href="' + listings_info['permalink'] + '">' + listings_info['listing_title'] + '</a></h3>';
                    var img_html = '<div class="famiau-car-img-wrap"><img width="' + listings_info['thumb']['width'] + '" height="' + listings_info['thumb']['width'] + '" src="' + listings_info['thumb']['url'] + '" title="' + listings_info['listing_title'] + '" /></div>';
                    var price_html = '<div class="famiau-car-price-wrap">' + listings_info['price'] + '</div>';
                    var fuel_type_html = '<div class="famiau-car-fuel_type-wrap famiau-feature-wrap"><span class="famiau-feature famiau-fuel_type">' + listings_info['fuel_type'] + '</span></div>';
                    var mileage_html = '<div class="famiau-car-mileage-wrap famiau-feature-wrap"><span class="famiau-feature famiau-mileage">' + listings_info['mileage'] + '</span></div>';
                    var gearbox_type_html = '<div class="famiau-car-gearbox_type-wrap famiau-feature-wrap"><span class="famiau-feature famiau-gearbox_type">' + listings_info['gearbox_type'] + '</span></div>';
                    
                    var info_html = '<div class="famiau-map-info-wrap"><div class="famiau-map-info-inner">' + car_status_html + info_title_html + '<div class="famiau-car-info">' + img_html + mileage_html + fuel_type_html + gearbox_type_html + '</div>' + price_html + '</div></div>';
                    
                    famiauGmapListings.infoWindow.setContent(info_html);
                    famiauGmapListings.infoWindow.setPosition(latlng);
                    famiauGmapListings.infoWindow.open(famiauGmapListings.map);
                };
            };
            
            famiauGmapListings.clear = function () {
                for (var i = 0, marker; marker = famiauGmapListings.markers[i]; i++) {
                    marker.setMap(null);
                }
            };
            
            famiauGmapListings.change = function () {
                famiauGmapListings.clear();
                famiauGmapListings.showMarkers();
            };
            
            var cluster_ptions = {
                styles: this_cluster_styles
            };
            
            famiauGmapListings.time = function () {
                famiauGmapListings.markerClusterer = new MarkerClusterer(famiauGmapListings.map, famiauGmapListings.markers, cluster_ptions);
            };
            
            // famiauGmapListings.clear();
            famiauGmapListings.init();
            
            $thisMapElem.addClass('famiau-gmap-loaded');
        });
    }
    
    famiau_init_gmap_listings();
    
    // Open/Close map filter form
    $(document).on('click', '.famiau-close-open-map-filter', function (e) {
        var $this = $(this);
        var $thisForm = $this.closest('form');
        
        if ($this.is('.famiau-filter-open')) {
            $this.removeClass('famiau-filter-open').addClass('famiau-filter-close');
            $thisForm.removeClass('form-opened').addClass('form-closed');
        }
        else {
            $this.addClass('famiau-filter-open').removeClass('famiau-filter-close');
            $thisForm.addClass('form-opened').removeClass('form-closed');
        }
        
        e.preventDefault();
        return false;
    });
    
    function famiau_close_map_filter_on_mobile() {
        if (!$('.famiau-close-open-map-filter').length) {
            return false;
        }
        var ww = $(window).innerWidth();
        if (ww <= 991) {
            $('.famiau-close-open-map-filter').removeClass('famiau-filter-open').addClass('famiau-filter-close');
            $('form.famiau-map-filters-form').removeClass('form-opened').addClass('form-closed');
        }
    }
    
    famiau_close_map_filter_on_mobile();
    
    // Map filter
    var famiauCurrentMapFilterRequest = null;
    $(document).on('submit', 'form.famiau-map-filters-form', function (e) {
        var $thisForm = $(this);
        var $thisMapFilters = $thisForm.closest('.famiau-map-filters');
        var $thisMapFiltersWrap = $thisMapFilters.closest('.famiau-map-filters-wrap');
        var $thisMapDisplay = $thisMapFilters.find('.famiau-listings-map-display');
        
        if ($thisMapFilters.is('.processing') && !$thisMapFiltersWrap.is('.has-instant-filter')) {
            return false;
        }
        
        $thisMapFilters.addClass('processing');
        
        var filter_data = {};
        $thisForm.find('.famiau-field').each(function () {
            var this_filter_key = $(this).attr('name');
            var this_filter_val = $(this).val();
            filter_data[this_filter_key] = this_filter_val;
        });
        
        var data = {
            action: 'famiau_map_filter_via_ajax',
            filter_data: filter_data,
            nonce: famiau['security']
        };
        
        famiauCurrentMapFilterRequest = $.ajax({
            type: 'POST',
            url: famiau['ajaxurl'],
            data: data,
            success: function (response) {
                if (response['err'] == 'no') {
                    $thisMapDisplay.attr('data-listings_info', JSON.stringify(response['listings_info'])).removeClass('famiau-gmap-loaded');
                    famiau_init_gmap_listings();
                    if ($thisForm.find('.map_location').length) {
                        $thisForm.find('.map_location').val('').trigger('change');
                    }
                }
                famiau_display_multi_messages($thisForm, response, 'bottom');
                $thisMapFilters.removeClass('processing');
            },
            dataType: 'json',
            beforeSend: function () {
                if (famiauCurrentMapFilterRequest != null) {
                    famiauCurrentMapFilterRequest.abort();
                }
            }
        });
        
        e.preventDefault();
        return false;
    });
    
    $(document).on('change', 'form.famiau-map-filters-form .famiau-field', function (e) {
        var $this = $(this);
        if ($this.is('.map_location')) {
            return false;
        }
        var $thisForm = $this.closest('form');
        var $thisMapFiltersWrap = $this.closest('.famiau-map-filters-wrap');
        if ($thisMapFiltersWrap.is('.has-instant-filter')) {
            $thisForm.trigger('submit');
        }
    });
    
    function famiau_range_slider() {
        if (!$('.famiau-slider-range').length) {
            return false;
        }
        $('.famiau-slider-range').each(function () {
            var $this = $(this);
            var $thisWrap = $this.closest('.famiau-slider-range-wrap');
            var slider_step = parseFloat($this.attr('data-step'));
            var slider_min = parseFloat($this.attr('data-min'));
            var slider_max = parseFloat($this.attr('data-max'));
            var slider_values = JSON.parse($this.attr('data-values'));
            var selected_max_price = $this.attr('data-selected_max');
            var slider_args = {
                range: $this.attr('data-range') === 'yes',
                step: slider_step,
                min: slider_min,
                max: slider_max,
                values: slider_values,
                instance: true,
                slide: function (event, ui) {
                    if ($thisWrap.find('.famiau-slider-range-input').length) {
                        $thisWrap.find('.famiau-slider-range-input-min').val(ui.values[0]);
                        $thisWrap.find('.famiau-slider-range-input-max').val(ui.values[1]);
                    }
                },
                change: function (event, ui) {
                    $thisWrap.find('.famiau-min-range-hidden').val(ui.values[0]).attr('data-filter_val', ui.values[0]).trigger('change');
                    $thisWrap.find('.famiau-max-range-hidden').val(ui.values[1]).attr('data-filter_val', ui.values[1]).trigger('change');
                    if ($thisWrap.find('.famiau-slider-range-input').length) {
                        $thisWrap.find('.famiau-slider-range-input-min').val(ui.values[0]);
                        $thisWrap.find('.famiau-slider-range-input-max').val(ui.values[1]);
                    }
                    var $thisSliderGroup = $thisWrap.closest('[data-filter_type="slider"]');
                    if ($thisSliderGroup.length) {
                        var display_format = $thisSliderGroup.attr('data-display_format');
                        if (typeof display_format != 'undefined' && typeof display_format != false) {
                            display_format = $.trim(display_format);
                            if (display_format != '') {
                                display_format = JSON.parse(display_format);
                                var from_val = ui.values[0];
                                var to_val = ui.values[1];
                                
                                var text_key = 'default';
                                if (from_val > slider_min) {
                                    if (to_val >= slider_max) {
                                        text_key = 'min';
                                    }
                                    else {
                                        text_key = 'between';
                                    }
                                }
                                else {
                                    if (to_val < slider_max) {
                                        text_key = 'max';
                                    }
                                }
                                
                                from_val = famiau_format_number_with_symbol(from_val, display_format['thousand_sep'], display_format['decimal_sep'], display_format['decimals'], display_format['symbol'], display_format['number_format']);
                                to_val = famiau_format_number_with_symbol(to_val, display_format['thousand_sep'], display_format['decimal_sep'], display_format['decimals'], display_format['symbol'], display_format['number_format']);
                                
                                var display_text = '';
                                if (display_format[text_key] != '') {
                                    display_text = display_format[text_key].replace('{min}', from_val).replace('{from}', from_val).replace('{max}', to_val).replace('{to}', to_val);
                                }
                                
                                if (display_text != '') {
                                    $thisSliderGroup.find('.famiau-slider-desc-text').text(display_text).addClass('has-text-desc');
                                }
                                else {
                                    $thisSliderGroup.find('.famiau-slider-desc-text').text('').removeClass('has-text-desc');
                                }
                            }
                        }
                    }
                }
            };
            
            var $thisSlider = $this.slider(slider_args);
            if (!isNaN(selected_max_price)) {
                if (slider_min < selected_max_price && selected_max_price < slider_max) {
                    $thisSlider.slider('values', 1, selected_max_price).trigger('change');
                }
            }
            var number_of_points = 4; // Math.ceil((slider_max - slider_min) / slider_step);
            var step_w = 100 / number_of_points; // $thisSlider.width() / number_of_points; //how far apart each option label should appear
            var legend_html = '';
            for (var i = 0; i <= number_of_points; i++) {
                // var this_point_val = ((slider_max - slider_min) * i) / number_of_points;
                var this_point_val = ((slider_max - slider_min) / number_of_points) * i + slider_min;
                var this_left = step_w * i;
                var this_pos_style = 'left:' + this_left + '%; right: auto;';
                if (this_left >= 100) {
                    this_pos_style = 'left:auto; right: 0;';
                }
                legend_html += '<span class="famiau-slider-ruler-step" style="' + this_pos_style + '">' + (this_point_val) + '</span>';
            }
            //after the slider create a containing div with p tags of a set width.
            $thisSlider.after('<div class="ui-slider-legend">' + legend_html + '</div>');
            
        });
    }
    
    famiau_range_slider();
    $(document).on('change', '.famiau-slider-range-wrap .famiau-slider-range-input', function () {
        var $this = $(this);
        var $thisWrap = $this.closest('.famiau-slider-range-wrap');
        var this_index = $this.is('.famiau-slider-range-input-min') ? 0 : 1;
        
        $thisWrap.find('.famiau-slider-range').slider('values', this_index, $this.val());
        if (this_index == 0) {
            $thisWrap.find('.famiau-min-range-hidden').val($this.val()).trigger('change');
        }
        else {
            $thisWrap.find('.famiau-max-range-hidden').val($this.val()).trigger('change');
        }
    });
    
    function famiau_format_number_with_symbol(number, thousand_sep, decimal_sep, tofixed, symbol, number_format) {
        var before_text = '';
        var after_text = '';
        number = number || 0;
        tofixed = !isNaN(tofixed = Math.abs(tofixed)) ? tofixed : 2;
        symbol = symbol !== undefined ? symbol : "$";
        thousand_sep = thousand_sep || ",";
        decimal_sep = decimal_sep || ".";
        var negative = number < 0 ? "-" : "",
            i = parseInt(number = Math.abs(+number || 0).toFixed(tofixed), 10) + "",
            j = (j = i.length) > 3 ? j % 3 : 0;
        
        switch (number_format) {
            case '%1$s%2$s':
                //left
                before_text += symbol;
                break;
            case '%1$s %2$s':
                //left with space
                before_text += symbol + ' ';
                break;
            case '%2$s%1$s':
                //right
                after_text += symbol;
                break;
            case '%2$s %1$s':
                //right with space
                after_text += ' ' + symbol;
                break;
            default:
                //default
                before_text += symbol;
        }
        
        return before_text +
            negative + (j ? i.substr(0, j) + thousand_sep : "" ) +
            i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousand_sep) +
            (tofixed ? decimal_sep + Math.abs(number - i).toFixed(tofixed).slice(2) : "") +
            after_text;
    }
    
    // Show mask phone number
    $(document).on('click', '.famiau-phone-mask-wrap .famiau-show-number', function (e) {
        var $this = $(this);
        var $thisWrap = $this.closest('.famiau-phone-mask-wrap');
        if ($thisWrap.is('.processing')) {
            return false;
        }
        
        $thisWrap.addClass('processing');
        var data = {
            action: 'famiau_show_mask_number_via_ajax',
            dealer_id: $this.attr('data-dealer_id'),
            nonce: famiau['security']
        };
        
        $.post(famiau['ajaxurl'], data, function (response) {
            
            if (response['err'] == 'no') {
                $thisWrap.html(response['html']).addClass('famiau-has-unmask-wrap');
            }
            $thisWrap.removeClass('processing');
            
        });
        
    });
    
    function famiau_open_media_uploader() {
        if (famiau['is_account_page'] == 'yes') {
            // attach a click event (or whatever you want) to some element on your page
            $(document).on('click', '.famiau-upload-wrap .famiau-upload-btn', function (e) {
                
                var $this = $(this);
                var thisWrap = $this.closest('.famiau-upload-wrap');
                var multi = thisWrap.attr('data-multi') == 'yes';
                
                // if the famiau_file_frame has already been created, just reuse it
                if (famiau_file_frame) {
                    famiau_file_frame.open();
                    return;
                }
                
                famiau_file_frame = wp.media.frames.file_frame = wp.media({
                    title: $(this).attr('data-uploader_title'),
                    button: {
                        text: $(this).attr('data-uploader_button_text')
                    },
                    library: {
                        type: 'image'
                        // uploadedTo: wp.media.view.settings.post.id
                    },
                    multiple: multi // set this to true for multiple file selection
                });
                
                famiau_file_frame.on('select', function () {
                    var selection_imgs = famiau_file_frame.state().get('selection').toJSON();
                    var remove_img_btn_html = '<a href="#" class="famiau-remove-img-btn famiau-remove-btn"><i class="fa fa-times"></i></a>';
                    
                    if (!thisWrap.find('.famiau-imgs-preview-wrap').length) {
                        thisWrap.prepend('<div class="famiau-imgs-preview-wrap famiau-sortable"></div>');
                    }
                    
                    for (var i = 0; i < selection_imgs.length; i++) {
                        var attachment = selection_imgs[i];
                        
                        var img_full = {};
                        var img_url_full = attachment['url'];
                        var img_url = img_url_full;
                        var width = attachment['width'];
                        var height = '';
                        if (typeof attachment['sizes']['thumbnail'] != 'undefined' && typeof attachment['sizes']['thumbnail'] != false) {
                            img_url = attachment['sizes']['thumbnail']['url'];
                            width = attachment['sizes']['thumbnail']['width'];
                            height = attachment['sizes']['thumbnail']['height'];
                        }
                        else {
                        
                        }
                        if (!thisWrap.find('.famiau-img-preview-' + attachment['id']).length) {
                            if (typeof attachment['sizes']['full'] != 'undefined' && typeof attachment['sizes']['full'] != false) {
                                img_full = attachment['sizes']['full'];
                            }
                            else {
                                img_full = {
                                    url: img_url_full,
                                    height: '',
                                    width: ''
                                }
                            }
                            img_full = JSON.stringify(img_full);
                            if (multi) {
                                thisWrap.find('.famiau-imgs-preview-wrap').append('<div class="famiau-img-preview-wrap">' +
                                    '<img width="' + width + '" height="' + height + '" data-attachment_id="' + attachment['id'] + '" data-img_full="' + encodeURIComponent(img_full) + '" class="famiau-img-preview famiau-img-preview-' + attachment['id'] + '" src="' + img_url + '" /> ' + remove_img_btn_html + '</div>');
                            }
                            else {
                                thisWrap.find('.famiau-imgs-preview-wrap').html('<div class="famiau-img-preview-wrap">' +
                                    '<img width="' + width + '" height="' + height + '" data-attachment_id="' + attachment['id'] + '" data-img_full="' + encodeURIComponent(img_full) + '" class="famiau-img-preview famiau-img-preview-' + attachment['id'] + '" src="' + img_url + '" /> ' + remove_img_btn_html + '</div>');
                            }
                        }
                        else {
                        
                        }
                    }
                    famiau_update_main_img_preview(thisWrap);
                });
                
                famiau_file_frame.open();
                
                e.preventDefault();
            });
            
            // Remove preview image
            $(document).on('click', '.famiau-img-preview-wrap .famiau-remove-img-btn', function (e) {
                var $this = $(this);
                var thisImgWrap = $this.closest('.famiau-img-preview-wrap');
                var thisUploadWrap = $this.closest('.famiau-upload-wrap');
                thisImgWrap.remove();
                famiau_update_main_img_preview(thisUploadWrap);
                
                e.preventDefault();
            });
            
        }
    }
    
    famiau_open_media_uploader();
    
    // Sortable
    $('.famiau-sortable').each(function () {
        var $this = $(this);
        $this.sortable({
            placeholder: 'ui-state-highlight',
            instance: true,
            update: function (event, ui) {
                famiau_update_main_img_preview($this.closest('.famiau-upload-wrap'));
            }
        });
        $this.disableSelection();
    });
    
    function famiau_update_main_img_preview($element) {
        if ($element.find('.famiau-imgs-preview-wrap .famiau-img-preview-wrap .famiau-img-preview').length) {
            var img_full = $element.find('.famiau-imgs-preview-wrap .famiau-img-preview-wrap:first-child .famiau-img-preview').attr('data-img_full');
            img_full = decodeURIComponent(img_full);
            if ($.trim(img_full) != '') {
                img_full = JSON.parse(img_full);
                $element.find('.famiau-main-img-wrap').html('<img width="' + img_full['width'] + '" height="' + img_full['height'] + '" src="' + img_full['url'] + '" class="famiau-main-img-preview" alt="" />');
            }
        }
        else {
            $element.find('.famiau-main-img-wrap').html('');
        }
    }
    
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
    
    $(window).on('resize', function () {
        famiau_close_map_filter_on_mobile();
    });
    
});