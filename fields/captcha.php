<?php
if (!defined('WPINC')) {
    die;
}

/**
 * Captcha feild defination of foem element
 * Class CBXFormFieldCaptcha
 */
class CBXFormFieldCaptcha
{

    public $settings;

    public function __construct()
    {
        $this->settings = new CBXForm_Settings_API();

    }

    /**
     * Method to display the preview html on drop (drag and drop mode for form builder admin part)
     *
     * @param array $args
     * @return string
     */
    public static function previewhtml_drop($args = array()){
        return  '<div class="g-recaptcha" data-sitekey=""></div>';
    }

    /**
     * Method to display the preview html on refresh (backend form edit save/refresh)
     *
     * @param array $args
     * @return string
     */
    public static function previewhtml_render( $form_item_id, $args = array() ){
        $settings = new CBXForm_Settings_API();
        $recaptcha_site_key = $settings->get_option('captcha_site_key', 'cbxform_security', '');

        return '<div class="g-recaptcha" data-sitekey="' . $recaptcha_site_key . '"></div>';

    }

    /**
     * Method to display the form element in front end
     *
     * @param array $args
     * @return string
     */
    public static function fronthtml_render( $form_item_id,$args = array(), $form_id ){

        $html = '';
        $settings = new CBXForm_Settings_API();

        //captcha site and secret key
        $captcha_site_key   = $settings->get_option('captcha_site_key','cbxform_security','');
        $captcha_secret_key = $settings->get_option('captcha_secret_key','cbxform_security','');

        $html .= '<div class="g-recaptcha" data-sitekey="' . $captcha_site_key . '"></div><input type="hidden" name="cbxformmetabox[fields][' . $form_item_id . ']" value="' . $captcha_secret_key . '"/>';

        return $html;
    }
}