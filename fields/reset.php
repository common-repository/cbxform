<?php
if (!defined('WPINC')) {
    die;
}

/**
 * Reset field definataion of form element
 * Class CBXFormFieldReset
 */
class CBXFormFieldReset
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
        return  '<input type="reset" value="'.__('Reset','cbxform').'" />';
    }

    /**
     * Method to display the preview html on refresh (backend form edit save/refresh)
     *
     * @param array $args
     * @return string
     */
    public static function previewhtml_render( $form_item_id, $args = array() ){

        return '<input type="reset"  value="' . $args['label'] . '">';

    }

    /**
     * Method to display the form element in front end
     *
     * @param array $args
     * @return string
     */
    public static function fronthtml_render( $form_item_id, $args = array(), $form_id ){

        $html = '';

        $html .= '<div class="cbx-btn"><input type="' . $args['type'] . '" value="' . $args['label'] . '" class="cbx-actionbutton cbx-actionbutton-reset"/></div>';

        return $html;
    }
}