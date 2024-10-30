<?php
if (!defined('WPINC')) {
    die;
}

/**
 * Label control of form element
 * Class CBXFormFieldControlLabel
 */
class CBXFormFieldControlLabel
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

        $html  = '<div class="inputlabel">' . __('Label', 'cbxform') . '</div><div class="inputcontrol"><input type="text" value="#label#" class="label" name="cbxformmetabox[fields][formitem-#id#][label]" /></div>';
        $html .= '<div class="inputlabel">' . __('Show Label', 'cbxform') . '</div><div class="inputcontrol"><input type="hidden" value="0" name="cbxformmetabox[fields][formitem-#id#][show_label]"><input type="checkbox" value="1" name="cbxformmetabox[fields][formitem-#id#][show_label]" checked></div>';

        return $html;
    }

    /**
     * Method to display the preview html on refresh (backend form edit save/refresh)
     *
     * @param array $args
     * @return string
     */
    public static function previewhtml_render($form_item_id,$args = array()){

        $show_label =  (isset($args['show_label']) && $args['show_label'] != '') ? intval($args['show_label']) : 0;

        $html  = '<div class="inputlabel">' . __('Label', 'cbxform') . '</div><div class="inputcontrol"><input class="label" type="text" name="cbxformmetabox[fields][' . $form_item_id . '][label]" value="' . $args['label'] . '" /></div>';
        $html .= '<div class="inputlabel">' . __('Show Label', 'cbxform') . '</div><div class="inputcontrol"><input type="hidden" value="0" name="cbxformmetabox[fields]['.$form_item_id.'][show_label]"><input type="checkbox" ' . checked($show_label, 1, false) . ' value="1" name="cbxformmetabox[fields][' . $form_item_id . '][show_label]"></div>';

        return $html;
    }
}