
var cbxformsinputkeleton = jQuery.parseJSON(cbxform.fields);

function cbxformfield_html_builder(type, cbxformitemcount) {

    cbxformskeleton_temp = cbxformsinputkeleton[type];

    console.log(cbxformskeleton_temp);

    cbxformskeleton_temp = cbxformskeleton_temp.replace(/#label#/g, type.charAt(0).toUpperCase() + type.slice(1));
    cbxformskeleton_temp = cbxformskeleton_temp.replace('#name#', type.toLowerCase() + ' : formitem-' + cbxformitemcount);
    cbxformskeleton_temp = cbxformskeleton_temp.replace(/#fielditem-label#/g, 'formitem-' + cbxformitemcount + '-label');
    cbxformskeleton_temp = cbxformskeleton_temp.replace(/#fielditem-value#/g, 'formitem-' + cbxformitemcount + '-value');
    cbxformskeleton_temp = cbxformskeleton_temp.replace(/#id#/g, cbxformitemcount);
    cbxformskeleton_temp = cbxformskeleton_temp.replace(/#type#/g, type);

    cbxformskeleton_temp = jQuery('<div/>').html(cbxformskeleton_temp).contents();

    return cbxformskeleton_temp;
}

(function ($) {
    'use strict';

    jQuery(document).ready(function ($) {

        //all common select box for this form
        $('.cbxform-chosen').chosen({width:"200px"});

        //field section show/hide
        $('.cbxformmetabox_box_fields_section').hide();

        var active_field_section = '';
        if (typeof(localStorage) != 'undefined' ) {
            //get the last active tab from storage
            active_field_section = localStorage.getItem("cbxformmetabox_box_field");
        }
        if (active_field_section != '' && $(active_field_section).length ) {
            $(active_field_section).show();
        } else {
            $('.cbxformmetabox_box_fields_section_basic').show();
        }

        $('.cbxformmetabox_box_field').on('click', 'h3', function (e) {
            e.preventDefault();
            var $this = $(this);
            $('.cbxformmetabox_box_fields_section').hide();
            $this.next('.cbxformmetabox_box_fields_section').show();

            //set the current active tab in storage
            localStorage.setItem("cbxformmetabox_box_field", $(this).data('reference'));
        });


        //single form import
        $('.cbxform_single_import').on('click',function( e ){

            var postid = $(this).data('postid');
            e.preventDefault();
            if($('#cbxform_single_import_select').val() != ''){
                if (confirm(cbxform.import_confirm)) {
                    $.ajax({
                        type: "post",
                        dataType: "json",
                        url: cbxform.ajaxurl,
                        data: {
                            action: "cbxform_import_singleform",
                            security: cbxform.nonce,
                            postid:postid,
                            formid: $('#cbxform_single_import_select').val()
                        },
                        success: function (data, textStatus, XMLHttpRequest) {
                            Ply.dialog('alert', data.msg);
                            location.reload();
                        }// end of success
                    });// end of ajax
                }
            }
            else{
                alert('Please select a field'); //todo: translation
            }

        });


        //for shortcode copy to clipboard
        var clipboard = new Clipboard('.cbxformshortcodetrigger');
        clipboard.on('success', function ( e ) {
            e.clearSelection();
        });

        //for populating selected form element on wysiwyg's cursor position/last
        $('.cbxformfield_select_wysiwyg').on('change', function (e) {
            var $this = $(this);
            wp.media.editor.insert($this.val());
        });

        $('.cbxformfield_select').on('change', function () {
            var $this = $(this);
            var target = $this.parent().siblings('.cbxformfield_select_target');
            var position = $(target).caret();
            if (position > 0) {
                target.val(target.val().substring(0, position) + $this.val() + target.val().substring(position));
            } else {
                target.val(target.val() + $this.val());
            }
        });

        //for populating all form element on wysiwyg's cursor position/last
        $('.cbxformfield_select_allfields').on('click', function (e) {
            var $this = $(this);
            wp.media.editor.insert($this.data('allfields'));
        });

        //add tabs
        var tabs = $("div.cbxform_steptabs").tabs();
        //active tabs
        $('div.cbxform_steptabs').click('tabsselect', function (event, ui) {

            //for short tag copy to clipboard
            var clipboard = new Clipboard('.cbxshorttagtrigger_label');
            clipboard.on('success', function ( e ) {
                e.clearSelection();
            });

            var active_tab = parseInt($("div.cbxform_steptabs").tabs('option', 'active'));
            var actiev_tab_id = $("div.cbxform_steptabs ul>li a").eq(active_tab).attr("href")

            //sortable each field for active tabs
            $(actiev_tab_id).sortable({
                items: '.formitem',
                placeholder: 'formitem-placeholder',
                helper: 'original',
                dropOnEmpty: true,
                forcePlaceholderSize: true,
                forceHelperSize: false,
                onDragStart: function ($item, container, _super) {
                    // Duplicate items of the no drop area
                    if (!container.options.drop)
                        $item.clone().insertAfter($item);
                    _super($item, container);
                }
            });

            //sortable each options for active tabs
            $('.optionitems').sortable({
                items: '.optionitem',
                placeholder: 'optionitem-placeholder',
                helper: 'original',
                dropOnEmpty: true,
                forcePlaceholderSize: true,
                forceHelperSize: false,
                onDragStart: function ($item, container, _super) {
                    // Duplicate items of the no drop area
                    if (!container.options.drop)
                        $item.clone().insertAfter($item);
                    _super($item, container);
                }
            });

            //dragable elements for active tabs
            $('#cbxformmetabox_fields .fielditem').draggable({
                //helper: 'clone',
                helper: function () {
                    var fieldtype = $(this).data('type');
                    var field_html = cbxformfield_html_builder(fieldtype, cbxformitemcount);
                    return field_html;
                },
                connectToSortable: actiev_tab_id,
                start: function (e, ui) {

                },
                stop: function (e, ui) {
                    var fieldtype = $(this).data('type');
                    var multiins = parseInt($(this).data('multiins'));

                    $('.cbxformmetabox_box_form').find('.last_count').val(cbxformitemcount);
                    cbxformitemcount++;

                    console.log(multiins);
                    if (multiins == 0) {
                        $(this).attr('disabled', true);
                        $(this).addClass('disabled').draggable('disable');
                    }

                },
                drag: function (e, ui) {

                }
            });
        });

        //remove tab
        $('.remove_tab').on('click', function (e) {
            e.preventDefault();
            var $this = $(this);

            Ply.dialog({
                "confirm-step": {
                    ui: "confirm",
                    data: {
                        text: cbxform.deleteconfirm,
                        ok: cbxform.deleteconfirmok, // button text
                        cancel: cbxform.deleteconfirmcancel
                    },
                    backEffect: "3d-flip[-180,180]"
                }
            }).always(function (ui) {
                if (ui.state) {
                    // Ok
                    var id = $this.siblings('a').attr('href');
                    $this.parent('li').remove();
                    $(id).remove();
                    //tabCounter--;
                    $('.cbxform_tabcounter').val(tabCounter);
                } else {
                    // Cancel
                    // ui.by — 'overlay', 'x', 'esc'
                }
            })
        });

        //edit tab
        $('.edit_tab').on('click', function (e) {
            e.preventDefault();
            var $this = $(this);

            Ply.dialog("prompt", {
                title: cbxform.edit_tab_title,
                form: {tab_title: cbxform.edit_tab_placeholder}
            }).always(function (ui) {
                if (ui.state) {
                    var id = $this.siblings('a').attr('href');
                    $(id).find(".tab-title").val(ui.data.tab_title);
                    $this.siblings('a').text(ui.data.tab_title);
                }
            });

        });

        //for form on/off in form edit
        var elem = document.querySelector('.cbxformeditjs-switch');
        var elems = Array.prototype.slice.call(document.querySelectorAll('.cbxformeditjs-switch'));

        elems.forEach(function (changeCheckbox) {
            changeCheckbox.onchange = function () {
                var enable = (changeCheckbox.checked) ? 1 : 0;
                var postid = $(changeCheckbox).attr('data-postid');
            };

            var switchery = new Switchery(changeCheckbox);
        });

        var cbxformitemcount = parseInt($('.cbxformmetabox_box_form').find('.last_count').val()) + 1;

        //delete a form item
        $('.cbxformmetabox_box_form').on('click', '.dashicons-post-trash', function (e) {
            e.preventDefault();
            var $this  = $(this);
            var parent = $this.parent('.formitem');

            Ply.dialog({
                "confirm-step": {
                    ui: "confirm",
                    data: {
                        text: cbxform.deleteconfirm,
                        ok: cbxform.deleteconfirmok, // button text
                        cancel: cbxform.deleteconfirmcancel
                    },
                    backEffect: "3d-flip[-180,180]"
                }
            }).always(function (ui) {
                if (ui.state) {
                    // Ok
                    var available_single_values = $.parseJSON($('#single_instance_inputs_avaiable').val());
                    parent.remove();
                    if ($.inArray(parent.data('type'),available_single_values) > -1) {

                        jQuery(".fielditem").each(function (a, c) {

                            if ($(this).data('type') === parent.data('type')) {
                                $(this).attr('disabled', false);
                                $(this).removeClass('disabled').draggable('enable');
                            }

                        });
                    }
                } else {
                    // Cancel
                    // ui.by — 'overlay', 'x', 'esc'
                }
            })
        });

        //edit a form item
        $('.cbxformmetabox_box_form').on('click', '.dashicons-admin-tools', function (e) {

            e.preventDefault();
            var $this = $(this);
            var parent = $this.parent('.formitem');

            parent.attr('style', '');
            parent.find('.formitem_inside_wrap').toggle();


            // on edit for label change field heading/text
            parent.find(".label").on('input', function () {
                var value = $(this).val();
                parent.find('.formitemlabel').text(value);
            });

            // on edit option label change
            parent.find(".optionlebel_value").on('input', function () {
                var value = $(this).val();
                $(this).siblings('.optionlebel').text(value);
            });

            // on edit add option
            parent.find('.optionitemtrig-add').on('click', function () {
                var optionitemcount = parseInt(parent.find('.option_lastcount').val());
                var incremented_opt_val = optionitemcount + 1
                var opt_parent = $(this).parent();
                var cloned_element = opt_parent.clone(true, true);

                var form_item = parseInt(parent.attr('id').replace('formitem-', ''));

                //organize the cloned option
                cloned_element.find('.optionval').attr('name', 'cbxformmetabox[fields][formitem-' + form_item + '][formitem-' + form_item + '-' + incremented_opt_val + ']');
                cloned_element.find('.optionlebel').html('Option-' + incremented_opt_val);
                cloned_element.find('.optionlebel_value').attr('name', 'cbxformmetabox[fields][formitem-' + form_item + '][option][formitem-' + form_item + '-' + incremented_opt_val + '][]');
                cloned_element.find('.optionlebel_value').val('Option-' + incremented_opt_val);

                //insert after the option element clicked
                opt_parent.after(cloned_element);

                //update the last count value to store for next increment start
                parent.find('.option_lastcount').val(incremented_opt_val);
            });

            // on edit...delete option
            parent.find('.optionitemtrig-remove').on('click', function () {
                $(this).parent().remove();
            });

            // on edit...edit option
            parent.find('.optionlebel').on('click', function (e) {
                $(this).siblings('.optionlebel_value').toggle();
            });

        });


        //sortable each field on first entry
        $('#step-1').sortable({
            items: '.formitem',
            placeholder: 'formitem-placeholder',
            helper: 'original',
            dropOnEmpty: true,
            forcePlaceholderSize: true,
            forceHelperSize: false,
            onDragStart: function ($item, container, _super) {
                // Duplicate items of the no drop area
                if (!container.options.drop)
                    $item.clone().insertAfter($item);
                _super($item, container);
            }
        });

        //sortable each options on first entry
        $('.optionitems').sortable({
            items: '.optionitem',
            placeholder: 'optionitem-placeholder',
            helper: 'original',
            dropOnEmpty: true,
            forcePlaceholderSize: true,
            forceHelperSize: false,
            onDragStart: function ($item, container, _super) {
                // Duplicate items of the no drop area
                if (!container.options.drop)
                    $item.clone().insertAfter($item);
                _super($item, container);
            }
        });

        //dragable element on first entry
        $('#cbxformmetabox_fields .fielditem').draggable({
            //helper: 'clone',
            helper: function () {
                var fieldtype = $(this).data('type');
                var field_html = cbxformfield_html_builder(fieldtype, cbxformitemcount);
                return field_html;
            },
            connectToSortable: '#step-1',
            start: function (e, ui) {
            },
            stop: function (e, ui) {
                var fieldtype = $(this).data('type');
                var multiins = parseInt($(this).data('multiins'));

                $('.cbxformmetabox_box_form').find('.last_count').val(cbxformitemcount);
                cbxformitemcount++;

                if (multiins == 0) {
                    $(this).attr('disabled', true);
                    $(this).addClass('disabled').draggable('disable');
                }
            },
            drag: function (e, ui) {

            }
        });

        //get the single instance values saved
        var single_values = $.parseJSON($('#single_instance_inputs_rendered').val());

        //disable||stop dragable the single instance form element such as(submit, reset) values saved in left side
        jQuery(".fielditem").each(function (a, c) {

            if ($.inArray($(this).data('type'), single_values) > -1) {
                $(this).attr('disabled', true);
                $(this).addClass('disabled').draggable('disable');
            }

        });



    });

})(jQuery);
