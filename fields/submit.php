<?php
if (!defined('WPINC')) {
    die;
}

/**
 * Submit field defination of form element
 * Class CBXFormFieldSubmit
 */
class CBXFormFieldSubmit
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
        return  '<input type="submit" value="'.__('Submit','cbxform').'" />';
    }

    /**
     * Method to display the preview html on refresh (backend form edit save/refresh)
     *
     * @param array $args
     * @return string
     */
    public static function previewhtml_render( $form_item_id, $args = array() ){

        return '<input type="submit"  value="' . $args['label'] . '">';

    }

    /**
     * Method to display the form element in front end
     *
     * @param array $args
     * @return string
     */
    public static function fronthtml_render( $form_item_id, $args = array(), $form_id ){

        $html = '';

        $cbx_ajax_icon = plugins_url('cbxform/public/css/busy.gif');
        $html .= '<div class="cbx-btn"><input type="' . $args['type'] . '" value="' . $args['label'] . '" class="cbx-actionbutton cbx-actionbutton-submit"/>
                  <input type="hidden" name="action"   value="process_form" />
                  <span data-busy="0" class="cbxform_ajax_icon"><img src="' . $cbx_ajax_icon . '"/></span></div>';

        return $html;
    }
}