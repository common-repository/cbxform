<?php
if (!defined('WPINC')) {
    die;
}

/**
 * Required control of form element
 * Class CBXFormFieldControlRequired
 */
class CBXFormFieldControlRequired
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
        return  '<div class="inputlabel inputlabel_required">' . __('Required', 'cbxform') . '</div><div class="inputcontrol_required"><input type="hidden" value="0" name="cbxformmetabox[fields][formitem-#id#][required]"><input type="checkbox" value="1" name="cbxformmetabox[fields][formitem-#id#][required]"></div>';;
    }

    /**
     * Method to display the preview html on refresh (backend form edit save/refresh)
     *
     * @param array $args
     * @return string
     */
    public static function previewhtml_render($form_item_id,$args = array()){
        return '<input type="hidden"  value="0" name="cbxformmetabox[fields][' . $form_item_id . '][required]"><div class="inputlabel_required">' . __('Required', 'cbxform') . '</div><div class="inputcontrol_required"><input type="checkbox" ' . checked($args['required'], 1, false) . ' value="1" name="cbxformmetabox[fields][' . $form_item_id . '][required]"></div>';
    }

}// end of class

?>