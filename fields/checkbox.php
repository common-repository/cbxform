<?php

if (!defined('WPINC')) {
    die;
}

/**
 * Checkbox field defination of form element
 * Class CBXFormFieldCheckbox
 */
class CBXFormFieldCheckbox
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
        $input1_option_value = '<input type="text" class="optionlebel_value hidden" name="cbxformmetabox[fields][formitem-#id#][option][formitem-#id#-1][]" value=""/>';
        $input2_option_value = '<input type="text" class="optionlebel_value hidden" name="cbxformmetabox[fields][formitem-#id#][option][formitem-#id#-2][]" value=""/>';

        return  '<div class="optionitems">
                                          <p class="optionitem optionitem-checkbox">
                                               <input class="optionval" type="checkbox" class="checkbox" name="cbxformmetabox[fields][formitem-#id#][formitem-#id#-1]" value="">
                                               <span class="optionlebel">' . __('Option-1', 'cbxform') . '</span>' . $input1_option_value . '
                                               <span class="optionitemtrig optionitemtrig-add dashicons dashicons-plus-alt2 "  title="' . __('Add', 'cbxform') . '"></span>
                                               <span class="optionitemtrig optionitemtrig-remove dashicons dashicons-minus" title="' . __('Remove', 'cbxform') . '"></span>
                                          </p>
                                          <p class="optionitem optionitem-checkbox">
                                               <input class="optionval" type="checkbox" class="radio" name="cbxformmetabox[fields][formitem-#id#][formitem-#id#-2]" value="">
                                               <span class="optionlebel">' . __('Option-2', 'cbxform') . '</span> ' . $input2_option_value . '
                                               <span class="optionitemtrig optionitemtrig-add dashicons dashicons-plus-alt2 "  title="' . __('Add', 'cbxform') . '"></span>
                                               <span class="optionitemtrig optionitemtrig-remove dashicons dashicons-minus" title="' . __('Remove', 'cbxform') . '"></span>
                                          </p>
                                          <input type="hidden" class="option_lastcount" name="cbxformmetabox[fields][formitem-#id#][option_last_count]" value="2"/></div>';
    }

    /**
     * Method to display the preview html on refresh (backend form edit save/refresh)
     *
     * @param array $args
     * @return string
     */
    public static function previewhtml_render( $form_item_id, $args = array() ){

        $fieldhtml = '';

        if (isset($args['option'])) {
            $fieldhtml = '<div class="optionitems">';

            foreach ($args['option'] as $option_key => $option_val) {
                $checkboxnput_option_value = '<input type="text" class="optionlebel_value hidden" name="cbxformmetabox[fields][' . $form_item_id . '][option][' . $option_key . '][]" value="' . $option_val[0] . '"/>';
                $fieldhtml .= '<p class="optionitem optionitem-ceheckbox"><input type="checkbox" class="optionval" name="cbxformmetabox[fields][' . $form_item_id . '][' . $option_key . ']" value="' . $option_val[0] . '"> <span class="optionlebel">' . $option_val[0] . '</span>' . $checkboxnput_option_value;
                $fieldhtml .= '<span class="optionitemtrig optionitemtrig-add dashicons dashicons-plus-alt2 "  title="' . __('Add', 'cbxform') . '"></span>';
                $fieldhtml .= '<span class="optionitemtrig optionitemtrig-remove dashicons dashicons-minus" title="' . __('Remove', 'cbxform') . '"></span></p>';
            }
            $fieldhtml .= '<input type="hidden" class="option_lastcount" name="cbxformmetabox[fields][' . $form_item_id . '][option_last_count]" value="' . intval($args['option_last_count']) . '"/>';
            $fieldhtml .= '</div>';
        }
        return $fieldhtml;

    }

    /**
     * Method to display the form element in front end
     *
     * @param array $args
     * @return string
     */
    public static function fronthtml_render( $form_item_id,$args = array(), $form_id){

        $html = $checked = '';

        $required_string = (isset($args['required']) && $args['required'] != '' && intval($args['required']) == 1) ? ' data-rule-required="true" ' : '';
        //$required_string = '';

        if (isset($args['option'])) {
            foreach ($args['option'] as $option_key => $option_val) {
                if (isset($_SESSION['cbxform_data']['error']) && sizeof($_SESSION['cbxform_data']['error']) > 0 && isset($_SESSION['cbxform_data']['data']) && sizeof($_SESSION['cbxform_data']['data']) > 0) {
                    if (array_key_exists($form_item_id, $_SESSION['cbxform_data']['data'])) {
                        if (in_array($option_val[0], $_SESSION['cbxform_data']['data'][$form_item_id])) {
                            $checked = 'checked';
                        } else {
                            $checked = '';
                        }
                    }
                }
                $html .='<div class="cbxradio-item">
                         <input type="checkbox" class="checkbox" name="cbxformmetabox[fields][' . $form_item_id . '][]" value="' . $option_key . '" id="' . $option_val[0] . '" ' . $checked . ''.$required_string.'/>
                         <label for="' . $option_val[0] . '">' . $option_val[0] . '</label>
                         </div>';
            }
        }

        return $html;
    }
}