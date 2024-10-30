
(function ($) {
    'use strict';

    jQuery(document).ready(function ($) {

        //multiple form import
        $($(".wrap h1")[0]).append(cbxform.import_modal_button);

        //for shortcode copy to clipboard
        var clipboard = new Clipboard('.cbxformshortcodetrigger');
        clipboard.on('success', function ( e ) {
            e.clearSelection();
        });

        //for form on/off in form listing
        var elem = document.querySelector('.cbxformjs-switch');
        var elems = Array.prototype.slice.call(document.querySelectorAll('.cbxformjs-switch'));

        elems.forEach(function (changeCheckbox) {
            changeCheckbox.onchange = function () {

                var enable = (changeCheckbox.checked) ? 1 : 0;
                var postid = $(changeCheckbox).attr('data-postid');
                //ajax call for sending test notification
                jQuery.ajax({
                    type: "post",
                    dataType: "json",
                    url: cbxform.ajaxurl,
                    data: {
                        action: "cbxform_enable_disable",
                        security: cbxform.nonce,
                        enable: enable,
                        postid: postid
                    },
                    success: function (data, textStatus, XMLHttpRequest) {
                        //console.log(data);
                    }// end of success
                });// end of ajax
            };

            var switchery = new Switchery(changeCheckbox);
        });

    });

})(jQuery);
