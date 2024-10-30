<?php
if (!defined('WPINC')) {
    die;
}

/**
 * Select Control of form element
 * Class CBXFormFieldControlSelect
 */
class CBXFormFieldControlCols
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
        return  '<div class="inputlabel inputlabel_cols">' . __('Cols', 'cbxform') . '</div><div class="inputcontrol inputcontrol_cols"><input type="text" value=""  name="cbxformmetabox[fields][formitem-#id#][cols]" /></div>';
    }

    /**
     * Method to display the preview html on refresh (backend form edit save/refresh)
     *
     * @param array $args
     * @return string
     */
    public static function previewhtml_render($form_item_id,$args = array()){
        return '<div class="inputlabel inputlabel_cols">' . __('Cols', 'cbxform') . '</div><div class="inputcontrol inputcontrol_rows"><input type="text" value="' . $args['cols'] . '"  name="cbxformmetabox[fields][' . $form_item_id . '][cols]" /></div>';
    }

}