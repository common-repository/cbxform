<?php
if (!defined('WPINC')) {
    die;
}

/**
 * Maxval control of form element
 * Class CBXFormFieldControlMaxval
 */
class CBXFormFieldControlMaxval
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
        return  '<div class="inputlabel inputlabel_max">' . __('Max Value', 'cbxform') . '</div><div class="inputcontrol inputcontrol_maxval"><input type="text" value=""  name="cbxformmetabox[fields][formitem-#id#][maxval]" /></div>';
    }

    /**
     * Method to display the preview html on refresh (backend form edit save/refresh)
     *
     * @param array $args
     * @return string
     */
    public static function previewhtml_render($form_item_id,$args = array()){
        return '<div class="inputlabel inputlabel_max">' . __('Max Value', 'cbxform') . '</div><div class="inputcontrol inputcontrol_maxval"><input type="text" value="' . $args['maxval'] . '"  name="cbxformmetabox[fields][' . $form_item_id . '][maxval]" /></div>';
    }

}