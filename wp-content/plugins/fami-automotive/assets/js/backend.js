jQuery(document).ready(function ($) {
    "use strict";
    
    // Tabs
    function famiau_show_active_tab_content() {
        $('.famiau-tabs').each(function () {
            var $thisTabs = $(this);
            var tab_id = $thisTabs.find('.nav-tab.nav-tab-active').attr('data-tab_id');
            $thisTabs.find('.tab-content').removeClass('tab-content-active');
            $thisTabs.find('.tab-content#' + tab_id).addClass('tab-content-active');
        });
    }
    
    famiau_show_active_tab_content();
    
    $(document).on('click', '.famiau-tabs .nav-tab', function (e) {
        var $this = $(this);
        var $thisTabs = $this.closest('.famiau-tabs');
        if ($this.is('.nav-tab-active')) {
            return false;
        }
        $thisTabs.find('.nav-tab').removeClass('nav-tab-active');
        $this.addClass('nav-tab-active');
        famiau_show_active_tab_content();
        e.preventDefault();
    });
    
    function famiau_update_custom_desc_groups() {
        if (!$('.famiau-custom-desc-groups-list .famiau-custom-desc-group').length) {
            $('textarea[name="_famiau_custom_desc_groups"]').val('');
            return;
        }
        var desc_groups_data = Array();
        $('.famiau-custom-desc-groups-list .famiau-custom-desc-group').each(function () {
            var $this = $(this);
            var this_group_name = $this.find('.famiau-group-name-input').val();
            var this_group_desc_list = Array();
            $this.find('.famiau-desc-detail').each(function () {
                var desc_label = $(this).find('.famiau-desc-label-input').val();
                var desc_val = $(this).find('.famiau-desc-value-input').val();
                var is_heading_desc = $(this).find('.famiau-is-heading-desc-cb').is(':checked') ? 'yes' : 'no';
                this_group_desc_list.push({
                    desc_label: desc_label,
                    desc_val: desc_val,
                    is_heading_desc: is_heading_desc,
                });
            });
            desc_groups_data.push({
                group_name: this_group_name,
                group_desc_list: this_group_desc_list
            });
        });
        
        $('textarea[name="_famiau_custom_desc_groups"]').val(JSON.stringify(desc_groups_data));
    }
    
    famiau_update_custom_desc_groups();
    
    $(document).on('change', '.famiau-custom-desc-group-wrap input', function (e) {
        var this_val = $(this).val();
        this_val = $("<div>").text(this_val).html().replace(/\"/g, '').replace(/\'/g, '');
        $(this).val(this_val);
        famiau_update_custom_desc_groups();
    });
    
    // Add new make
    $(document).on('submit', 'form[name="famiau-add-make-form"]', function () {
        var $thisForm = $(this);
        var new_make = $thisForm.find('.famiau-add-make-input').val();
        var err = false;
        
        // Escape html
        new_make = $("<div>").text(new_make).html().replace(/\"/g, '').replace(/\'/g, '');
        
        if ($.trim(new_make) == '') {
            err = true;
            $thisForm.find('.famiau-add-make-input').addClass('error');
        }
        else {
            $thisForm.find('.famiau-add-make-input').removeClass('error');
        }
        
        if ($('.famiau-makes-list-wrap .famiau-make-item[data-make="' + new_make + '"]').length) {
            err = true;
            alert('Duplicate make');
            $thisForm.find('.famiau-add-make-input').addClass('error');
        }
        
        if (err) {
            return false;
        }
        
        // var new_make_html = '<div class="famiau-make-item" data-make="' + new_make + '"><div class="famiau-item-inner">' + new_make + '</div><a href="#" class="remove-btn" title="Remove">x</a></div>';
        var make_reg = new RegExp('{make}', 'g');
        var models_reg = new RegExp('{models}', 'g');
        var new_make_html = famiau['html_temp']['make_item'].replace(make_reg, new_make).replace(models_reg, '');
        $('.famiau-makes-list-wrap').append(new_make_html);
        $thisForm.find('.famiau-add-make-input').val('').focus();
        
        return false;
    });
    
    // Remove an make
    $(document).on('click', '.famiau-makes-list-wrap .famiau-make-item .remove-btn', function (e) {
        var c = confirm(famiau['text']['confirm_remove_make_item']);
        if (!c) {
            return false;
        }
        $(this).closest('.famiau-make-item').remove();
        e.preventDefault();
    });
    
    // Add fuel type
    $(document).on('submit', 'form[name="famiau-add-fuel_type-form"]', function () {
        var $thisForm = $(this);
        var new_fuel_type = $thisForm.find('.famiau-add-fuel_type-input').val();
        var err = false;
        
        // Escape html
        new_fuel_type = $("<div>").text(new_fuel_type).html().replace(/\"/g, '').replace(/\'/g, '');
        
        if ($.trim(new_fuel_type) == '') {
            err = true;
            $thisForm.find('.famiau-add-fuel_type-input').addClass('error');
        }
        else {
            $thisForm.find('.famiau-add-fuel_type-input').removeClass('error');
        }
        
        if ($('.famiau-fuel_types-list-wrap .famiau-fuel_type-item[data-fuel_type="' + new_fuel_type + '"]').length) {
            err = true;
            // alert('Duplicate fuel_type');
            $thisForm.find('.famiau-add-fuel_type-input').addClass('error');
        }
        
        if (err) {
            return false;
        }
        
        var new_fuel_type_html = '<div class="famiau-fuel_type-item" data-fuel_type="' + new_fuel_type + '"><div class="famiau-item-inner">' + new_fuel_type + '</div><a href="#" class="remove-btn" title="Remove">x</a></div>';
        $('.famiau-fuel_types-list-wrap').append(new_fuel_type_html);
        $thisForm.find('.famiau-add-fuel_type-input').val('').focus();
        
        return false;
    });
    
    // Remove a fuel type
    $(document).on('click', '.famiau-fuel_types-list-wrap .famiau-fuel_type-item .remove-btn', function (e) {
        $(this).closest('.famiau-fuel_type-item').remove();
        e.preventDefault();
    });
    
    // Toggle models list
    $(document).on('click', '.famiau-toggle-edit-sub-item', function (e) {
        var $this = $(this);
        var $thisMakeItem = $this.closest('.famiau-make-item');
        var $thisItemsListWrap = $thisMakeItem.closest('.famiau-items-list-wrap');
        
        if ($thisMakeItem.is('.famiau-show-sub-list')) {
            $thisMakeItem.removeClass('famiau-show-sub-list');
        }
        else {
            $thisItemsListWrap.find('.famiau-make-item').removeClass('famiau-show-sub-list');
            $thisMakeItem.addClass('famiau-show-sub-list');
        }
        
        e.preventDefault();
    });
    
    // Add new model
    $(document).on('submit', 'form.famiau-add-model-form', function (e) {
        var $thisForm = $(this);
        var $thisMakeItem = $thisForm.closest('.famiau-make-item');
        var $thisModelsList = $thisMakeItem.find('.famiau-models-list');
        var new_model = $thisForm.find('.famiau-model-input').val();
        var err = false;
        
        // Escape html
        new_model = $("<div>").text(new_model).html().replace(/\"/g, '').replace(/\'/g, '');
        
        if ($.trim(new_model) == '') {
            err = true;
            $thisForm.find('.famiau-model-input').addClass('error');
        }
        else {
            $thisForm.find('.famiau-model-input').removeClass('error');
        }
        
        if ($thisModelsList.find('.famiau-model-item[data-model="' + new_model + '"]').length) {
            err = true;
            // alert('Duplicate fuel_type');
            $thisForm.find('.famiau-model-input').addClass('error');
        }
        
        if (err) {
            return false;
        }
        
        var model_reg = new RegExp('{model}', 'g');
        var new_model_html = famiau['html_temp']['model_item'].replace(model_reg, new_model);
        $thisModelsList.append(new_model_html);
        famiau_update_models_list($thisMakeItem);
        $thisForm.find('.famiau-model-input').val('').focus();
        
        return false;
    });
    
    function famiau_update_models_list($makeItem) {
        var models_args = Array();
        $makeItem.find('.famiau-model-item').each(function () {
            var model = $(this).attr('data-model');
            if ($.trim(model) != '') {
                models_args.push(model);
            }
        });
        $makeItem.attr('data-models', JSON.stringify(models_args));
    }
    
    // Remove sub item
    $(document).on('click', '.famiau-remove-sub-item-btn', function (e) {
        var $this = $(this);
        var $thisItem = $this.closest('.famiau-item');
        $this.closest('.famiau-sub-item').remove();
        if ($thisItem.is('.famiau-make-item')) {
            famiau_update_models_list($thisItem);
        }
        e.preventDefault();
    });
    
    // Add New Item
    $(document).on('submit', 'form.famiau-add-item-form', function () {
        var $thisForm = $(this);
        var $thisWrap = $thisForm.closest('.famiau-inner-wrapper');
        var item_input_selector = '.famiau-item-input';
        var item_val = $thisForm.find(item_input_selector).val();
        var err = false;
        
        // Escape html
        item_val = $("<div>").text(item_val).html().replace(/\"/g, '').replace(/\'/g, '');
        
        if ($.trim(item_val) == '') {
            err = true;
            $thisForm.find(item_input_selector).addClass('error');
        }
        else {
            $thisForm.find(item_input_selector).removeClass('error');
        }
        
        if ($thisWrap.find('.famiau-items-list-wrap .famiau-item[data-item_val="' + item_val + '"]').length) {
            err = true;
            $thisForm.find(item_input_selector).addClass('error');
        }
        
        if (err) {
            return false;
        }
        
        var new_item_html = '<div class="famiau-item" data-item_val="' + item_val + '"><div class="famiau-item-inner">' + item_val + '</div><a href="#" class="remove-btn" title="Remove">x</a></div>';
        $thisWrap.find('.famiau-items-list-wrap').append(new_item_html);
        $thisForm.find(item_input_selector).val('').focus();
        
        return false;
    });
    
    // Remove An Item
    $(document).on('click', '.famiau-items-list-wrap .famiau-item .remove-btn', function (e) {
        var c = confirm(famiau['text']['confirm_remove_item']);
        if (!c) {
            return false;
        }
        $(this).closest('.famiau-item').remove();
        e.preventDefault();
    });
    
    /* EDIT SINGLE PRODUCT ================================== */
    
    // Change selected make
    $(document).on('change', 'select[name="_famiau_make"]', function () {
        $('select[name="_famiau_model"]').attr('data-selected', '');
        $('input[name="_famiau_model"]').val('');
        famiau_update_models_select();
    });
    
    // Hide famiau field group
    $('.famiau-metabox-hidden-field').each(function () {
        $(this).closest('.cs-element').addClass('hidden').css({
            'display': 'none'
        });
    });
    
    // Get models select
    function famiau_get_model_select_html() {
        var $makeElem = $('select[name="_famiau_metabox_options[_famiau_make]"]');
        var $modelElem = $('input[name="_famiau_metabox_options[_famiau_model]"]');
        var $modelElemSelect = $('select[name="_famiau_metabox_options[_famiau_model_select]"]');
        if (!$makeElem.length || !$modelElem.length || !$modelElemSelect.length) {
            return false;
        }
        var cur_make = $makeElem.val();
        var cur_model = $modelElem.val();
        if (cur_make == '') {
            $modelElemSelect.html('<option value="" selected="selected">' + famiau['text']['select_model'] + '</option>').trigger('change');
        }
        else {
            var all_makes = JSON.parse($makeElem.attr('data-makes'));
            var cur_models = all_makes[cur_make];
            var model_options_html = '<option value="">' + famiau['text']['select_model'] + '</option>';
            if (typeof cur_models != 'undefined' && typeof cur_models != false) {
                if (!$.isEmptyObject(cur_models)) {
                    for (var i = 0; i < cur_models.length; i++) {
                        var selected = cur_models[i] == cur_model ? 'selected="selected"' : '';
                        model_options_html += '<option ' + selected + ' value="' + cur_models[i] + '">' + cur_models[i] + '</option>';
                    }
                }
            }
            $modelElemSelect.html(model_options_html);
        }
    }
    
    famiau_get_model_select_html();
    $(document).on('change', 'select[name="_famiau_metabox_options[_famiau_make]"]', function () {
        famiau_get_model_select_html();
        $('input[name="_famiau_metabox_options[_famiau_model]"]').val('');
    });
    $(document).on('change', 'select[name="_famiau_metabox_options[_famiau_model_select]"]', function () {
        if (!$('input[name="_famiau_metabox_options[_famiau_model]"]').length) {
            return false;
        }
        $('input[name="_famiau_metabox_options[_famiau_model]"]').val($(this).val());
    });
    
    // Update models select
    function famiau_update_models_select() {
        if (!$('select[name="_famiau_make"]').length || !$('select[name="_famiau_model"]').length) {
            return false;
        }
        var $makeSelect = $('select[name="_famiau_make"]');
        var $modelSelect = $('select[name="_famiau_model"]');
        var selected_model = $modelSelect.attr('data-selected');
        var models = $makeSelect.find('option:selected').attr('data-models');
        if ($.trim(models) != '') {
            models = JSON.parse(models);
            var options_html = '';
            if (models.length > 0) {
                options_html += '<option data-model="" value="">' + famiau['text']['select_model'] + '</option>';
                for (var i = 0; i < models.length; i++) {
                    // Escape html
                    models[i] = $("<div>").text(models[i]).html().replace(/\"/g, '').replace(/\'/g, '');
                    if (selected_model == models[i]) {
                        options_html += '<option selected data-model="' + models[i] + '" value="' + models[i] + '">' + models[i] + '</option>';
                    }
                    else {
                        options_html += '<option data-model="' + models[i] + '" value="' + models[i] + '">' + models[i] + '</option>';
                    }
                }
                $modelSelect.html(options_html);
            }
            else {
                $modelSelect.html('<option data-model="" value="">' + famiau['text']['no_model_to_select'] + '</option>');
            }
        }
        else {
            $modelSelect.html('<option data-model="" value="">' + famiau['text']['no_model_to_select'] + '</option>');
        }
    }
    
    famiau_update_models_select();
    
    // Metabox init suggestions
    $('.famiau-suggestions-field').each(function () {
        var $this = $(this);
        var $thisParent = $this.parent();
        var suggestions = $.trim($this.attr('data-suggestions'));
        var target_name = $this.attr('name');
        var suggestions_html = '';
        if (suggestions != '') {
            suggestions = JSON.parse(suggestions);
            for (var i = 0; i < suggestions.length; i++) {
                suggestions_html += '<label data-suggest_for="' + target_name + '" data-suggest_val="' + suggestions[i] + '" class="famiau-suggestion-lb">' + suggestions[i] + '</label>';
            }
        }
        if (suggestions_html != '') {
            if (!$thisParent.find('.famiau-suggestion-lbs').length) {
                $thisParent.append('<span class="famiau-suggestion-lbs famiau-lbs-group"></span>');
            }
            $thisParent.find('.famiau-suggestion-lbs').html(suggestions_html);
        }
    });
    
    // Checkbox list with hidden value
    $(document).on('change', '.famiau-meta-field-for-hidden', function (e) {
        var $this = $(this);
        var meta_key = $this.attr('data-meta_key');
        var $targetElem = $('.famiau-meta-field-hidden[data-meta_key="' + meta_key + '"]');
        var list_items = Array();
        $('.famiau-meta-field-for-hidden[data-meta_key="' + meta_key + '"]').each(function () {
            if ($(this).is(':checked')) {
                var this_val = $(this).val();
                list_items.push(this_val);
            }
        });
        list_items = JSON.stringify(list_items);
        $targetElem.val(list_items);
    });
    
    /* END EDIT SINGLE PRODUCT ============================== */
    
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
    
    function famiau_mega_filter_submit($filterForm) {
        var $famiauWrap = $filterForm.closest('.famiau-wrap');
        var $resultElem = $famiauWrap.find('.famiau-results-wrap');
        if ($filterForm.is('.processing')) {
            return false;
        }
        var filter_data = {};
        $filterForm.find('.famiau-filter-item.famiau-active-filter').each(function () {
            var this_filter_key = $(this).attr('data-filter_key');
            var this_filter_val = $(this).attr('data-filter_val');
            filter_data[this_filter_key] = this_filter_val;
        });
        
        $filterForm.addClass('processing');
        var data = {
            action: 'famiau_backend_mega_filter_results_via_ajax',
            filter_data: filter_data,
            nonce: famiau['security']
        };
        
        $.post(ajaxurl, data, function (response) {
            
            if ($resultElem.length) {
                $resultElem.html(response['html']);
                famiau_display_multi_messages($resultElem, response, 'bottom');
            }
            else {
            
            }
            $filterForm.removeClass('processing');
        });
    }
    
    // Filter action
    $(document).on('click', '.famiau-listings-table .famiau-action', function (e) {
        var $this = $(this);
        var $thisTr = $this.closest('tr');
        var $thisResulesWrap = $this.closest('.famiau-results-wrap');
        var action_name = $this.attr('data-action');
        var listing_id = $thisTr.attr('data-listing_id');
        
        if ($thisTr.is('.processing')) {
            return false;
        }
        
        var c = confirm(famiau['text']['confirm_action_' + action_name]);
        if (!c) {
            return false;
        }
        
        var data = {
            action: 'famiau_do_admin_listing_action_via_ajax',
            action_name: action_name,
            listing_id: listing_id,
            nonce: famiau['security']
        };
        
        $.post(ajaxurl, data, function (response) {
            
            if (response['err'] == 'no') {
                $thisTr.find('.famiau-listing-status').removeClass('listing-status-' + response['old_listing_status']).text(response['new_listing_status']);
                $thisTr.find('.famiau-actions-td').html(response['actions_html']);
            }
            
            $thisTr.removeClass('processing');
            famiau_display_multi_messages($thisResulesWrap, response, 'bottom');
            
        });
        
        e.preventDefault();
        return false;
    });
    
    /* END CAR MEGA FILTER ================================== */
    
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
    
    // Show popup
    $(document).on('click', '.famiau-show-popup-btn', function (e) {
        if (!$('body .famiau-popup-wrap').length) {
            $('body').append(famiau['html_temp']['popup']);
        }
        
        $('body').addClass('famiau-show-popup');
        
        e.preventDefault();
    });
    
    // Close popup
    $(document).on('click', '.famiau-close-popup', function (e) {
        var $this = $(this);
        $('body').removeClass('famiau-show-popup');
        
        e.preventDefault();
    });
    
    $(document).on('click', '.famiau-popup-wrap', function (e) {
        if (!$('.famiau-popup-inner').is(e.target) && $('.famiau-popup-inner').has(e.target).length === 0) {
            $('body').removeClass('famiau-show-popup');
        }
    });
    
    // Add new description group (form)
    $(document).on('click', '.famiau-add-desc-group-form-btn', function (e) {
        var $this = $(this);
        var $thisWrap = $this.closest('.famiau-custom-desc-group-wrap');
        var $thisDescGroupsList = $thisWrap.find('.famiau-custom-desc-groups-list');
        var groups_count = $thisDescGroupsList.find('.famiau-custom-desc-group').length;
        
        if (groups_count > 4) {
            return false;
        }
        
        $thisDescGroupsList.append(famiau['html_temp']['desc_group_form_template']);
        famiau_update_custom_desc_groups();
        return false;
    });
    
    // Remove a description group
    $(document).on('click', '.famiau-remove-desc-group-btn', function (e) {
        $(this).closest('.famiau-custom-desc-group').remove();
        famiau_update_custom_desc_groups();
        return false;
    });
    
    // Add new description detail (form)
    $(document).on('click', '.famiau-custom-desc-group .famiau-add-custom-desc-detail-btn', function (e) {
        var $this = $(this);
        var $thisGroup = $this.closest('.famiau-custom-desc-group');
        $thisGroup.find('.famiau-custom-desc-details-list').append(famiau['html_temp']['desc_detail_form_template']);
        famiau_update_custom_desc_groups();
        return false;
    });
    
    // Remove a description detail
    $(document).on('click', '.famiau-del-desc-detail-btn', function (e) {
        $(this).closest('.famiau-desc-detail').remove();
        famiau_update_custom_desc_groups();
        return false;
    });
    
    // Add custom description group
    // $(document).on('click', '.famiau-add-desc-group .famiau-add-desc-group-btn', function (e) {
    //     var $this = $(this);
    //
    //     e.preventDefault();
    // });
    
    // Save all settings
    $(document).on('click', '.famiau-save-all-settings', function (e) {
        var $this = $(this);
        if ($this.is('.processing')) {
            return false;
        }
        $('.fami-all-settings-form').find('.famiau-message').remove();
        $this.addClass('processing disabled').prop('disabled', true);
        var all_makes = Array();
        var all_fuel_types = Array();
        var famiau_settings = Array();
        var all_settings = {
            all_makes: all_makes,
            all_fuel_types: all_fuel_types,
            famiau_settings: famiau_settings
        };
        
        if ($('.fami-all-settings-form .famiau-field.wp-editor-area').length) {
            // Get TinyMCE content
            tinyMCE.triggerSave();
        }
        
        $('.fami-all-settings-form .famiau-field').each(function () {
            var setting_key = $(this).attr('name');
            var setting_val = $(this).val();
            famiau_settings.push(
                {
                    'setting_key': setting_key,
                    'setting_val': setting_val
                }
            );
        });
        
        $('.famiau-makes-list-wrap .famiau-make-item').each(function () {
            var this_models = Array();
            $(this).find('.famiau-model-item').each(function () {
                var this_model = $(this).attr('data-model');
                if ($.trim(this_model) != '') {
                    this_models.push(this_model);
                }
            });
            all_makes.push(
                {
                    make: $(this).attr('data-make'),
                    models: this_models
                }
            );
        });
        
        $('.famiau-fuel_types-list-wrap .famiau-fuel_type-item').each(function () {
            all_fuel_types.push($(this).attr('data-fuel_type'));
        });
        
        all_settings['all_makes'] = all_makes;
        all_settings['all_fuel_types'] = all_fuel_types;
        all_settings['famiau_settings'] = famiau_settings;
        
        // Find all another keys
        $('.famiau-items-list-wrap[data-option_key]').each(function () {
            var option_key = $(this).attr('data-option_key');
            all_settings[option_key] = Array();
            $(this).find('.famiau-item').each(function () {
                var item_val = $(this).attr('data-item_val');
                if ($.trim(item_val) != '') {
                    all_settings[option_key].push(item_val);
                }
            });
        });
        
        var data = {
            action: 'famiau_save_all_settings_via_ajax',
            all_settings: all_settings,
            nonce: famiau['security']
        };
        
        $.post(famiau['ajaxurl'], data, function (response) {
            famiau_display_multi_messages($('.fami-all-settings-form'), response, 'bottom');
            $this.removeClass('processing disabled').prop('disabled', false);
        });
        
        e.preventDefault();
    });
    
    // Import settings
    $(document).on('submit', 'form[name="famiau_import_settings_form"]', function (e) {
        var $thisForm = $(this);
        
        if ($thisForm.is('.processing')) {
            return false;
        }
        
        var c = confirm(famiau['text']['confirm_import_settings']);
        if (!c) {
            return false;
        }
        
        var form_data = new FormData(this);
        
        $thisForm.addClass('processing');
        $thisForm.find('button[type="submit"]').prop('disabled', true);
        
        // var data = {
        //     action: 'famiau_import_all_settings',
        //     form_data: form_data,
        //     processData: false,
        //     contentType: false,
        //     nonce: famiau['security']
        // };
        
        $.ajax({
            url: famiau['import_settings_url'],
            type: 'POST',
            data: form_data,
            processData: false,
            contentType: false,
            success: function (response) { // je récupère la réponse du fichier PHP
                $thisForm.removeClass('processing');
                $thisForm.find('button[type="submit"]').prop('disabled', false);
                famiau_display_multi_messages($thisForm, response, 'bottom');
                location.reload();
            }
        });
        
        e.preventDefault();
        return false;
    });
    
    // Min/Max input group
    function famiau_input_min_max() {
        $('.famiau-input-min-max-group').each(function () {
            var $this = $(this);
            var $minInput = $this.find('.famiau-input-num-link-min');
            var $maxInput = $this.find('.famiau-input-num-link-max');
            var min_val = parseFloat($minInput.val());
            var max_val = parseFloat($maxInput.val());
            
            if (isNaN(min_val)) {
                min_val = 0;
            }
            if (isNaN(max_val)) {
                max_val = min_val;
            }
            
            $maxInput.attr('min', min_val);
            
            if (max_val < min_val) {
                max_val = min_val;
            }
            $minInput.val(min_val);
            $maxInput.val(max_val);
        });
    }
    
    famiau_input_min_max();
    $(document).on('change', '.famiau-input-min-max-group input', function () {
        famiau_input_min_max();
    });
    
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
            if ($this.find('.famiau-filter-item').length) {
                $minSelect.attr('data-filter_val', min_val);
                $maxSelect.attr('data-filter_val', max_val);
            }
            
        });
    }
    
    famiau_min_max_select_number();
    
    $(document).on('change', '.famiau-select-min-max-group select', function () {
        var is_selected_max = $(this).is('.famisp-select-num-max');
        famiau_min_max_select_number(is_selected_max);
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
            var slider_args = {
                range: $this.attr('data-range') === 'yes',
                step: slider_step,
                min: slider_min,
                max: slider_max,
                values: slider_values,
                instance: true,
                slide: function (event, ui) {
                    // $( "#amount" ).val( "$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ] );
                    $thisWrap.find('.famiau-min-range-hidden').val(ui.values[0]).attr('data-filter_val', ui.values[0]);
                    $thisWrap.find('.famiau-max-range-hidden').val(ui.values[1]).attr('data-filter_val', ui.values[1]);
                }
            };
            
            var $thisSlider = $this.slider(slider_args);
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
        // $( '.famiau-slider-range' ).slider({
        //     range: true,
        //     min: 0,
        //     max: 500,
        //     values: [ 75, 300 ],
        //     slide: function( event, ui ) {
        //         // $( "#amount" ).val( "$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ] );
        //     }
        // });
    }
    
    famiau_range_slider();
    
    // Nav tab responsive
    function famiau_nav_tab_responsive() {
        if (!$('.famiau-tabs .nav-tab-wrapper').length) {
            return false;
        }
        $('.famiau-tabs .nav-tab-wrapper').each(function () {
            var $this = $(this);
            var this_w = $this.outerWidth();
            var item_margin_lr = parseFloat($this.find('> .nav-tab:first-child').css('margin-left')) + parseFloat($this.find('> .nav-tab:first-child').css('margin-right'));
            var show_more_w = 36;
            var threshold_w = 0;
            var count_hidden_items = 0;
            
            if (!$this.find('> .nav-tab-show-more').length) {
                $this.append('<a href="#" class="nav-tab-show-more famiau-show">... <div class="famiau-more-nav-tabs"></div></a>');
            }
            show_more_w = $this.find('.nav-tab-show-more').outerWidth();
            
            $this.find('.famiau-more-nav-tabs').html('');
            
            $this.find('> .nav-tab').each(function () {
                var this_item_w = parseFloat($(this).outerWidth());
                var this_nav_tab_target = $(this).attr('data-tab_id');
                if (threshold_w + this_item_w + item_margin_lr < this_w - 15 - show_more_w) {
                    threshold_w = threshold_w + this_item_w + item_margin_lr;
                    
                    $(this).removeClass('famiau-nav-tab-hidden-item');
                }
                else {
                    $(this).addClass('famiau-nav-tab-hidden-item');
                    if (!$this.find('.famiau-more-nav-tabs .nav-tab-clone[data-tab_id="' + this_nav_tab_target + '"]').length) {
                        var clone = $(this).clone(true).addClass('nav-tab-clone').removeClass('famiau-nav-tab-hidden-item').removeAttr('id');
                        $this.find('.nav-tab-show-more .famiau-more-nav-tabs').append(clone);
                    }
                    count_hidden_items++;
                }
            });
            if (count_hidden_items > 0) {
                $this.find('.nav-tab-show-more').addClass('famiau-show');
            }
            else {
                $this.find('.nav-tab-show-more').removeClass('famiau-show');
            }
        })
    }
    
    famiau_nav_tab_responsive();
    
    $(document).on('click', '.nav-tab-show-more', function (e) {
        $(this).find('.famiau-more-nav-tabs').toggleClass('active');
        return false;
    });
    
    $(window).on('resize', function () {
        famiau_nav_tab_responsive();
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
    
});