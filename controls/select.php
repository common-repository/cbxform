<?php
if (!defined('WPINC')) {
    die;
}

/**
 * Select Control of form element
 * Class CBXFormFieldControlSelect
 */
class CBXFormFieldControlSelect
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
        return  '<div class="inputlabel inputlabel_selectbox">' . __('Multiselect', 'cbxform') . '</div><div class="inputcontrol inputcontrol_selectbox"><input type="hidden" value="0" name="cbxformmetabox[fields][formitem-#id#][select]"><input type="checkbox" value="1" name="cbxformmetabox[fields][formitem-#id#][select]"></div>';
    }

    /**
     * Method to display the preview html on refresh (backend form edit save/refresh)
     *
     * @param array $args
     * @return string
     */
    public static function previewhtml_render($form_item_id,$args = array()){

        $select =  (isset($args['select']) && $args['select'] != '') ? intval($args['select']) : 0;

        return '<div class="inputlabel inputlabel_selectbox">' . __('Multiselect', 'cbxform') . '</div><div class="inputcontrol inputcontrol_selectbox"><input type="hidden" value="0" name="cbxformmetabox[fields]['.$form_item_id.'][select]"><input type="checkbox" ' . checked($select, 1, false) . ' value="1" name="cbxformmetabox[fields][' . $form_item_id . '][select]"></div>';
    }

}