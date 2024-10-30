<?php
if (!defined('WPINC')) {
    die;
}

/**
 * Paragraph field defination of form element
 * Class CBXFormFieldParagraph
 */
class CBXFormFieldParagraph
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
        return  '<p class="paragraph_item">'.__('Pragraph','cbxform').'</p>';
    }

    /**
     * Method to display the preview html on refresh (backend form edit save/refresh)
     *
     * @param array $args
     * @return string
     */
    public static function previewhtml_render( $form_item_id, $args = array() ){

        return '<p>' . $args['paragraph'] . '</p>';

    }

    /**
     * Method to display the form element in front end
     *
     * @param array $args
     * @return string
     */
    public static function fronthtml_render( $form_item_id,$args = array(), $form_id ){

        return '<p>' . $args['paragraph'] . '</p>';
    }

}