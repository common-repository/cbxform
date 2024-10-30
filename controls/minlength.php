<?php
if (!defined('WPINC')) {
    die;
}

/**
 * Minlength control of form element
 * Class CBXFormFieldControlMinlength
 */
class CBXFormFieldControlMinlength
{

    public function __construct()
    {


    }

    /**
     * Method to display the preview html on drop (drag and drop mode for form builder admin part)
     *
     * @param array $args
     * @return string
     */
    public static function previewhtml_drop($args = array()){
        return  '<div class="inputlabel inputlabel_minlength">' . __('Minlength Value', 'cbxform') . '</div><div class="inputcontrol inputcontrol_minlength"><input type="text" value=""  name="cbxformmetabox[fields][formitem-#id#][minlength]" /></div>';
    }

    /**
     * Method to display the preview html on refresh (backend form edit save/refresh)
     *
     * @param array $args
     * @return string
     */
    public static function previewhtml_render($form_item_id,$args = array()){

        return '<div class="inputlabel inputlabel_minlength">' . __('Minlength Value', 'cbxform') . '</div><div class="inputcontrol inputcontrol_minlength"><input type="text" value="' . $args['minlength'] . '"  name="cbxformmetabox[fields][' . $form_item_id . '][minlength]" /></div>';

    }

}