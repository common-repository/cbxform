<?php
if (!defined('WPINC')) {
    die;
}

/**
 * Textarea field defination of form element
 * Class CBXFormFieldTextarea
 */
class CBXFormFieldTextarea
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
        return  '<textarea rows="4" cols="50" name="cbxformmetabox[fields][formitem-#id#]" ></textarea>';
    }

    /**
     * Method to display the preview html on refresh (backend form edit save/refresh)
     *
     * @param array $args
     * @return string
     */
    public static function previewhtml_render( $form_item_id, $args = array() ){

        $minlength       = (isset($args['minlength']) && $args['minlength'] != '') ? 'minlength = "'.intval($args['minlength']).'" data-rule-minlength = "'.intval($args['minlength']).'"' : '';
        $maxlength       = (isset($args['maxlength']) && $args['maxlength'] != '') ? 'maxlength = "'.intval($args['maxlength']).'" data-rule-maxlength = "'.intval($args['maxlength']).'"' : '';
        $rows            = (isset($args['rows']) && $args['rows'] != '') ? intval($args['rows']) : 4;
        $cols            = (isset($args['cols']) && $args['cols'] != '') ? intval($args['cols']) : 50;

        return  '<textarea '.$minlength.' '.$maxlength.' rows="'.$rows.'" cols="'.$cols.'" name="cbxformmetabox[fields]['.$form_item_id.']" id="' . $form_item_id . '"></textarea>';
    }

    /**
     * Method to display the form element in front end
     *
     * @param array $args
     * @return string
     */
    public static function fronthtml_render( $form_item_id,$args = array() , $form_id){

        $returned_value = '';

        if (isset($_SESSION['cbxform_data']['error']) && sizeof($_SESSION['cbxform_data']['error']) > 0 && isset($_SESSION['cbxform_data']['data']) && sizeof($_SESSION['cbxform_data']['data']) > 0) {
            if (array_key_exists($form_item_id, $_SESSION['cbxform_data']['data'])) {
                $returned_value = $_SESSION['cbxform_data']['data'][$form_item_id];
            }
        }

        $required_string = (isset($args['required']) && $args['required'] != '' && intval($args['required'])) ? ' required ' : '';
        $minlength       = (isset($args['minlength']) && $args['minlength'] != '') ? 'minlength = "'.intval($args['minlength']).'" data-rule-minlength = "'.intval($args['minlength']).'"' : '';
        $maxlength       = (isset($args['maxlength']) && $args['maxlength'] != '') ? 'maxlength = "'.intval($args['maxlength']).'" data-rule-maxlength = "'.intval($args['maxlength']).'"' : '';
        $rows            = (isset($args['rows']) && $args['rows'] != '') ? intval($args['rows']) : 4;
        $cols            = (isset($args['cols']) && $args['cols'] != '') ? intval($args['cols']) : 50;


        return '<textarea '.$minlength.' '.$maxlength.' rows="'.$rows.'" cols="'.$cols.'" name="cbxformmetabox[fields][' . $form_item_id . ']" id="' . $form_item_id . '" ' . $required_string . '">' . $returned_value . '</textarea>';

    }
}