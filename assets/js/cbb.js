/*
* Innitiate Color Picker
* and set our custom values
*/
(function ($) {
    "use strict";

    var default_color = '#FFFFFF';

    function pickColor(color) {
        $('#cbb-set-color').val(color);
    }
    function toggle_text() {
        var cbb_set_color = $('#cbb-set-color');
        if ('' === cbb_set_color.val().replace('#', '')) {
            cbb_set_color.val(default_color);
            pickColor(default_color);
        } else {
            pickColor(cbb_set_color.val());
        }
    }

    $(document).ready(function () {
        var cbb_set_color = $('#cbb-set-color');
        cbb_set_color.wpColorPicker({
            change: function (event, ui) {
                pickColor(cbb_set_color.wpColorPicker('color'));
            },
            clear: function () {
                pickColor('');
            }
        });
        $('#cbb-set-color').click(toggle_text);

        toggle_text();

    });

}(jQuery));