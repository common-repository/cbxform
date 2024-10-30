<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Cbxform
 * @subpackage Cbxform/admin
 * @author     http://codeboxr.com/ <info@codeboxr.com>
 */
class Cbxform_Admin
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
     * Slug of the plugin screen.
     *
     * @since    1.0.0
     *
     * @var      string
     */
    protected $plugin_screen_hook_suffix = null;

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

    /**
     * The plugin basename of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $plugin_basename The plugin basename of the plugin.
     */
    protected $plugin_basename;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     *
     * @param      string $plugin_name The name of this plugin.
     * @param      string $version The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->plugin_basename = plugin_basename(plugin_dir_path(__DIR__) . $plugin_name . '.php');
        $this->settings_api = new CBXForm_Settings_API($plugin_name, $version);

        //before the header load to check any request to execute or not!
        if (isset($_GET['cbxform_ids']) && isset($_GET['cbxform_ids'])) {
            add_action('admin_init', array(&$this, 'cbxform_export'));
        }

    }

    /**
     * Get Base Currency Code.
     *
     * @return string
     */
    public function get_currency()
    {
        return apply_filters('cbxform_currency', get_option('cbxform_currency'));
    }

    /**
     * Get full list of currency codes.
     *
     * @return array
     */
    public function get_currencies()
    {
        return array_unique(
            apply_filters('cbxform_currencies', array(
                    'AED' => __('United Arab Emirates Dirham', 'cbxform'),
                    'ARS' => __('Argentine Peso', 'cbxform'),
                    'AUD' => __('Australian Dollars', 'cbxform'),
                    'BDT' => __('Bangladeshi Taka', 'cbxform'),
                    'BRL' => __('Brazilian Real', 'cbxform'),
                    'BGN' => __('Bulgarian Lev', 'cbxform'),
                    'CAD' => __('Canadian Dollars', 'cbxform'),
                    'CLP' => __('Chilean Peso', 'cbxform'),
                    'CNY' => __('Chinese Yuan', 'cbxform'),
                    'COP' => __('Colombian Peso', 'cbxform'),
                    'CZK' => __('Czech Koruna', 'cbxform'),
                    'DKK' => __('Danish Krone', 'cbxform'),
                    'DOP' => __('Dominican Peso', 'cbxform'),
                    'EUR' => __('Euros', 'cbxform'),
                    'HKD' => __('Hong Kong Dollar', 'cbxform'),
                    'HRK' => __('Croatia kuna', 'cbxform'),
                    'HUF' => __('Hungarian Forint', 'cbxform'),
                    'ISK' => __('Icelandic krona', 'cbxform'),
                    'IDR' => __('Indonesia Rupiah', 'cbxform'),
                    'INR' => __('Indian Rupee', 'cbxform'),
                    'NPR' => __('Nepali Rupee', 'cbxform'),
                    'ILS' => __('Israeli Shekel', 'cbxform'),
                    'JPY' => __('Japanese Yen', 'cbxform'),
                    'KIP' => __('Lao Kip', 'cbxform'),
                    'KRW' => __('South Korean Won', 'cbxform'),
                    'MYR' => __('Malaysian Ringgits', 'cbxform'),
                    'MXN' => __('Mexican Peso', 'cbxform'),
                    'NGN' => __('Nigerian Naira', 'cbxform'),
                    'NOK' => __('Norwegian Krone', 'cbxform'),
                    'NZD' => __('New Zealand Dollar', 'cbxform'),
                    'PYG' => __('Paraguayan Guaraní', 'cbxform'),
                    'PHP' => __('Philippine Pesos', 'cbxform'),
                    'PLN' => __('Polish Zloty', 'cbxform'),
                    'GBP' => __('Pounds Sterling', 'cbxform'),
                    'RON' => __('Romanian Leu', 'cbxform'),
                    'RUB' => __('Russian Ruble', 'cbxform'),
                    'SGD' => __('Singapore Dollar', 'cbxform'),
                    'ZAR' => __('South African rand', 'cbxform'),
                    'SEK' => __('Swedish Krona', 'cbxform'),
                    'CHF' => __('Swiss Franc', 'cbxform'),
                    'TWD' => __('Taiwan New Dollars', 'cbxform'),
                    'THB' => __('Thai Baht', 'cbxform'),
                    'TRY' => __('Turkish Lira', 'cbxform'),
                    'UAH' => __('Ukrainian Hryvnia', 'cbxform'),
                    'USD' => __('US Dollars', 'cbxform'),
                    'VND' => __('Vietnamese Dong', 'cbxform'),
                    'EGP' => __('Egyptian Pound', 'cbxform')
                )
            )
        );
    }

    /**
     * Get Currency symbol.
     *
     * @param string $currency (default: '')
     *
     * @return string
     */
    public function get_currency_symbol($currency = '')
    {
        if (!$currency) {
            $currency = $this->get_currency();
        }

        switch ($currency) {
            case 'AED' :
                $currency_symbol = 'د.إ';
                break;
            case 'AUD' :
            case 'ARS' :
            case 'CAD' :
            case 'CLP' :
            case 'COP' :
            case 'HKD' :
            case 'MXN' :
            case 'NZD' :
            case 'SGD' :
            case 'USD' :
                $currency_symbol = '&#36;';
                break;
            case 'BDT':
                $currency_symbol = '&#2547;&nbsp;';
                break;
            case 'BGN' :
                $currency_symbol = '&#1083;&#1074;.';
                break;
            case 'BRL' :
                $currency_symbol = '&#82;&#36;';
                break;
            case 'CHF' :
                $currency_symbol = '&#67;&#72;&#70;';
                break;
            case 'CNY' :
            case 'JPY' :
            case 'RMB' :
                $currency_symbol = '&yen;';
                break;
            case 'CZK' :
                $currency_symbol = '&#75;&#269;';
                break;
            case 'DKK' :
                $currency_symbol = 'DKK';
                break;
            case 'DOP' :
                $currency_symbol = 'RD&#36;';
                break;
            case 'EGP' :
                $currency_symbol = 'EGP';
                break;
            case 'EUR' :
                $currency_symbol = '&euro;';
                break;
            case 'GBP' :
                $currency_symbol = '&pound;';
                break;
            case 'HRK' :
                $currency_symbol = 'Kn';
                break;
            case 'HUF' :
                $currency_symbol = '&#70;&#116;';
                break;
            case 'IDR' :
                $currency_symbol = 'Rp';
                break;
            case 'ILS' :
                $currency_symbol = '&#8362;';
                break;
            case 'INR' :
                $currency_symbol = 'Rs.';
                break;
            case 'ISK' :
                $currency_symbol = 'Kr.';
                break;
            case 'KIP' :
                $currency_symbol = '&#8365;';
                break;
            case 'KRW' :
                $currency_symbol = '&#8361;';
                break;
            case 'MYR' :
                $currency_symbol = '&#82;&#77;';
                break;
            case 'NGN' :
                $currency_symbol = '&#8358;';
                break;
            case 'NOK' :
                $currency_symbol = '&#107;&#114;';
                break;
            case 'NPR' :
                $currency_symbol = 'Rs.';
                break;
            case 'PHP' :
                $currency_symbol = '&#8369;';
                break;
            case 'PLN' :
                $currency_symbol = '&#122;&#322;';
                break;
            case 'PYG' :
                $currency_symbol = '&#8370;';
                break;
            case 'RON' :
                $currency_symbol = 'lei';
                break;
            case 'RUB' :
                $currency_symbol = '&#1088;&#1091;&#1073;.';
                break;
            case 'SEK' :
                $currency_symbol = '&#107;&#114;';
                break;
            case 'THB' :
                $currency_symbol = '&#3647;';
                break;
            case 'TRY' :
                $currency_symbol = '&#8378;';
                break;
            case 'TWD' :
                $currency_symbol = '&#78;&#84;&#36;';
                break;
            case 'UAH' :
                $currency_symbol = '&#8372;';
                break;
            case 'VND' :
                $currency_symbol = '&#8363;';
                break;
            case 'ZAR' :
                $currency_symbol = '&#82;';
                break;
            default :
                $currency_symbol = '';
                break;
        }
        return apply_filters('cbxform_currency_symbol', $currency_symbol, $currency);
    }

    /**
     * Register the administration menu for this plugin into the WordPress Dashboard menu.
     * @since    1.0.0
     */
    public function add_plugin_admin_menu()
    {
        //add settings for this plugin
        $this->plugin_screen_hook_suffix = add_submenu_page(
            'edit.php?post_type=cbxform', __('Setting', 'cbxform'), __('Setting', 'cbxform'), 'manage_options', 'cbxformsettings', array($this, 'display_plugin_admin_settings')
        );
    }

    /**
     * Display settings
     * @global type $wpdb
     */
    public function display_plugin_admin_settings()
    {
        global $wpdb;
        $plugin_data = get_plugin_data(plugin_dir_path(__DIR__) . '/../' . $this->plugin_basename);
        include('partials/admin-settings-display.php');
    }

    /**
     * Settings Initialization
     */
    public function setting_init()
    {
        //set the settings
        $this->settings_api->set_sections($this->get_settings_sections());
        $this->settings_api->set_fields($this->get_settings_fields());
        //initialize settings
        $this->settings_api->admin_init();
    }

    /**
     * Global Setting Sections
     *
     *
     * @return type
     */
    public function get_settings_sections()
    {
        return apply_filters('cbxform_setting_sections', array(

            array(
                'id' => 'cbxform_global',
                'title' => __('Global', 'cbxform')
            ),
            array(
                'id' => 'cbxform_email',
                'title' => __('Email', 'cbxform')
            ),
            array(
                'id' => 'cbxform_security',
                'title' => __('Security', 'cbxform')
            ),
            array(
                'id' => 'cbxform_integration',
                'title' => __('Integration', 'cbxform')
            )
        ));
    }

    /**
     * Add global currency setting Section
     *
     * @param array $sections
     *
     * @return array
     */
    public function add_currency_setting_section($sections)
    {

        $currency_section = array(
            array(
                'id' => 'cbxform_currency',
                'title' => __('Currency', 'cbxform')
            )
        );

        return array_merge($sections, $currency_section);

    }

    /**
     * Global Setting Fields
     *
     * @return array
     */
    public function get_settings_fields()
    {


        $settings_builtin_fields =
            array(
                'cbxform_global' =>
                    array(
                        'formsubmit' => array(
                            'name' => 'formsubmit',
                            'label' => __('Form Submit', 'cbxform'),
                            'desc' => __('Form submission method.', 'cbxform'),
                            'type' => 'radio',
                            'default' => 'ajax',
                            'options' => array(
                                'refresh' => __('Refresh', 'cbxform'),
                                'ajax' => __('Ajax', 'cbxform')
                            ),
                            'desc_tip' => true,
                        ),
                        'showform_successful' => array(
                            'name' => 'showform_successful',
                            'label' => __('Form show (after successful submission)', 'cbxform'),
                            'desc' => __('Show form after successful submission.', 'cbxform'),
                            'type' => 'checkbox',
                            'default' => 'on',
                            'desc_tip' => true,
                        ),
                        'show_credit' => array(
                            'name' => 'show_credit',
                            'label' => __('Show Credit Under Form', 'cbxform'),
                            'desc' => __('Show Credit Under Form.', 'cbxform'),
                            'type' => 'radio',
                            'options' => array(
                                'yes' => 'Yes',
                                'no' => 'No'
                            ),
                            'default' => 'no',
                            'desc_tip' => true,
                        ),
                        'delete_global_config' => array(
                            'name' => 'delete_global_config',
                            'label' => __('Uninstall delete data', 'cbxform'),
                            'desc' => __('Delete Global Config data on uninstall.', 'cbxform'),
                            'type' => 'radio',
                            'options' => array(
                                'yes' => 'Yes',
                                'no' => 'No'
                            ),
                            'default' => 'no',
                            'desc_tip' => true,
                        ),
                    ),
                'cbxform_email' => array(
                    'headerimage' => array(
                        'name' => 'headerimage',
                        'label' => __('Header Image', 'cbxform'),
                        'desc' => __('Url To email you want to show as email header.Upload Image by media uploader.', 'cbxform'),
                        'type' => 'text',
                        'default' => '',
                        'desc_tip' => true,
                    ),
                    'footertext' => array(
                        'name' => 'footertext',
                        'label' => __('Footer Text', 'cbxform'),
                        'desc' => __('The text to appear at the email footer.', 'cbxform'),
                        'type' => 'wysiwyg',
                        'default' => '{sitename}',
                        'desc_tip' => true,
                    ),
                    'basecolor' => array(
                        'name' => 'basecolor',
                        'label' => __('Base Color', 'cbxform'),
                        'desc' => __('The base color of the email.', 'cbxform'),
                        'type' => 'color',
                        'default' => '#557da1',
                        'desc_tip' => true,
                    ),
                    'backgroundcolor' => array(
                        'name' => 'backgroundcolor',
                        'label' => __('Background Colour', 'cbxform'),
                        'desc' => __('The background color of the email.', 'cbxform'),
                        'type' => 'color',
                        'default' => '#f5f5f5',
                        'desc_tip' => true,
                    ),
                    'bodybackgroundcolor' => array(
                        'name' => 'bodybackgroundcolor',
                        'label' => __('Body Background Color', 'cbxform'),
                        'desc' => __('The background colour of the main body of email.', 'cbxform'),
                        'type' => 'color',
                        'default' => '#fdfdfd',
                        'desc_tip' => true,
                    ),
                    'bodytextcolor' => array(
                        'name' => 'bodytextcolor',
                        'label' => __('Body Text Color', 'cbxform'),
                        'desc' => __('The body text colour of the main body of email.', 'cbxform'),
                        'type' => 'color',
                        'default' => '#505050',
                        'desc_tip' => true,
                    ),
                ),
                'cbxform_security' => array(
                    'captcha_title' => array(
                        'name' => 'captcha_title',
                        'label' => __('<h3>Google Recaptcha</h3>', 'cbxform'),
                        'type' => 'title'
                    ),
                    'captcha_site_key' => array(
                        'name' => 'captcha_site_key',
                        'label' => __('Site Key', 'cbxform'),
                        'desc' => __('Enter Google Recaptcha Site Key.', 'cbxform'),
                        'type' => 'text',
                        'default' => '',
                        'desc_tip' => true,
                    ),
                    'captcha_secret_key' => array(
                        'name' => 'captcha_secret_key',
                        'label' => __('Secret Key', 'cbxform'),
                        'desc' => __('Enter Google Recaptcha Secret Key.', 'cbxform'),
                        'type' => 'text',
                        'default' => '',
                        'desc_tip' => true,
                    ),
                    'captcha_language' => array(
                        'name' => 'captcha_language',
                        'label' => __('Language', 'cbxform'),
                        'desc' => sprintf('%s<a href="%s" target="_blank">%s</a>', __('e.g. en, bn - Languages used by Google reCAPTCHA. To get the code for your language,', 'cbxform'), 'https://developers.google.com/recaptcha/docs/language', __('Click', 'cbxform')),
                        'type' => 'text',
                        'default' => 'en',
                        'desc_tip' => true,
                    ),
                )
            );

        $settings_fields = array(); //final setting array that will be passed to different filters

        $sections = $this->get_settings_sections();




        foreach ($sections as $section) {
            if (!isset($settings_builtin_fields[$section['id']])) {
                $settings_builtin_fields[$section['id']] = array();
            }
        }




        foreach ($sections as $section) {

            $settings_fields[$section['id']] = apply_filters('cbxform_global_' . $section['id'] . '_fields', $settings_builtin_fields[$section['id']]);
        }


        $settings_fields = apply_filters('cbxform_global_fields', $settings_fields); //final filter if need





        return $settings_fields;
    }

    /**
     *
     *
     * @param $fields
     *
     * @return mixed
     */
    public function add_currency_setting_fields($fields)
    {

        $currency_code_options = $this->get_currencies();

        foreach ($currency_code_options as $code => $name) {
            $currency_code_options[$code] = $name . ' (' . $this->get_currency_symbol($code) . ')';
        }

        $currency_fields = array(


            'currency' => array(
                'name' => 'currency',
                'label' => __('Default Currency', 'cbxform'),
                'desc' => __('This controls what currency is used for calculation.', 'cbxform'),
                'type' => 'select',
                'default' => 'no',
                'desc_tip' => true,
                'options' => $currency_code_options,
                'default' => 'USD',
            ),
            'currency_pos' => array(
                'name' => 'currency_pos',
                'label' => __('Currency Position', 'cbxform'),
                'desc' => __('This controls the position of the currency symbol.', 'cbxform'),
                'type' => 'select',
                'default' => 'left',
                'options' => array(
                    'left' => __('Left', 'cbxform') . ' (' . $this->get_currency_symbol($this->settings_api->get_option('currency', 'cbxform_currency', 'USD')) . '99.99)',
                    'right' => __('Right', 'cbxform') . ' (99.99' . $this->get_currency_symbol($this->settings_api->get_option('currency', 'cbxform_currency', 'USD')) . ')',
                    'left_space' => __('Left with space', 'cbxform') . ' (' . $this->get_currency_symbol($this->settings_api->get_option('currency', 'cbxform_currency', 'USD')) . ' 99.99)',
                    'right_space' => __('Right with space', 'cbxform') . ' (99.99 ' . $this->get_currency_symbol($this->settings_api->get_option('currency', 'cbxform_currency', 'USD')) . ')'
                ),
                'desc_tip' => true,
            ),
            'thousand_sep' => array(
                'name' => 'thousand_sep',
                'label' => __('Thousand Separator', 'cbxform'),
                'desc' => __('This sets the thousand separator of displayed prices.', 'cbxform'),
                'type' => 'text',
                'default' => ',',
                'desc_tip' => true,
            ),
            'decimal_sep' => array(
                'name' => 'decimal_sep',
                'label' => __('Decimal Separator', 'cbxform'),
                'desc' => __('This sets the decimal separator of displayed prices.', 'cbxform'),
                'type' => 'text',
                'default' => '.',
                'desc_tip' => true,
            ),
            'num_decimals' => array(
                'name' => 'num_decimals',
                'label' => __('Number of Decimals', 'cbxform'),
                'desc' => __('This sets the number of decimal points shown in displayed prices.', 'cbxform'),
                'type' => 'number',
                'default' => '2',
                'desc_tip' => true,
            )

        );

        return array_merge($fields, $currency_fields);
    }

    /**
     * Meta form settings section for CBXForm
     * @return type
     */
    public function cbxform_meta_settings_sections()
    {
        $sections = array(
            array(
                'id' => 'cbxform_style',
                'title' => __('Form Style', 'cbxform')
            ),
            array(
                'id' => 'cbxform_email_admin',
                'title' => __('Email(Admin)', 'cbxform')
            ),
            array(
                'id' => 'cbxform_email_user',
                'title' => __('Email(User)', 'cbxform')
            ),
            array(
                'id' => 'cbxform_misc',
                'title' => __('Misc', 'cbxform')
            ),
            array(
                'id' => 'cbxform_integration',
                'title' => __('Integration', 'cbxform')
            )
        );

        $sections = apply_filters('cbxform_meta_sections', $sections);

        return $sections;
    }


    /**
     * Meta form fields for meta settings sections
     *
     * @return type
     */
    public function cbxform_meta_settings_fields()
    {

        $form_structres = $this->get_form_structures();
        $input_styles = $this->get_form_input_styles();
        $form_submit = $this->settings_api->get_option('formsubmit', 'cbxform_global', 'ajax');
        $showform_successful = $this->settings_api->get_option('showform_successful', 'cbxform_global', 'on');
        $show_credit = $this->settings_api->get_option('show_credit', 'cbxform_global', 'no');


        $settings_builtin_fields =
            array(
                'cbxform_style' =>
                    array(
                        'formstructure' => array(
                            'name' => 'formstructure',
                            'label' => __('Form Structure', 'cbxform'),
                            'desc' => __('Select structure of the form.', 'cbxform'),
                            'type' => 'select',
                            'default' => 'cbxform',
                            'options' => $form_structres
                        ),
                        'formstyle' => array(
                            'name' => 'formstyle',
                            'label' => __('Input Style', 'cbxform'),
                            'desc' => __('Select style of the forms input.', 'cbxform'),
                            'type' => 'select',
                            'default' => 'cbxform',
                            'options' => $input_styles
                        ),
                        'text_beforeform' => array(
                            'name' => 'text_beforeform',
                            'label' => __('Text Before Form', 'cbxform'),
                            'desc' => __('Text Appear Before Form.', 'cbxform'),
                            'type' => 'wysiwyg',
                            'default' => ''
                        ),
                        'text_afterform' => array(
                            'name' => 'text_afterform',
                            'label' => __('Text After Form', 'cbxform'),
                            'desc' => __('Text Appear Before Form.', 'cbxform'),
                            'type' => 'wysiwyg',
                            'default' => ''
                        ),
                    ),
                'cbxform_email_admin' => array(
                    'status' => array(
                        'name' => 'status',
                        'label' => __('On/Off', 'cbxform'),
                        'desc' => __('Status of Email.', 'cbxform'),
                        'type' => 'checkbox',
                        'default' => '',
                        'desc_tip' => true,
                    ),
                    'format' => array(
                        'name' => 'format',
                        'label' => __('E-mail Format', 'cbxform'),
                        'desc' => __('Select the format of the E-mail.', 'cbxform'),
                        'type' => 'select',
                        'default' => 'html',
                        'options' => array(
                            'html' => __('HTML', 'cbxform'),
                            'plain' => __('Plain', 'cbxform')
                        )
                    ),
                    'name' => array(
                        'name' => 'name',
                        'label' => __('From Name', 'cbxform'),
                        'desc' => __('Name of sender.', 'cbxform'),
                        'type' => 'text',
                        'default' => '{sitename}',
                        'desc_tip' => true,
                        'label_selector' => true,
                        'value_selector' => true,
                        'show_type' => array(),
                    ),
                    'from' => array(
                        'name' => 'from',
                        'label' => __('From Email', 'cbxform'),
                        'desc' => __('From Email Address.', 'cbxform'),
                        'type' => 'text',
                        'default' => get_bloginfo('admin_email'),
                        'desc_tip' => true,
                        'label_selector' => false,
                        'value_selector' => false,
                        'show_type' => array(),
                    ),
                    'to' => array(
                        'name' => 'to',
                        'label' => __('To Email', 'cbxform'),
                        'desc' => __('To Email Address.', 'cbxform'),
                        'type' => 'text',
                        'default' => '',
                        'desc_tip' => true,
                        'label_selector' => false,
                        'value_selector' => true,
                        'show_type' => array('email'),
                    ),
                    'reply_to' => array(
                        'name' => 'reply_to',
                        'label' => __('Reply To', 'cbxform'),
                        'desc' => __('Reply To Email Address.', 'cbxform'),
                        'type' => 'text',
                        'default' => '',
                        'desc_tip' => true,
                        'label_selector' => false,
                        'value_selector' => true,
                        'show_type' => array('email'),
                    ),
                    'subject' => array(
                        'name' => 'subject',
                        'label' => __('Subject', 'cbxform'),
                        'desc' => __('Email Subject.', 'cbxform'),
                        'type' => 'text',
                        'default' => '',
                        'desc_tip' => true,
                        'label_selector' => true,
                        'value_selector' => true,
                        'show_type' => array(),
                    ),
                    'heading' => array(
                        'name' => 'heading',
                        'label' => __('Heading', 'cbxform'),
                        'desc' => __('Email heading.', 'cbxform'),
                        'type' => 'text',
                        'default' => '',
                        'desc_tip' => true,
                        'label_selector' => true,
                        'value_selector' => true,
                        'show_type' => array(),
                    ),
                    'body' => array(
                        'name' => 'body',
                        'label' => __('Body', 'cbxform'),
                        'desc' => __('Email Body.', 'cbxform'),
                        'type' => 'wysiwyg',
                        'default' => '',
                        'desc_tip' => true,
                    ),
                    'cc' => array(
                        'name' => 'cc',
                        'label' => __('CC', 'cbxform'),
                        'desc' => __('Email CC.', 'cbxform'),
                        'type' => 'text',
                        'default' => '',
                        'desc_tip' => true,
                        'label_selector' => false,
                        'value_selector' => true,
                        'show_type' => array('email'),
                    ),
                    'bcc' => array(
                        'name' => 'bcc',
                        'label' => __('BCC', 'cbxform'),
                        'desc' => __('Email BCC.', 'cbxform'),
                        'type' => 'text',
                        'default' => '',
                        'desc_tip' => true,
                        'label_selector' => false,
                        'value_selector' => true,
                        'show_type' => array('email'),
                    )
                ),
                'cbxform_email_user' => array(
                    'status' => array(
                        'name' => 'status',
                        'label' => __('On/Off', 'cbxform'),
                        'desc' => __('Status of Email.', 'cbxform'),
                        'type' => 'checkbox',
                        'default' => '',
                        'desc_tip' => true,
                    ),
                    'format' => array(
                        'name' => 'format',
                        'label' => __('E-mail Format', 'cbxform'),
                        'desc' => __('Select the format of the E-mail.', 'cbxform'),
                        'type' => 'select',
                        'default' => 'html',
                        'options' => array(
                            'html' => __('HTML', 'cbxform'),
                            'plain' => __('Plain', 'cbxform')
                        )
                    ),
                    'name' => array(
                        'name' => 'name',
                        'label' => __('From Name', 'cbxform'),
                        'desc' => __('Name of sender.', 'cbxform'),
                        'type' => 'text',
                        'default' => '{sitename}',
                        'desc_tip' => true,
                        'label_selector' => true,
                        'value_selector' => true,
                        'show_type' => array(),
                    ),
                    'from' => array(
                        'name' => 'from',
                        'label' => __('From Email', 'cbxform'),
                        'desc' => __('From Email Address.', 'cbxform'),
                        'type' => 'text',
                        'default' => get_bloginfo('admin_email'),
                        'desc_tip' => true,
                        'label_selector' => false,
                        'value_selector' => false,
                        'show_type' => array(),
                    ),
                    'to' => array(
                        'name' => 'to',
                        'label' => __('To Email', 'cbxform'),
                        'desc' => __('To Email Address.', 'cbxform'),
                        'type' => 'text',
                        'default' => '',
                        'desc_tip' => true,
                        'label_selector' => false,
                        'value_selector' => true,
                        'show_type' => array('email'),
                    ),
                    'subject' => array(
                        'name' => 'subject',
                        'label' => __('Subject', 'cbxform'),
                        'desc' => __('Email Subject.', 'cbxform'),
                        'type' => 'text',
                        'default' => '',
                        'desc_tip' => true,
                        'label_selector' => true,
                        'value_selector' => true,
                        'show_type' => array(),
                    ),
                    'heading' => array(
                        'name' => 'heading',
                        'label' => __('Heading', 'cbxform'),
                        'desc' => __('Email heading.', 'cbxform'),
                        'type' => 'text',
                        'default' => '',
                        'desc_tip' => true,
                        'label_selector' => true,
                        'value_selector' => true,
                        'show_type' => array(),
                    ),
                    'body' => array(
                        'name' => 'body',
                        'label' => __('Body', 'cbxform'),
                        'desc' => __('Email Body.', 'cbxform'),
                        'type' => 'wysiwyg',
                        'default' => '',
                        'desc_tip' => true,
                    )
                ),
                'cbxform_misc' => array(
                    'formsubmit' => array(
                        'name' => 'formsubmit',
                        'label' => __('Form Submit', 'cbxform'),
                        'desc' => __('Form submission method.', 'cbxform'),
                        'type' => 'radio',
                        'default' => $form_submit,
                        'options' => array(
                            'refresh' => __('Refresh', 'cbxform'),
                            'ajax' => __('Ajax', 'cbxform')
                        ),
                        'desc_tip' => true,
                    ),
                    'showform_suceessful' => array(
                        'name' => 'showform_suceessful',
                        'label' => __('Form show (after successful submission)', 'cbxform'),
                        'desc' => __('Show form after successful submission.', 'cbxform'),
                        'type' => 'checkbox',
                        'default' => $showform_successful,
                        'desc_tip' => true,
                    ),
                    'show_credit' => array(
                        'name' => 'show_credit',
                        'label' => __('Show Credit Under Form', 'cbxform'),
                        'desc' => __('Show Credit Under Form.', 'cbxform'),
                        'type' => 'radio',
                        'options' => array(
                            'yes' => 'Yes',
                            'no' => 'No'
                        ),
                        'default' => $show_credit,
                        'desc_tip' => true,
                    ),
                    'enable_form_submission_limit' => array(
                        'name' => 'enable_form_submission_limit',
                        'label' => __('Enable/Disable', 'cbxform'),
                        'desc' => __('Enable/Disable Form Submission Limit.', 'cbxform'),
                        'type' => 'checkbox',
                        'default' => 'off',
                        'desc_tip' => true,
                    ),
                    'form_submission_limit_val' => array(
                        'name' => 'form_submission_limit_val',
                        'label' => __('Submission Count', 'cbxform'),
                        'desc' => __('Form will not display if submission count is crossed.', 'cbxform'),
                        'type' => 'number',
                        'default' => 0,
                        'desc_tip' => true,
                    ),
                    'success_message' => array(
                        'name' => 'success_message',
                        'label' => __('Success Message', 'cbxform'),
                        'desc' => __('General message after form successful submission.', 'cbxform'),
                        'type' => 'wysiwyg',
                        'default' => 'Form Submitted Successfully',
                        'desc_tip' => true,
                    ),
                )
            );


        $settings_meta_fields = array(); //final setting array that will be passed to different filters

        $meta_sections = $this->cbxform_meta_settings_sections();

        foreach ($meta_sections as $meta_section) {
            if (!isset($settings_builtin_fields[$meta_section['id']])) {
                $settings_builtin_fields[$meta_section['id']] = array();
            }
        }


        foreach ($meta_sections as $meta_section) {
            $settings_meta_fields[$meta_section['id']] = apply_filters('cbxform_meta_' . $meta_section['id'] . '_fields', $settings_builtin_fields[$meta_section['id']]);
        }


        $settings_meta_fields = apply_filters('cbxform_meta_fields', $settings_meta_fields); //final filter if need

        return $settings_meta_fields;
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles($hook)
    {
        global $post_type;
        //only post pages
        if ($hook == 'post.php' || $hook == 'post-new.php' || $hook == 'edit.php') {
            //for form type of post type
            if ('cbxform' == $post_type) {
                //register css for admin
                wp_register_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/cbxform-admin.css', array(), $this->version, 'all');
                wp_register_style($this->plugin_name . 'formfply', plugin_dir_url(__FILE__) . 'css/ply.css', array(), $this->version, 'all');
                wp_register_style($this->plugin_name . 'jquery-ui', 'http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css', array(), $this->version, 'all');
                wp_register_style($this->plugin_name . 'form', plugin_dir_url(__FILE__) . 'css/cbxform-admin.css', array($this->plugin_name . 'formfply'), $this->version, 'all');
                wp_register_style('switcherycss', plugin_dir_url(__FILE__) . 'css/switchery.min.css', array(), $this->version, 'all');
                wp_register_style('cbxformchosen', plugin_dir_url(__FILE__) . 'css/chosen.min.css', array(), $this->version, 'all');
                wp_register_style('cbxform-iframe_modal', plugin_dir_url(__FILE__) . 'css/modal.css');
                //enqueue them
                wp_enqueue_style('wp-color-picker');
                wp_enqueue_style('switcherycss');
                wp_enqueue_style('cbxformchosen');
                wp_enqueue_style($this->plugin_name);
                wp_enqueue_style($this->plugin_name . 'formfply');
                wp_enqueue_style($this->plugin_name . 'form');
                wp_enqueue_style($this->plugin_name . 'jquery-ui');
                wp_enqueue_style('cbxform-iframe_modal');
            }
        }
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts($hook)
    {
        global $post_type;

        //if ('cbxform' != $post_type)  return;

        //register scripts
        wp_register_script($this->plugin_name . 'formply', plugin_dir_url(__FILE__) . 'js/ply.min.js', array('jquery'), $this->version, false);
        wp_register_script($this->plugin_name . 'form', plugin_dir_url(__FILE__) . 'js/cbxform-admin-form.js', array('jquery', 'wp-color-picker', $this->plugin_name . 'formply'), $this->version, false);
        wp_register_script($this->plugin_name . 'clipboardjs', plugin_dir_url(__FILE__) . 'js/clipboard.min.js', array('jquery'), $this->version, false);

        $url = 'https://www.google.com/recaptcha/api.js';
        $url = add_query_arg(
            array(
                'onload' => 'recaptchaCallback',
                'hl' => $this->settings_api->get_option('captcha_language', 'cbxform_security', 'en')
            ), $url
        );
        wp_register_script($this->plugin_name . 'captcha', $url, array(), $this->version, false);
        wp_register_script($this->plugin_name . 'switcheryjs', plugin_dir_url(__FILE__) . 'js/switchery.js', array('jquery'), $this->version, false);
        wp_register_script($this->plugin_name . 'caretjs', plugin_dir_url(__FILE__) . 'js/jquery.caret.js', array('jquery'), $this->version, false);
        wp_register_script($this->plugin_name . 'chosen', plugin_dir_url(__FILE__) . 'js/chosen.jquery.min.js', array('jquery'), $this->version, false);
        wp_register_script($this->plugin_name . 'form-list', plugin_dir_url(__FILE__) . 'js/cbxform-admin-form-list.js', array('jquery'), $this->version, false);
        wp_register_script($this->plugin_name . 'iframe_modal', plugin_dir_url(__FILE__) . 'js/modal.js', array('jquery'));

        //adding js to post edit or add screen
        if ($hook == 'post.php' || $hook == 'post-new.php') {

            //for form type of post type
            if ('cbxform' == $post_type) {

                $preview_control_wrap_start = '';
                $preview_control_wrap_end = '';
                $preview_skeleton_wrap = '';
                $preview_shorttag_wrap_start = '';
                $preview_shorttag_wrap_end = '';

                $form_item_wrap_start = '<div class="formitem" data-type="#type#" id="formitem-#id#" style="width: auto;height: 29px;"><span class="formitemlabel">#label#</span> <span class="formitemname">( #name# )</span><span class="dashicons dashicons-admin-tools"></span><span class="dashicons dashicons-post-trash"></span>';

                //preview starts here
                $preview_skeleton_wrap .= '<div class="formitem_inside_wrap">';
                $preview_skeleton_wrap .= '<div class="formitem_inside formitem_inside_preview">';
                $preview_skeleton_wrap .= '<h3>' . __("Preview", "cbxform") . '</h3>';
                $preview_skeleton_wrap .= '<p>#fieldhtml#</p>';
                $preview_skeleton_wrap .= '</div>';
                //preview end

                //common control starts here
                $preview_control_wrap_start .= '<div class="formitem_inside formitem_inside_control">';
                $preview_control_wrap_start .= '<h3>' . __("Control", "cbxform") . '</h3>';
                $preview_control_wrap_end .= '<input type="hidden" value="#type#" name="cbxformmetabox[fields][formitem-#id#][type]">';
                $preview_control_wrap_end .= '<div class="clearfix"></div>';
                $preview_control_wrap_end .= '</div>';
                //$preview_control_wrap_end .= '</div>'; //formitem_inside_wrap
                //common control ends

                //common short tag starts here
                $preview_shorttag_wrap_start .= '<div class="formitem_inside formitem_inside_short_tag">';
                $preview_shorttag_wrap_start .= '<h3>' . __("Short Tag", "cbxform") . '</h3>';
                $preview_shorttag_wrap_start .= '<p>' . __("Label Name : ", "cbxform") . '{#fielditem-label#}</p>';
                $preview_shorttag_wrap_start .= '<p>' . __("Field value : ", "cbxform") . '{#fielditem-value#}</p>';
                $preview_shorttag_wrap_end .= '<div class="clearfix"></div>';
                $preview_shorttag_wrap_end .= '</div>';
                $preview_shorttag_wrap_end .= '</div>'; //formitem_inside_wrap
                //common short tag ends

                $form_item_wrap_end = '</div>'; //formitem

                $fields = $this->get_form_fields();
                $fields_html_arr = array();

                foreach ($fields as $key => $field) {
                    $fields_html = $form_item_wrap_start;

                    //add the preview for this field
                    $fieldclass = 'cbxformfield' . $key;
                    if (is_callable(array($fieldclass, 'previewhtml_drop'))) {
                        $field_skeleton = call_user_func_array(array($fieldclass, 'previewhtml_drop'), array());
                        $fields_html .= str_replace('#fieldhtml#', $field_skeleton, $preview_skeleton_wrap);
                    }

                    //add the controls needed for this field
                    $fields_html .= $preview_control_wrap_start;
                    if (isset($field['general_controls']) && sizeof($field['general_controls']) > 0) {
                        foreach ($field['general_controls'] as $control) {
                            $controlclass = 'cbxformfieldcontrol' . $control;
                            if (is_callable(array($controlclass, 'previewhtml_drop'))) {
                                $fields_html .= call_user_func_array(array($controlclass, 'previewhtml_drop'), array());
                            }
                        }
                    }

                    $fields_html .= $preview_control_wrap_end;
                    $fields_html .= $preview_shorttag_wrap_start;
                    $fields_html .= $preview_shorttag_wrap_end;
                    $fields_html .= $form_item_wrap_end;
                    $fields_html_arr[$key] = $fields_html;
                }

                $translation_array = array(
                    'deleteconfirm' => __('Are you sure to delete this item?', 'cbxform'),
                    'deleteconfirmok' => __('Sure', 'cbxform'),
                    'deleteconfirmcancel' => __('Oh! No', 'cbxform'),
                    'ajaxurl' => admin_url('admin-ajax.php'),
                    'nonce' => wp_create_nonce('cbxform'),
                    'fields' => wp_json_encode($fields_html_arr),
                    'step' => __('Step', 'cbxform'),
                    'remove_tab' => __('Remove Tab', 'cbxform'),
                    'edit_tab_placeholder' => __('Tab Title', 'cbxform'),
                    'edit_tab_title' => __('Enter Tab Title', 'cbxform'),
                    'import' => __('Import', 'cbxform'),
                    'import_confirm' => __('This will replace form element and this action cannot be undone.', 'cbxform'),
                    'import_modal_button' => sprintf(
                        '<input type="button" class="button button-primary " id="cbxform_open-iframe_modal" value="%1$s" data-content-url="%2$s">',
                        __('Import Forms', 'cbxform'),
                        admin_url('admin-ajax.php?action=cbxform_import_multipleform')),
                );

                wp_localize_script($this->plugin_name . 'form', 'cbxform', $translation_array);

                //enqueue them
                wp_enqueue_script('jquery-ui-tabs');
                wp_enqueue_script('jquery-ui-draggable');
                wp_enqueue_script('jquery-ui-droppable');
                wp_enqueue_script($this->plugin_name . 'formply');
                wp_enqueue_script($this->plugin_name . 'form');
                wp_enqueue_script($this->plugin_name . 'captcha');
                wp_enqueue_script($this->plugin_name . 'clipboardjs');
                wp_enqueue_script($this->plugin_name . 'switcheryjs');
                wp_enqueue_script($this->plugin_name . 'caretjs');
                wp_enqueue_script($this->plugin_name . 'chosen');

            }
        }
        //adding js to post listing
        if ($hook == 'edit.php' && 'cbxform' == $post_type) {

            $translation_array = array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('cbxform'),
                'import_modal_button' => sprintf(
                    '<input type="button" class="button button-primary " id="cbxform_open-iframe_modal" value="%1$s" data-content-url="%2$s">',
                    __('Import Forms', 'cbxform'),
                    admin_url('admin-ajax.php?action=cbxform_import_multipleform')),
            );
            wp_localize_script($this->plugin_name . 'form-list', 'cbxform', $translation_array);
            wp_enqueue_script($this->plugin_name . 'form-list');
            wp_enqueue_script($this->plugin_name . 'iframe_modal');
            wp_enqueue_script($this->plugin_name . 'switcheryjs');
            wp_enqueue_script($this->plugin_name . 'clipboardjs');
        }

    }

    /**
     * Listing of incoming posts Column Header
     *
     * @param $columns
     *
     * @return mixed
     */
    public function columns_header($columns)
    {
        unset($columns['date']);
        $columns['shortcode'] = __('Shortcode', $this->plugin_name);
        $columns['status'] = __('Status', $this->plugin_name);
        $columns['count'] = __('Submission Count', $this->plugin_name);

        return $columns;
    }

    /**
     * Listing of form each row of post type.
     *
     * @param $column
     * @param $post_id
     */
    public function custom_column_row($column, $post_id)
    {
        $setting = get_post_meta($post_id, '_cbxformmeta', true);

        switch ($column) {
            case 'shortcode':
                echo '<span class="cbxformshortcode">[cbxform id=' . $post_id . ']</span>';
                echo '<span class="cbxformshortcodetrigger" data-clipboard-target=".cbxformshortcode" title="' . __("Copy to clipboard", "cbxform") . '">
                        <img style="width: 16px; height: 16px;" src="' . plugins_url('admin/images/clippy.svg', dirname(__FILE__)) . '" alt="' . __('Copy to clipboard', 'cbxform') . '">
                     </span>';
                break;
            case 'status':
                $enable = !empty($setting['status']) ? intval($setting['status']) : 0;
                echo '<input data-postid="' . $post_id . '" ' . (($enable == 1) ? ' checked="checked" ' : '') . ' type="checkbox"  value="' . $enable . '" class="js-switch cbxformjs-switch" autocomplete="off" />';
                break;
            case 'count':
                echo intval(get_post_meta($post_id, '_cbxformmeta_submission_count', TRUE));
        }
    }

    /**
     * @param $columns
     * @return mixed
     */
    public function custom_column_sortable($columns)
    {

        $columns['count'] = 'count';
        return $columns;
    }

    /**
     * Form Enable/Disable Ajax
     */
    public function cbxform_enable_disable()
    {
        check_ajax_referer('cbxform', 'security');

        $enable = (isset($_POST['enable']) && $_POST['enable'] != null) ? intval($_POST['enable']) : 0;
        $post_id = (isset($_POST['postid']) && $_POST['postid'] != null) ? intval($_POST['postid']) : 0;
        $fieldValues = get_post_meta($post_id, '_cbxformmeta', true);
        if ($post_id > 0) {

            $fieldValues['status'] = $enable;

            update_post_meta($post_id, '_cbxformmeta', $fieldValues);
        }
        echo $enable;

        wp_die();
    }

    /**
     * Add metabox for custom post type cbxfeedbackform && cbxfeedbackbtn
     *
     * @since    1.0.0
     */
    public function add_meta_boxes_form()
    {
        //add meta box for creating form and form elements
        add_meta_box(
            'cbxformmetabox', __('Form Parameters', $this->plugin_name), array($this, 'cbxformmetabox_display'), 'cbxform', 'normal', 'high'
        );
        add_meta_box(
            'cbxformmetabox_shortcode', __('Get the Shortcode', $this->plugin_name), array($this, 'cbxformmetabox_shortcode_display'), 'cbxform', 'side', 'low'
        );
        add_meta_box(
            'cbxformmetabox_action', __('Status', $this->plugin_name), array($this, 'cbxformmetabox_shortcode_action'), 'cbxform', 'side', 'low'
        );
        add_meta_box(
            'cbxformmetabox_import', __('Import', $this->plugin_name), array($this, 'cbxformmetabox_import_action'), 'cbxform', 'side', 'low'
        );
        add_meta_box(
            'cbxformmetabox_settings', __('Settings', $this->plugin_name), array($this, 'cbxformmetabox_settings'), 'cbxform', 'normal', 'high'
        );

        $screen = get_current_screen();

        if ($screen->post_type == 'cbxform') {
            add_action('media_buttons', array($this, 'add_cbxform_media_button'));
        }

    }

    /**
     * Get Feedback form fieldgroups
     *
     * @return mixed|void
     */
    public function get_form_fieldgroups()
    {
        $field_group = array(
            'basic' => __('General Fields', 'cbxform'),
            'action' => __('Action Fields', 'cbxform'),
            'security' => __('Security Fields', 'cbxform')
        );

        return apply_filters('cbxform_fieldgroups', $field_group);
    }

    /**
     * Get form fields
     *
     * @return array
     */
    public function get_form_fields()
    {
        $fields = array(
            'text' => array(
                'label' => __('Text', 'cbxform'),
                'name' => 'textfield',
                'group' => 'basic',
                'general_controls' => apply_filters('cbxform_text_add_control', array('label', 'required', 'minlength', 'maxlength')),
            ),
            'number' => array(
                'label' => __('Number', 'cbxform'),
                'name' => 'numberfield',
                'group' => 'basic',
                'general_controls' => apply_filters('cbxform_number_add_control', array('label', 'required', 'minval', 'maxval')),
            ),
            'email' => array(
                'label' => __('Email', 'cbxform'),
                'name' => 'emailfield',
                'group' => 'basic',
                'general_controls' => apply_filters('cbxform_email_add_control', array('label', 'required')),
            ),
            'textarea' => array(
                'label' => __('Textarea', 'cbxform'),
                'name' => 'textareafield',
                'group' => 'basic',
                'general_controls' => apply_filters('cbxform_textarea_add_control', array('label', 'required', 'minlength', 'maxlength', 'rows', 'cols')),
            ),
            'select' => array(
                'label' => __('Select', 'cbxform'),
                'name' => 'selectfield',
                'group' => 'basic',
                'general_controls' => apply_filters('cbxform_select_add_control', array('label', 'required', 'select')),
            ),
            'radio' => array(
                'label' => __('Radio', 'cbxform'),
                'name' => 'radiofield',
                'group' => 'basic',
                'general_controls' => apply_filters('cbxform_radio_add_control', array('label', 'required')),
            ),
            'checkbox' => array(
                'label' => __('Checkbox', 'cbxform'),
                'name' => 'checkboxfield',
                'group' => 'basic',
                'general_controls' => apply_filters('cbxform_checkbox_add_control', array('label', 'required')),
            ),
            'hr' => array(
                'label' => __('HR', 'cbxform'),
                'name' => 'hr',
                'group' => 'basic',
                'general_controls' => apply_filters('cbxform_hr_add_control', array()),
            ),
            'paragraph' => array(
                'label' => __('Paragraph', 'cbxform'),
                'name' => 'paragraph',
                'group' => 'basic',
                'general_controls' => apply_filters('cbxform_paragraph_add_control', array('paragraph')),
            ),
            'captcha' => array(
                'label' => __('Captcha', 'cbxform'),
                'name' => 'captcha',
                'group' => 'security',
                'multiins' => false,
                'general_controls' => apply_filters('cbxform_captcha_add_control', array('label')),
            ),
            'submit' => array(
                'label' => __('Submit', 'cbxform'),
                'name' => 'submit',
                'group' => 'action',
                'multiins' => false,
                'general_controls' => apply_filters('cbxform_submit_add_control', array('label')),
            ),
            'reset' => array(
                'label' => __('Reset', 'cbxform'),
                'name' => 'reset',
                'group' => 'action',
                'multiins' => false,
                'general_controls' => apply_filters('cbxform_reset_add_control', array('label')),
            )
        );

        return apply_filters('cbxform_fields', $fields);
    }

    /**
     * Register Custom Post Type cbxform
     *
     * @since    3.7.0
     */
    public function create_form()
    {
        $labels = array(
            'name' => _x('CBX Form', 'Post Type General Name', $this->plugin_name),
            'singular_name' => _x('CBX Form', 'Post Type Singular Name', $this->plugin_name),
            'menu_name' => __('CBX Forms', $this->plugin_name),
            'parent_item_colon' => __('Parent Item:', $this->plugin_name),
            'all_items' => __('Forms', $this->plugin_name),
            'view_item' => __('View Form', $this->plugin_name),
            'add_new_item' => __('Add New Form', $this->plugin_name),
            'add_new' => __('Add New', $this->plugin_name),
            'edit_item' => __('Edit Form', $this->plugin_name),
            'update_item' => __('Update Form', $this->plugin_name),
            'search_items' => __('Search Form', $this->plugin_name),
            'not_found' => __('Not found', $this->plugin_name),
            'not_found_in_trash' => __('Not found in Trash', $this->plugin_name),
        );
        $args = array(
            'label' => __('CBX Form', $this->plugin_name),
            'description' => __('CBX Form', $this->plugin_name),
            'labels' => $labels,
            'supports' => array('title'),
            'hierarchical' => false,
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_nav_menus' => true,
            'show_in_admin_bar' => true,
            'menu_icon' => 'dashicons-email',
            'can_export' => true,
            'has_archive' => false,
            'exclude_from_search' => true,
            'publicly_queryable' => false,
            'capability_type' => 'post',
        );
        register_post_type('cbxform', $args);
    }

    /**
     * Render Metabox under custom post type
     *
     * @param $post
     *
     * @since 1.0
     *
     */
    public function cbxformmetabox_shortcode_display($post)
    {
        echo '<span class="cbxformshortcode">[cbxform id=' . $post->ID . ']</span>
              <span class="cbxformshortcodetrigger" data-clipboard-target=".cbxformshortcode" title="' . __("Copy to clipboard", "cbxform") . '">
                   <img style="width: 16px; height: 16px;" src="' . plugins_url('admin/images/clippy.svg', dirname(__FILE__)) . '" alt="' . __('Copy to clipboard', 'cbxform') . '">
              </span>';
    }

    /**
     * Render Metabox under custom post type
     *
     * @param $post
     *
     * @since 1.0
     *
     */
    public function cbxformmetabox_shortcode_action($post)
    {
        $fieldValues = get_post_meta($post->ID, '_cbxformmeta', true);
        $status = 1;
        if (isset($fieldValues['status'])) {
            $status = intval($fieldValues['status']);
        }
        $checked = ($status == 1) ? 'checked' : '';
        echo '<div class="">' . __('Off/On', 'cbxform') . '</div><div><input type="hidden" value="0" name="cbxformmetabox[status]"><input class="js-switch cbxformeditjs-switch" autocomplete="off" type="checkbox" value="1" name="cbxformmetabox[status]" ' . $checked . '></div>';
    }

    /**
     * Render Metabox under custom post type
     *
     * @param $post
     *
     * @since 1.0
     *
     */
    public function cbxformmetabox_import_action($post)
    {
        ?>
        <p>
            <label for="cbxform"><?php _e('Select Form', 'cbxform'); ?></label>

            <select class="widefat" id="cbxform_single_import_select" name="cbxform_single_import_select">
                <?php

                $args = array(
                    "posts_per_page" => -1,
                    "orderby" => "ID",
                    "order" => "DESC",
                    'post__not_in' => array($post->ID),
                    "post_type" => "cbxform",
                    "post_status" => "publish"

                );

                $forms = get_posts($args);

                echo '<option value="">' . __('Please Select a form') . '</option>';
                foreach ($forms as $key => $value) {
                    echo '<option value="' . $value->ID . '">' . sprintf(__('%s (ID: %d)', 'cbxform'), get_the_title($value->ID), $value->ID) . '</a></option>';
                }


                wp_reset_postdata(); //needed*/

                ?>
            </select>
            <?php
            $cbx_ajax_icon = plugins_url('cbxform/public/css/busy.gif');

            echo sprintf('<input style="margin-top:10px;" class="button-primary cbxform_single_import" type="submit" name="cbxform_single_import" value="%s" data-postid="%s"/>', __('Import Form', 'cbxform'), $post->ID) . '<span data-busy="0" class="cbxform_ajax_icon"><img
                            src="' . $cbx_ajax_icon . '"/></span>';
            ?>
        </p>

    <?php }


    /**
     * Get the form styles
     *
     * @return type
     */
    public function get_form_structures()
    {
        $form_styles = array(
            'cbxform' => __('Default', 'cbxform'),
            'cbxform-inline' => __('Inline', 'cbxform'),
        );

        return apply_filters('cbxform_structures', $form_styles);
    }

    /**
     * Get the form styles
     *
     * @return type
     */
    public function get_form_input_styles()
    {
        $form_styles = array(
            'cbxform' => __('Full Border', 'cbxform'),
            'cbxform-line' => __('Bottom Border', 'cbxform'),
        );
        return apply_filters('cbxform_styles', $form_styles);
    }


    /**
     * Render Metabox under custom post type
     * @param $post
     *
     * @since 1.0
     *
     */
    public function cbxformmetabox_settings($post)
    {
        $meta_new = new CBXFormmetasettings('cbxformmetabox');

        $form_sections = $this->cbxform_meta_settings_sections();
        $form_fields = $this->cbxform_meta_settings_fields();

        $meta_new->cbxform_show_metabox($form_sections, $form_fields);

    }

    /**
     * Build dropdown for text and textarea
     * @param $label_selector
     * @param $value_selector
     * @param $show_type
     * @return string
     */
    public function cbxform_render_text_textarea_dropdown($label_selector, $value_selector, $all_selector, $ref, $show_type)
    {
        global $post;
        $html = $allfields = '';
        $cbxform_meta = get_post_meta($post->ID, '_cbxformmeta', TRUE);
        $fields = isset($cbxform_meta['fields']) ? $cbxform_meta['fields'] : array();


        foreach ($fields as $key => $field) {
            if (is_array($field)) {
                $noninsertable_fields = apply_filters('cbxform_noninsertable_form_fields', array('submit', 'reset', 'hr', 'paragraph', 'captcha'));
                if (in_array($field['type'], $noninsertable_fields)) {
                    unset($fields[$key]);
                }
            }
        }

        if (is_array($show_type) && sizeof($show_type) > 0) {
            foreach ($fields as $key => $field) {
                if (is_array($field)) {
                    if (!in_array($field['type'], $show_type)) {
                        unset($fields[$key]);
                    }
                }
            }
        }


        $fields = apply_filters('cbxform_selectable_fielditem', array('regular' => $fields));

        $html_label = $html_value = '';


        if (sizeof(array_keys($fields)) > 0) {

            if ($label_selector) {
                $html_label .= '<select name="cbxformfield_select_label" class="cbxformfield_select_' . $ref . ' cbxformfield_select cbxform-chosen" style="float: right;">';
                $html_label .= '<option value="">' . __('Select Label', 'cbxform') . '</option>';

            }

            if ($value_selector) {
                $html_value .= '<select name="cbxformfield_select_value" class="cbxformfield_select_' . $ref . ' cbxformfield_select cbxform-chosen" style="float: right;">';
                $html_value .= '<option value="">' . __('Select value', 'cbxform') . '</option>';
            }


            foreach ($fields as $grp_key => $grp_val) {
                if (sizeof($grp_val) > 0) {

                    if ($label_selector) {
                        $html_label .= '<optgroup label="' . ucfirst($grp_key) . '">';
                        foreach (array_keys($grp_val) as $key => $value) {
                            if (strpos($value, 'step') !== false) {
                                continue;
                            }
                            $html_label .= '<option value="{' . $value . '-label' . '}">' . $fields[$grp_key][$value]['label'] . ' ( ' . $fields[$grp_key][$value]['type'] . ' : ' . $value . ' )' . '</option>';
                        }
                        $html_label .= '</optgroup>';
                    }

                    if ($value_selector) {
                        $html_value .= '<optgroup label="' . ucfirst($grp_key) . '">';
                        foreach (array_keys($grp_val) as $key => $value) {
                            if (strpos($value, 'step') !== false) {
                                continue;
                            }
                            $html_value .= '<option value="{' . $value . '-value}">' . $fields[$grp_key][$value]['label'] . ' ( ' . $fields[$grp_key][$value]['type'] . ' : ' . $value . ' )' . '</option>';
                        }
                        $html_value .= '</optgroup>';
                    }

                    if ($value_selector) {
                        foreach (array_keys($grp_val) as $key => $value) {
                            if (strpos($value, 'step') !== false) {
                                continue;
                            }
                            $allfields .= '{' . $value . '-label' . '}:' . '{' . $value . '-value' . '}';
                        }
                    }
                }
            }
        }


        if ($label_selector) {
            $html .= $html_label . '</select>';
        }
        if ($value_selector) {
            $html .= $html_value . '</select>';
        }
        if ($all_selector) {
            $html .= '<input type="button" name="cbxformfield_select_allfields" class="cbxformfield_select_allfields button-secondary" data-allfields=' . $allfields . ' value="' . __('Insert All', 'cbxform') . '"/>';
        }


        return $html;
    }

    /**
     * Callback filter for 'media_buttons' to add custom form element dropdown
     */
    public function add_cbxform_media_button()
    {
        $label_selector = true;
        $value_selector = true;
        $all_selector = true;
        $ref = 'wysiwyg';
        $show_type = array();

        echo $this->cbxform_render_text_textarea_dropdown($label_selector, $value_selector, $all_selector, $ref, $show_type);
    }

    /**
     * Render Metabox under custom post type
     * @param $post
     *
     * @since 1.0
     *
     */
    public function cbxformmetabox_display($post)
    {
        $last_count = 0;
        $arranged_fields = $single_instance_inputs = $single_instance_inputs_rendered = array();


        $fieldValues = get_post_meta($post->ID, '_cbxformmeta', true);
        $tab_count = (isset($fieldValues['cbxform_tabcounter'])) ? $fieldValues['cbxform_tabcounter'] : 2;
        $allfields = $this->get_form_fields();
        $allfieldgroups = $this->get_form_fieldgroups();
        $fields = isset($fieldValues['fields']) ? $fieldValues['fields'] : array();

        //rearranging the form elements by steps
        if (sizeof($fields) > 0) {
            foreach ($fields as $key => $field) {
                if (strpos($key, 'step') !== false) {
                    $new_key = $key;
                    continue;
                }
                $arranged_fields[$new_key][$key] = $field;
            }
        } else {
            $arranged_fields['step-1'][] = '';
        }

        if (sizeof($arranged_fields) == 0) {
            $arranged_fields['step-1'][] = '';
        }
        //end rearranging form elements

        if (isset($fieldValues['lastcount'])) {
            $last_count = intval($fieldValues['lastcount']);
        }

        wp_nonce_field('cbxformmetabox', 'cbxformmetabox[nonce]');

        ?>

        <div id="cbxformmetabox_wrapper">
            <!--Left side of the meta screen of available fields-->
            <div class="cbxformmetabox_box cbxformmetabox_box_field" id="cbxformmetabox_fields">
                <?php foreach ($allfieldgroups as $groupkey => $groupvalue) { ?>
                    <h3 data-reference=".cbxformmetabox_box_fields_section_<?php echo $groupkey; ?>"><?php echo $groupvalue; ?></h3>
                    <div id="cbxformmetabox_box_fields_section_<?php echo $groupkey; ?>"
                         class="cbxformmetabox_box_fields_section cbxformmetabox_box_fields_section_<?php echo $groupkey; ?>"
                         style="display: none;">
                        <?php
                        foreach ($allfields as $key => $definations) {
                            if (!isset($definations['group'])) $definations['group'] = 'basic'; //assinging nongroup items onto basic
                            if (isset($definations['group']) && $definations['group'] == $groupkey) {
                                $multiins = (isset($definations['multiins']) && $definations['multiins'] == false) ? 0 : 1;
                                if ($multiins == 0) {
                                    $single_instance_inputs[] = $key;
                                } ?>

                                <a href="#" class="button fielditem" data-multiins="<?php echo $multiins; ?>"
                                   data-name="<?php echo $definations['name']; ?>" data-type="<?php echo $key; ?>"
                                   data-label="<?php echo $definations['label']; ?>"><?php echo $definations['label']; ?></a>
                            <?php }
                        } ?>
                        <div class="clearfix"></div>
                    </div>
                <?php } ?>
            </div>


            <!--Right side of the meta screen of dragged fields-->
            <div class="cbxformmetabox_box cbxformmetabox_box_form cbxform_steptabs" id="cbxformmetabox_form">
                <input class="last_count" type="hidden" name="cbxformmetabox[lastcount]"
                       value="<?php echo $last_count; ?>"/>
                <ul id="sortable">
                    <?php print "</pre>";
                    if (sizeof($arranged_fields) > 0) {
                        foreach ($arranged_fields as $k => $v) {
                            $tab_val = (isset($fields[$k])) ? $fields[$k] : 'step-1'; ?>
                            <li class="cbxformtabs_sort"><a href="#<?php echo $k; ?>"><span
                                        class="cbxform_tabtitle"><?php echo sprintf('%s', __($tab_val, 'cbxform')); ?></span></a><span
                                    class='ui-icon ui-icon-close remove_tab'></span><span
                                    class='ui-icon ui-icon-pencil edit_tab'></span></li>
                        <?php }
                    } ?>
                    <!--Addon Implementation-->
                    <?php do_action('cbxformstep_addtab'); ?>
                </ul>
                <input type="hidden" name="cbxformmetabox[cbxform_tabcounter]" class="cbxform_tabcounter"
                       value="<?php echo $tab_count + 1; ?>"/>

                <?php
                if (sizeof($arranged_fields) > 0) {
                    foreach ($arranged_fields as $step => $form_items) { ?>
                        <div id="<?php echo $step; ?>" class="connectedSortable ui-helper-reset">
                            <?php
                            $tab_val = (isset($fields[$step])) ? $fields[$step] : 'step-1';
                            echo '<input type="hidden" class="tab-title" value="' . $tab_val . '" name="cbxformmetabox[fields][' . $step . ']" />';

                            $form_items = array_filter($form_items);

                            if (sizeof($form_items) > 0 && !empty($form_items)) {
                                //if (sizeof($form_items) > 0 ) {
                                foreach ($form_items as $form_item_id => $form_item) {

                                    if (array_key_exists($form_item['type'], $allfields)) {
                                        if (isset($form_item['type']) && in_array($form_item['type'], $single_instance_inputs)) {
                                            $single_instance_inputs_rendered[] = $form_item['type'];
                                        }

                                        $html = '<div class="formitem" data-type= "' . $form_item['type'] . '" id="' . $form_item_id . '" style="width: auto; height: 29px;">';
                                        if (isset($form_item['label']) && $form_item['label'] != '') {
                                            $html .= '<span class="formitemlabel">' . $form_item['label'] . '( ' . $form_item['type'] . ' : ' . $form_item_id . ' )' . '</span>';
                                        } else {
                                            $html .= '<span class="formitemlabel">' . ucfirst($form_item['type']) . ' : ' . $form_item_id . '</span>';
                                        }
                                        $html .= '<span class="dashicons dashicons-admin-tools"></span><span class="dashicons dashicons-post-trash"></span>';
                                        $html .= '<div class="formitem_inside_wrap">';

                                        /*Preview section start*/
                                        $fieldclass = 'cbxformfield' . $form_item['type'];
                                        $html .= '<div class="formitem_inside formitem_inside_preview"><h3>' . __("Preview", "cbxform") . '</h3>';
                                        if (is_callable(array($fieldclass, 'previewhtml_render'))) {
                                            $html .= call_user_func_array(array($fieldclass, 'previewhtml_render'), array($form_item_id, $form_item));
                                        }
                                        $html .= '</div>';
                                        /*Preview section end

                                        /*Control section start*/
                                        $html .= '<div class="formitem_inside formitem_inside_control"><h3>' . __("Control", "cbxform") . '</h3>';

                                        foreach ($form_item as $control_key => $control_val) {

                                            if ($control_key == 'type' || $control_key == 'option') continue;
                                            $controlclass = 'cbxformfieldcontrol' . $control_key;
                                            if (is_callable(array($controlclass, 'previewhtml_render'))) {
                                                $html .= call_user_func_array(array($controlclass, 'previewhtml_render'), array($form_item_id, $form_item));
                                            }
                                        }

                                        $html .= '<input type="hidden" value="' . $form_item['type'] . '" name="cbxformmetabox[fields][' . $form_item_id . '][type]">';
                                        $html .= '<div class="clearfix"></div>';
                                        $html .= '</div>';
                                        /*Control section end*/

                                        /*common short tag starts here*/
                                        $html .= '<div class="formitem_inside formitem_inside_short_tag">';
                                        $html .= '<h3>' . __("Short Tag", "cbxform") . '</h3>';
                                        $html .= '<p>' . __("Label Name : ", "cbxform") . '<span class="cbxshorttag_label cbxshorttag_label_' . $form_item_id . '">{' . $form_item_id . '-label}</span></p>';
                                        /*$html .= '<span class="cbxshorttagtrigger" data-clipboard-target=".cbxshorttag_label_'.$form_item_id.'" title="' . __("Copy to clipboard", "cbxform") . '">
                                                  <img style="width: 16px; height: 16px;" src="' . plugins_url('admin/images/clippy.svg', dirname(__FILE__)) . '" alt="' . __('Copy to clipboard', 'cbxform') . '">
                                                  </span>';*/
                                        $html .= '<p>' . __("Field value : ", "cbxform") . '<span class="cbxshorttag_label cbxshorttag_value_' . $form_item_id . '">{' . $form_item_id . '-value}</span></p>';
                                        /*$html .= '<span class="cbxshorttagtrigger" data-clipboard-target=".cbxshorttag_value_'.$form_item_id.'" title="' . __("Copy to clipboard", "cbxform") . '">
                                                  <img style="width: 16px; height: 16px;" src="' . plugins_url('admin/images/clippy.svg', dirname(__FILE__)) . '" alt="' . __('Copy to clipboard', 'cbxform') . '">
                                                  </span>';*/
                                        $html .= '<div class="clearfix"></div>';
                                        $html .= '</div>';
                                        /*common short tag end here*/

                                        $html .= '</div>';//formitem_inside_wrap
                                        $html .= '</div>';//formitem

                                        echo $html;
                                    }

                                }

                            } ?>
                        </div>
                    <?php }
                } ?>
            </div>
            <!--cbxformmetabox_box_form -->
            <div class="clearfix"></div>
            <input type="hidden" value='<?php echo wp_json_encode($single_instance_inputs_rendered); ?>'
                   id="single_instance_inputs_rendered"/>
            <input type="hidden" value='<?php echo wp_json_encode($single_instance_inputs); ?>'
                   id="single_instance_inputs_avaiable"/>
        </div> <!--cbxformmetabox_wrapper -->
        <?php
    }

    /**
     * Save meta values for form
     *
     * @param        int $post_id The ID of the post being save
     * @param            bool                Whether or not the user has the ability to save this post.
     */
    public function save_post_form($post_id, $post)
    {
        $post_type = 'cbxform';
        $action_array = array();

        // If this isn't a 'cbxform' post, don't update it.
        if ($post_type != $post->post_type) {
            return;
        }

        if (!empty($_POST['cbxformmetabox'])) {

            $postData = $_POST['cbxformmetabox'];

            foreach ($postData['fields'] as $key => $value) {
                if ($value['type'] == 'submit' || $value['type'] == 'reset') {
                    $action_array[$key] = $value;
                    unset($postData['fields'][$key]);
                }
            }
            $postData['fields'] = array_merge($postData['fields'], $action_array);
            if ($this->user_can_save($post_id, 'cbxformmetabox', $postData['nonce'])) {
                unset($postData['nonce']);
                if (!isset($postData['fields'])) {
                    $postData['fields'] = array();
                }
                update_post_meta($post_id, '_cbxformmeta', $postData);
            }
        }
    }

    /**
     * Determines whether or not the current user has the ability to save meta data associated with this post.
     *
     * @param        int $post_id The ID of the post being save
     * @param $action
     * @param $nonce
     * @return bool
     */
    public function user_can_save($post_id, $action, $nonce)
    {
        $is_autosave = wp_is_post_autosave($post_id);
        $is_revision = wp_is_post_revision($post_id);
        $is_valid_nonce = (isset($nonce) && wp_verify_nonce($nonce, $action));

        // Return true if the user is able to save; otherwise, false.
        return !($is_autosave || $is_revision) && $is_valid_nonce;
    }


    /**
     * Add the custom Bulk Action to the select menus of 'cbxform' posts
     */
    public function bulk_admin_footer()
    {
        global $post_type;

        if ($post_type == 'cbxform') {
            ?>
            <script type="text/javascript">
                jQuery(document).ready(function () {
                    jQuery('<option>').val('export').text('<?php _e('Export','cbxform')?>').appendTo("select[name='action']");
                    jQuery('<option>').val('export').text('<?php _e('Export','cbxform')?>').appendTo("select[name='action2']");
                });
            </script>
            <?php
        }
    }


    /**
     * Handle the custom Bulk Action
     */
    public function bulk_action()
    {
        global $typenow;
        $post_type = $typenow;

        if ($post_type == 'cbxform') {

            // get the action
            $wp_list_table = _get_list_table('WP_Posts_List_Table');  // depending on your resource type this could be WP_Users_List_Table, WP_Comments_List_Table, etc
            $action = $wp_list_table->current_action();

            $allowed_actions = array("export");
            if (!in_array($action, $allowed_actions)) return;

            // security check
            check_admin_referer('bulk-posts');

            // make sure ids are submitted.  depending on the resource type, this may be 'media' or 'ids'
            if (isset($_REQUEST['post'])) {
                $post_ids = array_map('intval', $_REQUEST['post']);
            }

            if (empty($post_ids)) return;

            // this is based on wp-admin/edit.php
            $sendback = remove_query_arg(array('exported', 'untrashed', 'deleted', 'cbxform_ids'), wp_get_referer());
            if (!$sendback)
                $sendback = admin_url("edit.php?post_type=$post_type");

            $pagenum = $wp_list_table->get_pagenum();
            $sendback = add_query_arg('paged', $pagenum, $sendback);

            switch ($action) {
                case 'export':

                    $exported = 0;
                    foreach ($post_ids as $post_id) {

                        if (!$this->check_export($post_id)) {
                            wp_die(__('Error exporting post.', 'cbxform'));
                        }

                        $exported++;
                    }

                    $sendback = add_query_arg(array('exported' => $exported, 'cbxform_ids' => join(',', $post_ids)), $sendback);
                    break;

                default:
                    return;
            }

            $sendback = remove_query_arg(array('action', 'action2', 'tags_input', 'post_author', 'comment_status', 'ping_status', '_status', 'post', 'bulk_edit', 'post_view'), $sendback);

            wp_redirect($sendback);
            exit();
        }
    }


    /**
     * Display an admin notice on the Posts page after exporting
     */
    public function bulk_admin_notices()
    {
        global $post_type, $pagenow;

        if ($pagenow == 'edit.php' && $post_type == 'cbxform' && isset($_REQUEST['exported']) && (int)$_REQUEST['exported']) {
            $message = sprintf(_n('Post exported.', '%s posts exported.', $_REQUEST['exported'], 'cbxform'), number_format_i18n($_REQUEST['exported']));
            echo "<div class=\"updated\"><p>{$message}</p></div>";
        }
    }

    /**
     * Performs Export
     * @param $post_id
     * @return bool
     */
    public function check_export($post_id)
    {

        return true;
    }

    /**
     * Performs Export
     * @param $post_id
     * @return bool
     */
    public function cbxform_export()
    {

        $filename = 'formdata.txt';
        header("Content-type: text/plain");
        header("Cache-Control: no-store, no-cache");
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        // Open the text file
        $f = fopen($filename, "w");

        if (isset($_REQUEST["cbxform_ids"]) && sizeof($_REQUEST["cbxform_ids"])) {
            foreach (explode(',', $_REQUEST['cbxform_ids']) as $key => $value) {
                $data[$key]['title'] = get_the_title($value);
                $data[$key]['data'] = get_post_meta($value, '_cbxformmeta', true);
            }
        }

        $data = json_encode($data);

        // Write text
        fwrite($f, $data);

        // Close the text file
        fclose($f);

        // Open file for reading, and read the line
        $f = fopen($filename, "r");

        // Read text
        echo fgets($f);
        fclose($f);
        exit();
    }


    /**
     * Single Form Import
     */
    public function cbxform_import_singleform()
    {
        check_ajax_referer('cbxform', 'security');

        $formid = (isset($_POST['formid']) && $_POST['formid'] != null) ? intval($_POST['formid']) : 0;
        $postid = (isset($_POST['postid']) && $_POST['postid'] != null) ? intval($_POST['postid']) : 0;

        $fieldValues = get_post_meta($formid, '_cbxformmeta', true);

        if ($formid > 0 && $postid > 0) {
            $update = update_post_meta($postid, '_cbxformmeta', $fieldValues);
        }

        if ($update == true) {
            echo json_encode(array('msg' => __('Updated', 'cbxform')));
        } else {
            echo json_encode(array('msg' => __('Not Updated', 'cbxform')));
        }

        wp_die();
    }

    /**
     * Multiple Form Import Modal
     */
    public function cbxform_import_multipleform_modal()
    {

        wp_register_style($this->plugin_name . '-iframe_modal-content-css', plugin_dir_url(__FILE__) . 'css/modal-content.css');
        wp_register_script($this->plugin_name . '-iframe_modal-content-js', plugin_dir_url(__FILE__) . 'js/modal-content.js', array('jquery'));

        wp_enqueue_style($this->plugin_name . '-iframe_modal-content-css');

        $plupload_init = array(
            'runtimes' => 'html5,silverlight,flash,html4',
            'browse_button' => 'plupload-browse-button', // will be adjusted per uploader
            'container' => 'plupload-upload-ui', // will be adjusted per uploader
            'drop_element' => 'drop-target', // will be adjusted per uploader
            'file_data_name' => 'async-upload', // will be adjusted per uploader
            'multiple_queues' => false,
            'max_file_size' => wp_max_upload_size() . 'b',
            'url' => admin_url('admin-ajax.php'),
            'flash_swf_url' => includes_url('js/plupload/plupload.flash.swf'),
            'silverlight_xap_url' => includes_url('js/plupload/plupload.silverlight.xap'),
            'filters' => array(array('title' => __('Allowed Files', 'cbxform'), 'extensions' => 'txt')),
            'multipart' => true,
            'urlstream_upload' => true,
            'multi_selection' => false, // will be added per uploader
            // additional post data to send to our ajax hook
            'multipart_params' => array(
                '_ajax_nonce' => "", // will be added per uploader
                'action' => 'plupload_action', // the ajax action name
                'imgid' => 'fileid' // will be added per uploader
            )
        );

        $translation_array = array(
            'base_plupload_config' => json_encode($plupload_init),
        );

        wp_localize_script($this->plugin_name . '-iframe_modal-content-js', 'cbxform_modal_content', $translation_array);
        wp_enqueue_script('plupload-all');
        wp_enqueue_script($this->plugin_name . '-iframe_modal-content-js');

        include('partials/modal-content.php');

        wp_die();
    }


    /**
     * Multiform Form Import process
     */
    public function cbxform_import_multipleform_process()
    {

        $imgid = $_POST["imgid"];
        check_ajax_referer($imgid . 'pluploadan');

        // handle file upload
        $status = wp_handle_upload($_FILES[$imgid . 'async-upload'], array('test_form' => true, 'action' => 'plupload_action'));

        $uploaded_file_content = json_decode(file_get_contents($status['file']), true);

        if (isset($uploaded_file_content) && sizeof($uploaded_file_content) > 0) {
            foreach ($uploaded_file_content as $key => $value) {

                if ($value['title'] != '') {
                    // Create post object
                    $new_form = array(
                        'post_title' => wp_strip_all_tags($value['title']),
                        'post_content' => '',
                        'post_status' => 'publish',
                        'post_author' => 1,
                        'post_type' => 'cbxform',
                    );

                    $form = wp_insert_post($new_form);

                    if ($form) {
                        $meta = update_post_meta($form, '_cbxformmeta', $value['data']);
                        if ($meta) {
                            $response['success'] = __('Forms Imported Sucessfully', 'cbxform');
                        } else {
                            $response['fail'] = __('Form created but content canot be updated', 'cbxform');
                        }

                    } else {
                        $response['fail'] = __('Cannot Create Form', 'cbxform');
                    }
                } else {
                    $response['fail'] = __('Cannot Create Form', 'cbxform');
                }

            }
        } else {
            $response['fail'] = __('Invalid file content', 'cbxform');
        }

        echo wp_json_encode($response);
        wp_die();
    }
}