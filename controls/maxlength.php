<?php
if (!defined('WPINC')) {
    die;
}

/**
 * Maxlength control of form element
 * Class CBXFormFieldControlMaxlength
 */
class CBXFormFieldControlMaxlength
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
        return  '<div class="inputlabel inputlabel_maxlength">' . __('Maxlength Value', 'cbxform') . '</div><div class="inputcontrol inputcontrol_maxlength"><input type="text" value=""  name="cbxformmetabox[fields][formitem-#id#][maxlength]" /></div>';
    }

    /**
     * Method to display the preview html on refresh (backend form edit save/refresh)
     *
     * @param array $args
     * @return string
     */
    public static function previewhtml_render($form_item_id,$args = array()){
        return '<div class="inputlabel inputlabel_maxlength">' . __('Maxlength Value', 'cbxform') . '</div><div class="inputcontrol inputcontrol_maxlength"><input type="text" value="' . $args['maxlength'] . '"  name="cbxformmetabox[fields][' . $form_item_id . '][maxlength]" /></div>';
    }

}