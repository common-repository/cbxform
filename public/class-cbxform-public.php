<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://codeboxr.com/
 * @since      1.0.0
 *
 * @package    Cbxform
 * @subpackage Cbxform/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Cbxform
 * @subpackage Cbxform/public
 * @author     http://codeboxr.com/ <info@codeboxr.com>
 */

require 'validation/vendor/autoload.php';                                                      // Requiring libraries from the composer installation.

use Respect\Validation\Validator as cbxform_validate;                                          // Aliasing the validator with a short name for easy usage.
use Respect\Validation\Exceptions\ValidationException;                                         // Aliasing the exception we use to catch validation errors.

class Cbxform_Public
{
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;
    //for setting
    private $settings_api;

    public $mail_format;

    public $showform_successful;

    public $mail_from_address;

    public $mail_from_name;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string $plugin_name The name of the plugin.
     * @param      string $version The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->settings_api = new CBXForm_Settings_API();
        $this->showform_successful = $this->settings_api->get_option('showform_successful', 'cbxform_global', 'off');
    }

    /**
     * Plugin Initialize
     */
    public function init()
    {
        //adding the default form fields
        require_once(CBXFORM_ROOT_PATH . 'fields/text.php');
        require_once(CBXFORM_ROOT_PATH . 'fields/number.php');
        require_once(CBXFORM_ROOT_PATH . 'fields/email.php');
        require_once(CBXFORM_ROOT_PATH . 'fields/textarea.php');
        require_once(CBXFORM_ROOT_PATH . 'fields/select.php');
        require_once(CBXFORM_ROOT_PATH . 'fields/radio.php');
        require_once(CBXFORM_ROOT_PATH . 'fields/checkbox.php');
        require_once(CBXFORM_ROOT_PATH . 'fields/hr.php');
        require_once(CBXFORM_ROOT_PATH . 'fields/paragraph.php');
        require_once(CBXFORM_ROOT_PATH . 'fields/captcha.php');
        require_once(CBXFORM_ROOT_PATH . 'fields/submit.php');
        require_once(CBXFORM_ROOT_PATH . 'fields/reset.php');

        //adding the default form controls
        require_once(CBXFORM_ROOT_PATH . 'controls/label.php');
        require_once(CBXFORM_ROOT_PATH . 'controls/required.php');
        require_once(CBXFORM_ROOT_PATH . 'controls/maxlength.php');
        require_once(CBXFORM_ROOT_PATH . 'controls/minlength.php');
        require_once(CBXFORM_ROOT_PATH . 'controls/maxval.php');
        require_once(CBXFORM_ROOT_PATH . 'controls/minval.php');
        require_once(CBXFORM_ROOT_PATH . 'controls/select.php');
        require_once(CBXFORM_ROOT_PATH . 'controls/paragraph.php');
        require_once(CBXFORM_ROOT_PATH . 'controls/rows.php');
        require_once(CBXFORM_ROOT_PATH . 'controls/cols.php');
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/cbxform-public.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {
        $url = 'https://www.google.com/recaptcha/api.js';
        $url = add_query_arg(
            array(
                'onload' => 'recaptchaCallback',
                'hl' => $this->settings_api->get_option('captcha_language','cbxform_security','en')
            ), $url
        );
        wp_register_script($this->plugin_name . 'front-captcha', $url, array(), $this->version, false);
        wp_register_script($this->plugin_name . '-validate', plugin_dir_url(__FILE__) . 'js/jquery.validate.min.js', array('jquery'), $this->version, false);
        wp_register_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/cbxform-public.js', array('jquery'), $this->version, false);

        $cbxform_translation = array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('cbxform'),
            'required' => __('This field is required.', $this->plugin_name),
            'remote' => __('Please fix this field.', $this->plugin_name),
            'email' => __('Please enter a valid email address.', $this->plugin_name),
            'url' => __('Please enter a valid URL.', $this->plugin_name),
            'date' => __('Please enter a valid date.', $this->plugin_name),
            'dateISO' => __('Please enter a valid date ( ISO ).', $this->plugin_name),
            'number' => __('Please enter a valid number.', $this->plugin_name),
            'digits' => __('Please enter only digits.', $this->plugin_name),
            'equalTo' => __('Please enter the same value again.', $this->plugin_name),
            'maxlength' => __('Please enter no more than {0} characters.', $this->plugin_name),
            'minlength' => __('Please enter at least {0} characters.', $this->plugin_name),
            'rangelength' => __('Please enter a value between {0} and {1} characters long.', $this->plugin_name),
            'range' => __('Please enter a value between {0} and {1}.', $this->plugin_name),
            'max' => __('Please enter a value less than or equal to {0}.', $this->plugin_name),
            'min' => __('Please enter a value greater than or equal to {0}.', $this->plugin_name),
            'recaptcha' => __('Please check the captcha.', $this->plugin_name),

        );
        wp_localize_script($this->plugin_name, 'cbxform', $cbxform_translation);

        wp_enqueue_script($this->plugin_name . 'front-captcha');
        wp_enqueue_script($this->plugin_name . '-validate');
        wp_enqueue_script($this->plugin_name);
    }


    /**
     * Callback for 'wp_mail_from_name' filter
     *
     * @param type $name
     * @return type
     */
    public function cbxform_wp_mail_from_name($name)
    {
        return str_replace('{sitename}', get_bloginfo('name'), $this->mail_from_name);
    }

    /**
     * Callback for 'wp_mail_from' filter
     *
     * @param type $content_type
     * @return type
     */
    public function cbxform_wp_mail_from($content_type)
    {
        return $this->mail_from_address;
    }

    /**
     * Filter the format of the sending mail
     *
     * @param type $content_type
     * @return string
     */
    public function cbxform_mail_content_type($content_type)
    {
        if ($this->mail_format == 'html') {
            return 'text/html';
        } elseif ($this->mail_format == 'plain') {
            return 'text/plain';
        } elseif ($this->mail_format == 'multipart') {
            return 'multipart/mixed';
        } else {
            return 'text/plain';
        }
    }

    /**
     * Formats the header of the mail
     *
     * @param $cc
     * @param $bcc
     * @param $reply_to
     * @return array
     */
    public function email_header($cc, $bcc, $reply_to = '')
    {
        $cc_array = explode(',', $cc);
        $bcc_array = explode(',', $bcc);

        foreach ($cc_array as $key => $cc) {
            $headers[] = 'CC: ' . $cc;
        }

        foreach ($bcc_array as $key => $bcc) {
            $headers[] = 'BCC: ' . $bcc;
        }

        if($reply_to !=''){
            $headers[]   = 'Reply-To: '.$reply_to;
        }

        return $headers;
    }


    /**
     * Parse special keywords for form labels
     * @param $search
     * @param $replace
     * @param $subject
     * @return mixed
     */
    public function parse_label($search,$replace,$subject){
        return str_replace('{' . $search . '-label}', $replace, $subject);
    }
    /**
     * Parse special keywords for form labels
     * @param $search
     * @param $replace
     * @param $subject
     * @return mixed
     */
    public function parse_value($search,$replace,$subject){
        return str_replace('{' . $search . '-value}', $replace, $subject);
    }
    /**
     * Parse The message body by form fields.
     */
    public function parse_msgbody($email_body, $posted_values, $saved_values)
    {

        foreach ($posted_values as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $k => $v) {
                    $email_body = $this->parse_label($key,$saved_values[$key]['label'],$email_body);
                    $email_body = $this->parse_value($key,$v,$email_body);
                }
            } else {
                $email_body = $this->parse_label($key,$saved_values[$key]['label'],$email_body);
                $email_body = $this->parse_value($key,$value,$email_body);
            }
        }

        return apply_filters('cbxform_parse_msgbody',$email_body, $posted_values, $saved_values);
    }


    /**
     * Sending mail by calling hook 'onCBXFormValidationComplete'
     * @param $return
     * @param $meta
     * @return mixed
     */
    public function onCBXFormValidationComplete( $return, $meta ){

        $email_status = $this->action_form($return['data'], $meta);

        if (is_array($email_status) && sizeof($email_status) > 0) {
            foreach ($email_status as $key => $value) {
                $return['success'][] = array('msg' => $value['msg'], 'show' => false);
            }
        }else{
            foreach ($email_status as $key => $value) {
                $return['warning'][] = array('msg' => __('Unable to Send mail','cbxform'), 'show' => false);
            }
        }

        return $return;
    }

    /**
     * Form Mail Action
     *
     * @param $data
     * @param $metavalue_key
     * @return array
     * @throws \Html2Text\Html2TextException
     */
    public function action_form($data, $metavalue_key)
    {
        $settings = $metavalue_key['settings'];

        $fields = $metavalue_key['fields'];

        $return = array();

        $html = $admin_email_body = $user_email_body = '';

        if (isset($settings['cbxform_email_admin']) && sizeof($settings['cbxform_email_admin']) > 0) {
            if ($settings['cbxform_email_admin']['status'][0] == 'on') {
                $this->mail_format       = $settings['cbxform_email_admin']['format'][0];
                $this->mail_from_address = ($settings['cbxform_email_admin']['from'][0] != '') ? $settings['cbxform_email_admin']['from'][0] : get_bloginfo('admin_email');
                $this->mail_from_name    = $this->parse_msgbody($settings['cbxform_email_admin']['name'][0], $data, $fields);

                $to                  = $this->parse_msgbody($settings['cbxform_email_admin']['to'][0], $data, $fields);
                $header              = $this->email_header($this->parse_msgbody($settings['cbxform_email_admin']['cc'][0], $data, $fields), $this->parse_msgbody($settings['cbxform_email_admin']['bcc'][0], $data, $fields), $this->parse_msgbody($settings['cbxform_email_admin']['reply_to'][0], $data, $fields));
                $subject             = $this->parse_msgbody($settings['cbxform_email_admin']['subject'][0], $data, $fields);
                $admin_email_heading = $this->parse_msgbody($settings['cbxform_email_admin']['heading'][0], $data, $fields);
                $admin_email_body    = $this->parse_msgbody($settings['cbxform_email_admin']['body'][0], $data, $fields);

                $emailTemplate = new CBXFormEmailTemplate();
                $message = $emailTemplate->getHtmlTemplate();
                $message = str_replace('{mainbody}', $admin_email_body, $message); //replace mainbody
                $message = str_replace('{emailheading}', $admin_email_heading, $message); //replace emailbody

                if ($this->mail_format == 'html') {
                    $message = $emailTemplate->htmlEmeilify($message);
                } elseif ($this->mail_format == 'plain') {
                    $message = $emailTemplate->htmlEmeilify($message);
                    $message = Html2Text\Html2Text::convert($message);
                    $message = Html2Text\Html2Text::fixNewlines($message);
                }

                add_filter('wp_mail_from', array($this, 'cbxform_wp_mail_from'));
                add_filter('wp_mail_from_name', array($this, 'cbxform_wp_mail_from_name'));
                $admin_email_status = wp_mail($to, $subject, $message, $header);
                remove_filter('wp_mail_from', array($this, 'cbxform_wp_mail_from'));
                remove_filter('wp_mail_from_name', array($this, 'cbxform_wp_mail_from_name'));

                if ($admin_email_status) {
                    $return['admin'] = array('msg' => __('Admin Mail Send Sucessfully', 'cbxform'));
                }

            }

            $html = $message = '';

            if ($settings['cbxform_email_user']['status'][0] == 'on') {

                $this->mail_format       = $settings['cbxform_email_user']['format'][0];
                $this->mail_from_address = ($settings['cbxform_email_user']['from'][0] != '') ? $settings['cbxform_email_user']['from'][0] : get_bloginfo('admin_email');
                $this->mail_from_name    = $this->parse_msgbody($settings['cbxform_email_user']['name'][0], $data, $fields);

                $to                 = $this->parse_msgbody($settings['cbxform_email_user']['to'][0], $data, $fields);
                //$header             = $this->email_header($this->parse_msgbody($settings['cbxform_email_user']['cc'][0], $data, $fields), $this->parse_msgbody($settings['cbxform_email_user']['bcc'][0], $data, $fields));
                $header             = '';
                $subject            = $this->parse_msgbody($settings['cbxform_email_user']['subject'][0], $data, $fields);
                $user_email_heading = $this->parse_msgbody($settings['cbxform_email_user']['heading'][0], $data, $fields);
                $user_email_body    = $this->parse_msgbody($this->parse_msgbody($settings['cbxform_email_user']['body'][0], $data, $fields), $data, $fields);

                $emailTemplate = new CBXFormEmailTemplate();
                $message = $emailTemplate->getHtmlTemplate();
                $message = str_replace('{mainbody}', $user_email_body, $message); //replace mainbody
                $message = str_replace('{emailheading}', $user_email_heading, $message); //replace emailbody

                if ($this->mail_format == 'html') {
                    $message = $emailTemplate->htmlEmeilify($message);
                } elseif ($this->mail_format == 'plain') {
                    $message = $emailTemplate->htmlEmeilify($message);
                    $message = Html2Text\Html2Text::convert($message);
                    $message = Html2Text\Html2Text::fixNewlines($message);
                }

                add_filter('wp_mail_from', array($this, 'cbxform_wp_mail_from'));
                add_filter('wp_mail_from_name', array($this, 'cbxform_wp_mail_from_name'));
                $user_email_status = wp_mail($to, $subject, $message, $header);
                remove_filter('wp_mail_from', array($this, 'cbxform_wp_mail_from'));
                remove_filter('wp_mail_from_name', array($this, 'cbxform_wp_mail_from_name'));

                if ($user_email_status) {
                    $return['user'] = array('msg' => __('User Mail Send Sucessfully'), 'cbxform');
                }
            }
        }

        return $return;
    }


    /**
     * Process the submit form
     */
    public function process_form()
    {
        if (isset($_POST['cbxformmetabox']) && sizeof($_POST['cbxformmetabox']) > 0) {

            $return = $error = $data = array();
            $metavalue_key = $_POST['cbxformmetabox'];


            if (isset($metavalue_key['post_id']) && $metavalue_key['post_id'] != null) {
                $meta = get_post_meta($metavalue_key['post_id'], '_cbxformmeta', TRUE);
                $form_id = intval($metavalue_key['post_id']);

                if ('cbxform' !== get_post_type($form_id)) {
                    $error = array(
                        'invalid_form_id' => __('Invalid Form ID', 'cbxform'),
                    );
                    $return['form_id'] = $form_id;
                    $return['success'] = false;
                    $return['data'] = $data;
                    $return['error'] = $error;
                    return $return;
                } else {
                    //the form unique id
                    $return['form_id'] = $form_id;
                }
            }

            //get the saved form info form meta by id
            $saved_formvalues = get_post_meta($form_id, '_cbxformmeta', true);
            //posted value
            if (isset($metavalue_key['fields']) && sizeof($metavalue_key['fields']) > 0) {
                $posted_form_fields = $_POST['cbxformmetabox']['fields'];
            }
            //saved value
            $saved_form_fields = $saved_formvalues['fields'];
            $form_submission_error = false;


            //$data = array();
            foreach ($posted_form_fields as $postedkey => $postedvalue) {
                if (array_key_exists($postedkey, $saved_form_fields)) {

                    //check captcha
                    if ($saved_form_fields[$postedkey]['type'] == 'captcha') {
                        if ($this->settings_api->get_option('captcha_secret_key', 'cbxform_security') != '') {
                            $recaptcha_secret = $this->settings_api->get_option('captcha_secret_key', 'cbxform_security');
                            $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . $recaptcha_secret . "&response=" . $_POST['g-recaptcha-response']);
                            $response = json_decode($response, true);
                            //failure
                            if ($response["success"] !== true) {
                                $error['captcha'] = __('Captcha Invalid', 'cbxform');
                                $return['error'] = $error;
                                $form_submission_error = true;
                            }
                        }
                    }

                    //nonce check
                    if (!isset($_POST['cbxform_nonce_field']) || !wp_verify_nonce($_POST['cbxform_nonce_field'], 'cbxform_nonce')) {
                        $error['nonce'] = __('Nonce Invalid', 'cbxform');
                        $return['error'] = $error;
                        $form_submission_error = true;
                    }

                    //check required
                    if (isset($saved_form_fields[$postedkey]['required']) && $saved_form_fields[$postedkey]['required'] == 1) {
                        if (is_array($postedvalue)) {
                            //if (empty(array_filter($postedvalue))) {
                            $postedvalue = array_filter($postedvalue);
                            if (empty($postedvalue)) {
                                $error[$postedkey][] = sprintf(__('%s is Required', 'cbxform'), esc_attr($saved_form_fields[$postedkey]['label']));
                                $return['error'] = $error;
                                $form_submission_error = true;
                            }
                        } else {
                            if (!cbxform_validate::stringType()->notEmpty()->validate($postedvalue)) {
                                $error[$postedkey][] = sprintf(__('%s is Required', 'cbxform'), esc_attr($saved_form_fields[$postedkey]['label']));
                                $return['error'] = $error;
                                $form_submission_error = true;
                            }
                        }

                    }

                    //check minlength and maxlength values
                    if ((isset($saved_form_fields[$postedkey]['minlength']) && $saved_form_fields[$postedkey]['minlength'] != '') || (isset($saved_form_fields[$postedkey]['maxlength']) && $saved_form_fields[$postedkey]['maxlength'] != '')) {

                        if ($saved_form_fields[$postedkey]['maxlength'] == '') {
                            if (!cbxform_validate::stringType()->length(intval($saved_form_fields[$postedkey]['minlength']), null)->validate($postedvalue)) {
                                $error[$postedkey][] = sprintf(__('%s: Please provide at least %d characters', 'cbxform'), esc_attr($saved_form_fields[$postedkey]['label']), intval($saved_form_fields[$postedkey]['minlength']));
                                $return['error'] = $error;
                                $form_submission_error = true;
                            }
                        } elseif ($saved_form_fields[$postedkey]['minlength'] == '') {
                            if (!cbxform_validate::stringType()->length(null, intval($saved_form_fields[$postedkey]['maxlength']))->validate($postedvalue)) {
                                $error[$postedkey][] = sprintf(__('%s: Please provide not more than %s characters', 'cbxform'), esc_attr($saved_form_fields[$postedkey]['label']), intval($saved_form_fields[$postedkey]['maxlength']));
                                $return['error'] = $error;
                                $form_submission_error = true;
                            }
                        } else {
                            if (!cbxform_validate::stringType()->length(intval($saved_form_fields[$postedkey]['minlength']), intval($saved_form_fields[$postedkey]['maxlength']))->validate($postedvalue)) {
                                $error[$postedkey][] = sprintf(__('%s: Please provide characters between %d & %d', 'cbxform'), esc_attr($saved_form_fields[$postedkey]['label']), intval($saved_form_fields[$postedkey]['minlength']), intval($saved_form_fields[$postedkey]['maxlength']));
                                $return['error'] = $error;
                                $form_submission_error = true;
                            }
                        }
                    }

                    //check min and max values
                    if ((isset($saved_form_fields[$postedkey]['min']) && $saved_form_fields[$postedkey]['min'] != '') || (isset($saved_form_fields[$postedkey]['max']) && $saved_form_fields[$postedkey]['max'] != '')) {

                        if ($saved_form_fields[$postedkey]['max'] == '') {
                            if (!cbxform_validate::intVal()->min(intval($saved_form_fields[$postedkey]['min']))->validate($postedvalue)) {
                                $error[$postedkey][] = sprintf(__('%s: Please provide at least %d', 'cbxform'), esc_attr($saved_form_fields[$postedkey]['label']), intval($saved_form_fields[$postedkey]['min']));
                                $return['error'] = $error;
                                $form_submission_error = true;
                            }
                        } elseif ($saved_form_fields[$postedkey]['min'] == '') {
                            if (!cbxform_validate::intVal()->max(intval($saved_form_fields[$postedkey]['max']))->validate($postedvalue)) {
                                $error[$postedkey][] = sprintf(__('%s: Please provide not more than %d', 'cbxform'), esc_attr($saved_form_fields[$postedkey]['label']), intval($saved_form_fields[$postedkey]['max']));
                                $return['error'] = $error;
                                $form_submission_error = true;
                            }
                        } else {
                            if (!cbxform_validate::intVal()->between(intval($saved_form_fields[$postedkey]['min']), intval($saved_form_fields[$postedkey]['max']))->validate($postedvalue)) {
                                $error[$postedkey][] = sprintf(__('%s: Please provide value between %d & %d', 'cbxform'), esc_attr($saved_form_fields[$postedkey]['label']), intval($saved_form_fields[$postedkey]['min']), intval($saved_form_fields[$postedkey]['max']));
                                $return['error'] = $error;
                                $form_submission_error = true;
                            }
                        }
                    }



                    if ($saved_form_fields[$postedkey]['type'] == 'text' || $saved_form_fields[$postedkey]['type'] == 'textarea' || $saved_form_fields[$postedkey]['type'] == 'email') {
                        $data[$postedkey] = esc_attr($postedvalue);
                    }elseif ($saved_form_fields[$postedkey]['type'] == 'number') {
                        $data[$postedkey] = floatval($postedvalue);
                    }elseif ($saved_form_fields[$postedkey]['type'] == 'radio' || $saved_form_fields[$postedkey]['type'] == 'checkbox' || $saved_form_fields[$postedkey]['type'] == 'select') {
                        foreach ($postedvalue as $key => $value) {
                            $data[$postedkey][] = esc_attr($saved_form_fields[$postedkey]['option'][$value][0]);
                        }
                    }else{
                        //$data[$postedkey] = apply_filters('onCBXFormExtraFieldProcess', $data, $saved_form_fields , $postedkey , $postedvalue);
                        $data = apply_filters('onCBXFormExtraFieldProcess', $data, $saved_form_fields , $postedkey , $postedvalue); //todo: onCBXFormExtraFieldProcess
                    }
                }
            }

            $return['data'] = $data;

            if (!session_id()) session_start();

            if (!isset($return['error']) || sizeof($return['error']) == 0) {
                //if form validation successful, now we will do whatever we want to do

                $settings = $saved_formvalues['settings'];

                if (isset($settings['cbxform_misc']['success_message'][0]) && $settings['cbxform_misc']['success_message'][0] != '') {
                    $msg = $this->parse_msgbody($settings['cbxform_misc']['success_message'][0],$return['data'],$meta['fields']);
                    $return['success'][] = array('msg' => $msg, 'show' => true);
                }else{
                    $return['success'][] = array('msg' => __('Form Successfully Submitted','cbxform'), 'show' => true);
                }

                $return = apply_filters('onCBXFormValidationComplete', $return, $meta);

                $form_submission_count = get_post_meta($metavalue_key['post_id'], '_cbxformmeta_submission_count', TRUE);

                if($form_submission_count == ''){
                    update_post_meta($metavalue_key['post_id'],'_cbxformmeta_submission_count',1);
                }else{
                    update_post_meta($metavalue_key['post_id'],'_cbxformmeta_submission_count',intval($form_submission_count)+1);
                }

                $_SESSION['cbxform_data'] = $return;

                //if ajax
                if (isset($_POST['ajax']) && $_POST['ajax'] == true) {
                    echo wp_json_encode($_SESSION['cbxform_data']); //ajax
                    wp_die(); //ajax
                }

            } else {
                $return['success'] = null;
                $_SESSION['cbxform_data'] = $return;

                //if ajax
                if (isset($_POST['ajax']) && $_POST['ajax'] == true) {
                    echo wp_json_encode($_SESSION['cbxform_data']); //ajax
                    wp_die(); //ajax
                }
            }
        }

    }

    /**
     * Register Widget
     */
    public function register_widget()
    {

        register_widget("CBXFormWidget"); //form widget

    }
    /**
     * 'cbxform' shortcode callback
     *
     * @param $atts
     * @return string
     */
    public function cbxform_shortcode($atts)
    {
        $instance = shortcode_atts(
            array(
                'id' => '',
            ),
            $atts
        );

        extract($instance);
        $action_button_html = $class_radio_checkbox = $ajax_class = $html = '';

        $formclass = 'cbxform-wrapper cbxform ';


        $arranged_fields = array();
        $formvalues = get_post_meta($id, '_cbxformmeta', true);


        // structure and style of the form by adding class in it
        if (isset($formvalues['settings']['cbxform_style']) && sizeof($formvalues['settings']['cbxform_style']) > 0) {
            if (isset($formvalues['settings']['cbxform_style']['formstructure']) && sizeof($formvalues['settings']['cbxform_style']['formstructure']) > 0) {
                $formclass .= $formvalues['settings']['cbxform_style']['formstructure'][0];
                if ($formvalues['settings']['cbxform_style']['formstructure'][0] == 'cbxform') {
                    $class_radio_checkbox = 'cbxform-item-input-list';
                }
            }
            if (isset($formvalues['settings']['cbxform_style']['formstyle']) && sizeof($formvalues['settings']['cbxform_style']['formstyle']) > 0) {
                $formclass .= ' ' . $formvalues['settings']['cbxform_style']['formstyle'][0];
            }
        }

        // check the form status
        if (isset($formvalues['status']) && intval($formvalues['status']) == 0) {
            return '';
        }

        if (isset($formvalues['settings']['cbxform_misc']) && sizeof($formvalues['settings']['cbxform_misc']) > 0) {
            $form_submission_count = get_post_meta($id, '_cbxformmeta_submission_count', TRUE);
            if($formvalues['settings']['cbxform_misc']['enable_form_submission_limit'][0] == 'on' && intval($formvalues['settings']['cbxform_misc']['form_submission_limit_val'][0]) > 0 && intval($formvalues['settings']['cbxform_misc']['form_submission_limit_val'][0]) < intval($form_submission_count)+1 ){
                return '';
            }
        }

        // form submission ajax/refresh
        if (isset($formvalues['settings']['cbxform_misc']) && sizeof($formvalues['settings']['cbxform_misc']) > 0) {
            if (isset($formvalues['settings']['cbxform_misc']['formsubmit']) && sizeof($formvalues['settings']['cbxform_misc']['formsubmit']) > 0) {
                if (esc_attr($formvalues['settings']['cbxform_misc']['formsubmit'][0]) == 'ajax') {
                    $ajax_class = 'cbxform_ajax';
                } else {
                    $ajax_class = '';
                }
            }

            if (isset($formvalues['settings']['cbxform_misc']['showform_suceessful']) && sizeof($formvalues['settings']['cbxform_misc']['showform_suceessful']) > 0) {

                if (!is_array($formvalues['settings']['cbxform_misc']['showform_suceessful'])) {
                    $this->showform_successful = $formvalues['settings']['cbxform_misc']['showform_suceessful'];
                } else {
                    $this->showform_successful = $formvalues['settings']['cbxform_misc']['showform_suceessful'][0];
                }

                if (isset($_SESSION['cbxform_data'])) {
                    $_SESSION['cbxform_data']['showform_successful'] = $this->showform_successful;
                }
            }
        }

        if (intval($id) > 0) {
            ob_start(); ?>
            <div class="<?php echo $formclass ?>" data-structure="<?php echo $formvalues['settings']['cbxform_style']['formstructure'][0]; ?>">
                <div class="cbxform-messages">
                    <?php
                    if (isset($_SESSION['cbxform_data'])) {
                        if (isset($_SESSION['cbxform_data']['error']) && sizeof($_SESSION['cbxform_data']['error']) > 0) {
                            foreach ($_SESSION['cbxform_data']['error'] as $key => $value) {
                                if (!is_array($value)) {
                                    echo '<p class="cbxform-error"><a href="#' . $key . '">' . $value . '</a></p>';
                                } else {
                                    foreach ($value as $k => $v) {
                                        echo '<p class="cbxform-error"><a href="#' . $key . '">' . $v . '</a></p>';
                                    }
                                }
                            }
                        }
                    } ?>
                    <?php
                    if (isset($_SESSION['cbxform_data'])) {
                        if (isset($_SESSION['cbxform_data']['success']) && sizeof($_SESSION['cbxform_data']['success']) > 0) {
                            foreach ($_SESSION['cbxform_data']['success'] as $key => $success_msg_array) {
                                if ($success_msg_array['show']) {
                                    echo '<p class="cbxform-success">' . $success_msg_array['msg'] . '</p>';
                                }
                            }
                        }
                    } ?>
                </div>
                <?php static $counter = 1; ?>
                <?php do_action('cbx_beforeform'); ?>


                <?php
                    if($formvalues['settings']['cbxform_style']['text_beforeform'][0] != '' ){
                        echo '<p class="cbxform_beforeform cbxform_beforeform-'.$id.' cbxform_beforeform-'.$id.'-'.$counter.'">'.$formvalues['settings']['cbxform_style']['text_beforeform'][0].'</p>';
                    }

                ?>

                <form action="" data-ajax="<?php echo $ajax_class; ?>" name="cbxform-<?php echo $id.'-'.$counter; ?>" method="post"
                      enctype="multipart/form-data" class="<?php echo 'cbxform-single cbxform-' . $id.'-'.$counter; ?>">

                    <input type="hidden" name="cbxformmetabox[post_id]" value="<?php echo $id; ?>"/>

                    <?php wp_nonce_field('cbxform_nonce', 'cbxform_nonce_field'); ?><!--Nonce field for form-->

                    <?php if (isset($formvalues['fields'])) { //checking if fields exist or not

                        /**---start rearranging the fields by steps---**/

                        if (sizeof($formvalues['fields']) > 0) {

                            foreach ($formvalues['fields'] as $key => $field) {
                                if (strpos($key, 'step') !== false) {
                                    $new_key = $field;
                                    continue;
                                }
                                $arranged_fields[$new_key][$key] = $field;
                            }
                        } else {
                            $arranged_fields['step-1'][] = '';
                        }

                        /*---finish rearranging the fields by steps---*/


                        foreach ($arranged_fields as $title => $elements) { //loop by steps to all the saved form fields to render

                            do_action('cbx_beforeformelement', $title);

                            foreach ($elements as $form_item_id => $value) {
                                //reset some classes for each element
                                $label_class = $input_class = '';

                                /*front end form element render start*/

                                //some custom style added for specific form elements
                                $cbxform_stylable_form_field = apply_filters('cbxform_stylable_form_field',array('radio','checkbox','select','captcha'));


                                if ( in_array($value['type'],$cbxform_stylable_form_field)) {

                                    $label_class = 'cbx-radio-label';

                                    if ($value['type'] == 'radio') {
                                        $input_class = 'cbxform-item-input-radio ' . $class_radio_checkbox;
                                    }
                                    if ($value['type'] == 'checkbox') {
                                        $input_class = 'cbxform-item-input-checkbox ' . $class_radio_checkbox;
                                    }
                                    if ($value['type'] == 'select') {
                                        $input_class = 'cbxform-item-input-radio cbxform-item-input-list ';
                                    }
                                    if ($value['type'] == 'captcha') {
                                        $input_class = 'cbxform-item-input-radio cbx-list ';
                                    }

                                    if ($value['type'] == 'mailchimp') {
                                        $input_class = 'cbxform-item-input-checkbox ' . $class_radio_checkbox;;
                                    }
                                }

                                if ($value['type'] == 'submit' || $value['type'] == 'reset') {
                                    $fieldclass = 'cbxformfield' . $value['type'];
                                    //$action_button_html .= '<div class="cbxform-action" id="cbxform-actionbtns">';
                                    if (is_callable(array($fieldclass, 'fronthtml_render'))) {
                                        $action_button_html .= call_user_func_array(array($fieldclass, 'fronthtml_render'), array($form_item_id, $value, $id));
                                    }
                                    //$action_button_html .= '</div>';
                                } else {

                                    $html .= '<div class="cbxform-item-field">';
                                    $html .= '<div class="cbxform-item cbxform-item-label ' . $label_class . '">';
                                    $html .= '<div class="cbxform-item-wrap">';
                                    if (isset($value['label']) && $value['show_label'] == 1) {
                                        $html .= '<label for="' . $form_item_id . '">' . $value['label'] . '</label>';
                                    }
                                    $html .= '</div>';
                                    $html .= '</div>';
                                    $html .= '<div class="cbxform-item cbxform-item-input ' . $input_class . '">';
                                    $html .= '<div class="cbxform-item-wrap">';
                                    $fieldclass = 'cbxformfield' . $value['type'];

                                    if (is_callable(array($fieldclass, 'fronthtml_render'))) {
                                        $html .= call_user_func_array(array($fieldclass, 'fronthtml_render'), array($form_item_id, $value, $id));
                                    }

                                    $html .= '</div>';
                                    $html .= '</div>';
                                    $html .= '</div>';
                                }
                                /*Front end form element end*/
                            }

                            echo $html;
                            do_action('cbx_afterformelement');

                            $html = '';
                        }
                    }
                    $action_button = '<div class="cbxform-action" id="cbxform-actionbtns-'.$counter.'">'.$action_button_html.'</div>';
                    echo $action_button;

                    ?>
                    <div class="cbfformclearfix"></div>
                </form>
                <div class="cbfformclearfix"></div>
                <?php do_action('cbx_afterform');  ?>
                <?php $counter++;  ?>

                <?php


                if($formvalues['settings']['cbxform_style']['text_afterform'][0] != '' ){
                    echo '<p class="text_afterform text_afterform-'.$id.' text_afterform-'.$id.'-'.$counter.'">'.$formvalues['settings']['cbxform_style']['text_afterform'][0].'</p>';
                }

                ?>

                <script type="text/javascript">
                    jQuery(document).ready(function ($) {

                        <?php if(isset( $_SESSION['cbxform_data']['showform_successful']) &&  $_SESSION['cbxform_data']['showform_successful'] == 'off') { ?>

                        $('form[name="cbxform-<?php echo $id.'-'.$counter; ?>"]').remove();

                        <?php } ?>

                    });
                </script>
                <?php
                $show_credit =  (isset($formvalues['settings']['cbxform_misc']['show_credit']) && sizeof($formvalues['settings']['cbxform_misc']['show_credit']) > 0) ? esc_attr($formvalues['settings']['cbxform_misc']['show_credit'][0]) : 'no';

                if($show_credit == 'yes'){
                    echo sprintf('<p style="text-align: right;font-size: 10px;">%s %s target="_blank">%s</a></p>',__('CBX Forms from','cbxform'),'<a rel="external follow" href="http://codeboxr.com?utm_source=cbxform&utm_medium=clientwebsite&utm_campaign=cbxform"','Codeboxr');
                }
                ?>
            </div>


            <?php
            $form = ob_get_contents();
            ob_end_clean();
            if (isset($_SESSION['cbxform_data'])) {
                unset($_SESSION['cbxform_data']);
            }
            return $form;
        } else return '';
    }
}
