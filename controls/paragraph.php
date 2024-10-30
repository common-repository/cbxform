<?php
if (!defined('WPINC')) {
    die;
}

/**
 * Paragraph control of form element
 * Class CBXFormFieldControlParagraph
 */
class CBXFormFieldControlParagraph
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
        return  '<div class="inputlabel inputlabel_paragraph">' . __('Text', 'cbxform') . '</div><div class="inputcontrol inputcontrol_paragraph"><input type="text" value="" name="cbxformmetabox[fields][formitem-#id#][paragraph]" /></div>';
    }

    /**
     * Method to display the preview html on refresh (backend form edit save/refresh)
     *
     * @param array $args
     * @return string
     */
    public static function previewhtml_render($field_item_id,$args = array()){
        return '<div class="inputlabel_paragraph">' . __('Text', 'cbxform') . '</div><div class="inputcontrol inputcontrol_paragraph"><input type="text" value="' . $args['paragraph'] . '" name="cbxformmetabox[fields][' . $field_item_id . '][paragraph]" /></div>';
    }

}