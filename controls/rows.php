<?php
if (!defined('WPINC')) {
    die;
}

/**
 * Select Control of form element
 * Class CBXFormFieldControlSelect
 */
class CBXFormFieldControlRows
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
        return  '<div class="inputlabel inputlabel_rows">' . __('Rows', 'cbxform') . '</div><div class="inputcontrol inputcontrol_rows"><input type="text" value=""  name="cbxformmetabox[fields][formitem-#id#][rows]" /></div>';
    }

    /**
     * Method to display the preview html on refresh (backend form edit save/refresh)
     *
     * @param array $args
     * @return string
     */
    public static function previewhtml_render($form_item_id,$args = array()){
        return '<div class="inputlabel inputlabel_rows">' . __('Rows', 'cbxform') . '</div><div class="inputcontrol inputcontrol_rows"><input type="text" value="' . $args['rows'] . '"  name="cbxformmetabox[fields][' . $form_item_id . '][rows]" /></div>';
    }

}