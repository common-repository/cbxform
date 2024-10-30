<?php
if (!defined('WPINC')) {
    die;
}

/**
 * Minval control of form element
 * Class CBXFormFieldControlMinval
 */
class CBXFormFieldControlMinval
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
        return  '<div class="inputlabel inputlabel_min">' . __('Min Value', 'cbxform') . '</div><div class="inputcontrol inputcontrol_minval"><input type="text" value=""  name="cbxformmetabox[fields][formitem-#id#][minval]" /></div>';
    }

    /**
     * Method to display the preview html on refresh (backend form edit save/refresh)
     *
     * @param array $args
     * @return string
     */
    public static function previewhtml_render($form_item_id,$args = array()){
        return '<div class="inputlabel inputlabel_min">' . __('Min Value', 'cbxform') . '</div><div class="inputcontrol inputcontrol_minval"><input type="text" value="' . $args['minval'] . '"  name="cbxformmetabox[fields][' . $form_item_id . '][minval]" /></div>';;
    }

}