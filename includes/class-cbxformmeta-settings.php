<?php
/**
 * Class CBXFormmetasettings
 */
if (!class_exists('CBXFormmetasettings')):

    class CBXFormmetasettings {

        /**
         * meta settings sections array
         *
         * @var array
         */
        private $meta_settings_sections = array();

        /**
         * meta settings fields array
         *
         * @var array
         */
        private $meta_settings_fields = array();

        /**
         * Singleton instance
         *
         * @var object
         */
        private static $_instance;
        public $metakey;

        public function __construct($metakey = 'cbxform') {
            $this->metakey = $metakey;
            add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
        }

        /**
         * Enqueue scripts and styles
         */
        public function admin_enqueue_scripts() {
            wp_enqueue_style('wp-color-picker');
            wp_enqueue_media();
            wp_enqueue_script('wp-color-picker');
            wp_enqueue_script('jquery');
        }

        /**
         * Set settings sections
         *
         * @param array   $sections setting sections array
         */
        public function set_meta_settings_sections($sections) {
            $this->meta_settings_sections = $sections;

            return $this;
        }

        /**
         * Add a single section
         *
         * @param array   $section
         */
        public function add_meta_settings_section($section) {
            $this->meta_settings_sections[] = $section;

            return $this;
        }

        /**
         * Set settings fields
         *
         * @param array   $fields settings fields array
         */
        public function set_meta_settings_fields($fields) {
            $this->meta_settings_fields = $fields;

            return $this;
        }

        /**
         * Add settings fields
         * 
         * @param type $section
         * @param type $field
         * @return \CBXFormmetasettings
         */
        public function add_meta_settings_field($section, $field) {
            $defaults = array(
                'name'  => '',
                'label' => '',
                'desc'  => '',
                'type'  => 'text'
            );

            $arg                                    = wp_parse_args($field, $defaults);
            $this->meta_settings_fields[$section][] = $arg;

            return $this;
        }

        /**
         * Call from admin page to render settings meta box
         */
        public function cbxform_show_metabox($form_sections, $form_fields) {

            $this->cbxform_show_nav($form_sections);
            $this->cbxform_show_items($form_sections, $form_fields);

            $this->script();
        }

        /**
         * Show nav links for meta settings box
         * 
         * @param type $form_sections
         */
        public function cbxform_show_nav($form_sections) {
            $html = '<h2 class="cbxform-meta-settings nav-tab-wrapper">';
            foreach ($form_sections as $tab) {
                $html .= sprintf('<a href="#%1$s" class="nav-tab cbxform-meta-nav-tab" id="%1$s-tab">%2$s</a>', $tab['id'], $tab['title']);
            }
            $html .= '</h2>';
            echo $html;
        }

        /**
         * Show nav items for meta settings box
         * 
         * @param type $form_sections
         * @param type $form_fields
         */
        public function cbxform_show_items($form_sections, $form_fields) {

            echo '<div class="metabox-holder">
                <div class="postbox">';
            foreach ($form_sections as $key => $form_defination) {
                ?>
                <div id="<?php echo $form_defination['id']; ?>" style="padding: 20px; display: none;" class="cbxform-setting-meta group">
                    <?php if (array_key_exists('desc', $form_defination)): ?>
                        <p><?php echo $form_defination['desc']; ?></p>
                    <?php endif; ?>
                    <table class="table form-table">

                        <?php
                        foreach ($form_fields[$form_defination['id']] as $form_fields_key => $form_field_defination) {

                            $callback = 'cbxform_callback_' . $form_field_defination['type'];

                            $form_field['section'] = $form_defination['id'];
                            $form_field['id']      = $form_field_defination['name'];
                            $form_field['label']   = $form_field_defination['label'];
                            $form_field['desc']    = $form_field_defination['desc'];
                            $form_field['default'] = $form_field_defination['default'];
                            if(isset($form_field_defination['label_selector'])){
                                $form_field['label_selector'] = $form_field_defination['label_selector'];
                            }
                            if(isset($form_field_defination['value_selector'])){
                                $form_field['value_selector'] = $form_field_defination['value_selector'];
                            }
                            if(isset($form_field_defination['show_type'])){
                                $form_field['show_type'] = $form_field_defination['show_type'];
                            }

                            if (isset($form_field_defination['options'])) {
                                $form_field['options'] = $form_field_defination['options'];
                            }
                            
                            echo '<tr>';

                            if(is_callable(array($this, $callback),true)){
                                call_user_func(array($this, $callback), $form_field);
                            }
                            
                            echo '</tr>';
                        }
                        ?>
                    </table>
                </div>

                <?php
            }
            echo '</div>
            </div>';
        }

        /**
         * Call back for text field
         * 
         * @global type $post
         * @param type $args
         */
        public function cbxform_callback_text($args) {

            global $post;

            $post_id      = $post->ID;
            $cbxform_meta = get_post_meta($post_id, '_cbxformmeta', TRUE);
            $dropdown_html = '';
            $cbxform_admin = new Cbxform_Admin(CBXFORM_PLUGIN_NAME,CBXFORM_PLUGIN_VERSION);

            if(isset($args['label_selector']) || isset($args['value_selector'])){

                if(isset($args['show_type']) && sizeof($args['show_type']) > 0){
                    $show_type = $args['show_type'];
                }else{
                    $show_type = array();
                }
                $dropdown_html = $cbxform_admin->cbxform_render_text_textarea_dropdown($args['label_selector'],$args['value_selector'],false,'',$show_type);
            }

            $value        = esc_attr($args['default']);

            if (isset($cbxform_meta['settings']) && isset($cbxform_meta['settings'][$args['section']])) {
                if (is_array($cbxform_meta['settings']) && !empty($cbxform_meta['settings']) && array_key_exists($args['id'], $cbxform_meta['settings'][$args['section']])) {
                    $value = $cbxform_meta['settings'][$args['section']][$args['id']][0];
                }
            }


            $size = isset($args['size']) && !is_null($args['size']) ? $args['size'] : 'regular';
            $html = sprintf('<td><span style="" class="cbxform_meta_settings_label"><strong> %s</strong></span>', $args['label']);
            $html .= '</td><td>';
            $html .= '<p style="padding-bottom: 10px;">'.$dropdown_html.'</p>';
            $html .= sprintf('<input type="text" style="height: 30px;" class="%3$s %1$s-text cbxformfield_select_target" id="%5$s[%2$s][%3$s]" name="%5$s[settings][%2$s][%3$s][]" value="%4$s"/>', $size, $args['section'], $args['id'], $value, $this->metakey);
            $html .= sprintf('<br><span style="" class="description"> %s</span></td>', $args['desc']);

            echo $html;
        }

        /**
         * Displays a rich text wysiwyg for a settings field
         *
         * @param array   $args settings field args
         */
        function cbxform_callback_wysiwyg( $args ) {
            global $post;
            $post_id      = $post->ID;
            $cbxform_meta = get_post_meta($post_id, '_cbxformmeta', TRUE);

            $value = $args['default'];
            $size = isset( $args['size'] ) && !is_null( $args['size'] ) ? $args['size'] : '500px';

            if (isset($cbxform_meta['settings']) && isset($cbxform_meta['settings'][$args['section']])) {
                if (is_array($cbxform_meta['settings']) && !empty($cbxform_meta['settings']) && array_key_exists($args['id'], $cbxform_meta['settings'][$args['section']])) {
                    $value = $cbxform_meta['settings'][$args['section']][$args['id']][0];
                }
            }

            $editor_settings = array(
                'teeny' => true,
                'textarea_name' => 'cbxformmetabox[settings]['.$args['section'].']['.$args['id'].'][]',
                'textarea_rows' => 10,
                'editor_class'  => 'cbxformfield_select_target_wysiwyg'
            );

            echo  sprintf('<td><span style="" class="cbxform_meta_settings_label"><strong> %s</strong></span>', $args['label']);
            echo  '</td>';
            echo  '<td style="max-width: ' . $size . ';">';
            wp_editor( $value, $args['section'] . '-' . $args['id'], $editor_settings );
            echo sprintf('<br><span style="" class="description"> %s</span></td>', $args['desc']);

        }

        /**
         * Callback for number
         * 
         * @global type $post
         * @param type $args
         */
        public function cbxform_callback_number($args) {


            global $post;
            $post_id      = $post->ID;
            $cbxform_meta = get_post_meta($post_id, '_cbxformmeta', TRUE);
            $value        = '';


            if (isset($cbxform_meta['settings']) && isset($cbxform_meta['settings'][$args['section']])) {
                if (is_array($cbxform_meta['settings']) && !empty($cbxform_meta['settings']) && array_key_exists($args['id'], $cbxform_meta['settings'][$args['section']])) {
                    $value = $cbxform_meta['settings'][$args['section']][$args['id']][0];
                }
            }


            $size = isset($args['size']) && !is_null($args['size']) ? $args['size'] : 'regular';
            $html = sprintf('<td><span style="" class="cbxform_label"><strong> %s</strong></span>', $args['label']);
            $html .= '</td>';

            $html .= sprintf('<td><input type="number" style="height: 30px;" class="%1$s-text" id="%2$s-%3$s" name="%5$s[settings][%2$s][%3$s][]" value="%4$s"/>', $size, $args['section'], $args['id'], $value, $this->metakey);
            $html .= sprintf('<br><span style="" class="description"> %s</span></td>', $args['desc']);

            echo $html;
        }

        /**
         * Displays a info field
         *
         * @param array  $args settings field args
         */
        function cbxform_callback_title($args) {
            $html = sprintf('<td colspan="2"><h3> %s</h3></td>', $args['label']);
            echo $html;
        }
        /**
         * Call back for text field
         * 
         * @global type $post
         * @param type $args
         */
        public function cbxform_callback_color($args) {

            global $post;


            $post_id      = $post->ID;
            $cbxform_meta = get_post_meta($post_id, '_cbxformmeta', false);
            $value        = '';

            if (isset($cbxform_meta['settings']) && isset($cbxform_meta['settings'][$args['section']])) {
                if (is_array($cbxform_meta['settings']) && !empty($cbxform_meta['settings']) && array_key_exists($args['id'], $cbxform_meta['settings'][$args['section']])) {
                    $value = $cbxform_meta['settings'][$args['section']][$args['id']][0];
                }
            }


            $size = isset($args['size']) && !is_null($args['size']) ? $args['size'] : 'regular';
            $html = sprintf('<td><span style="" class="cbxform_meta_settings_label"><strong> %s</strong></span>', $args['label']);
            $html .= '</td>';
            $html .= '<td>';
            $html .= sprintf('<input type="text" style="height: 30px;" class="%3$s %1$s-text wp-color-picker-field" id="%5$s[%2$s][%3$s]" name="%5$s[settings][%2$s][%3$s][]" value="%4$s"/>', $size, $args['section'], $args['id'], $value, $this->metakey);
            $html .= sprintf('<br><span style="" class="description"> %s</span></td>', $args['desc']);

            echo $html;
        }

        /**
         * Call back for text area
         * 
         * @global type $post
         * @param type $args
         */
        public function cbxform_callback_textarea($args) {

            global $post;
            $post_id      = $post->ID;
            $cbxform_meta = get_post_meta($post_id, '_cbxformmeta', TRUE);
            $value        = '';
            $dropdown_html = '';
            $cbxform_admin = new Cbxform_Admin(CBXFORM_PLUGIN_NAME,CBXFORM_PLUGIN_VERSION);

            if(isset($args['label_selector']) || isset($args['value_selector'])){

                if(isset($args['show_type']) && sizeof($args['show_type']) > 0){
                    $show_type = $args['show_type'];
                }else{
                    $show_type = array();
                }
                $dropdown_html = $cbxform_admin->cbxform_render_text_textarea_dropdown($args['label_selector'],$args['value_selector'],false,'',$show_type);
            }

            if (isset($cbxform_meta['settings']) && isset($cbxform_meta['settings'][$args['section']])) {
                if (is_array($cbxform_meta['settings']) && !empty($cbxform_meta['settings']) && array_key_exists($args['id'], $cbxform_meta['settings'][$args['section']])) {
                    $value = $cbxform_meta['settings'][$args['section']][$args['id']][0];
                }
            }

            $size = isset($args['size']) && !is_null($args['size']) ? $args['size'] : 'regular';
            $html = sprintf('<td><span style="" class="cbxform_label"><strong> %s</strong></span>', $args['label']);
            $html .= '</td><td>';
            $html .= '<p style="padding-bottom: 10px;">'.$dropdown_html.'</p>';
            $html .= sprintf('<textarea style="width: 350px;" rows="5" cols="140" class="%3$s %1$s-text cbxformfield_select_target" id="%2$s[%3$s]" name="%5$s[settings][%2$s][%3$s][]">%4$s</textarea>', $size, $args['section'], $args['id'], $value, $this->metakey);

            $html .= sprintf('<br><span class="description"> %s</span></td>', $args['desc']);
            echo $html;
        }

        /**
         * Call back for check box
         * 
         * @global type $post
         * @param type $args
         */
        public function cbxform_callback_checkbox($args) {


            global $post;

            $post_id      = $post->ID;
            $cbxform_meta = get_post_meta($post_id, '_cbxformmeta', TRUE);
            $value = $args['default'];

            if (isset($cbxform_meta['settings']) && isset($cbxform_meta['settings'][$args['section']])) {
                if (is_array($cbxform_meta['settings']) && !empty($cbxform_meta['settings']) && array_key_exists($args['id'], $cbxform_meta['settings'][$args['section']])) {
                    $value = $cbxform_meta['settings'][$args['section']][$args['id']][0];
                }
            }


            $html = sprintf('<td><span style="" class="cbxform_label"><strong> %s</strong></span>', $args['label']);
            $html .= '</td>';
            $html .= sprintf('<input type="hidden" name="%3$s[settings][%1$s][%2$s]" value="off" />', $args['section'], $args['id'], $this->metakey);
            $html .= sprintf('<td><input type="checkbox" class="checkbox js-switch cbxformeditjs-switch" id="%1$s[%2$s]" name="%5$s[settings][%1$s][%2$s][]" value="on"%4$s />', $args['section'], $args['id'], $value, checked($value, 'on', false), $this->metakey);
            $html .= sprintf('<span for="%1$s[%2$s]"> %3$s</span></td>', $args['section'], $args['id'], $args['desc']);

            echo $html;
        }

        /**
         * Call back for check box
         * 
         * @global type $post
         * @param type $args
         */
        public function cbxform_callback_radio($args) {

            global $post;
            $post_id = $post->ID;

            $cbxform_meta = get_post_meta($post_id, '_cbxformmeta', TRUE);

            $value = $args['default'];
            
            if (isset($cbxform_meta['settings']) && isset($cbxform_meta['settings'][$args['section']])) {
                if (is_array($cbxform_meta['settings']) && !empty($cbxform_meta['settings']) && array_key_exists($args['id'], $cbxform_meta['settings'][$args['section']])) {
                    $value = $cbxform_meta['settings'][$args['section']][$args['id']][0];
                }
            }

            $html = sprintf('<td><span style="" class="cbxform_label"><strong> %s</strong></span>', $args['label']);
            $html .= '</td><td>';

           
            foreach ($args['options'] as $key => $label) {
                $html .= sprintf('<input type="radio" class="%1$s"  name="%5$s[settings][%1$s][%2$s][]" id="%1$s[%2$s]"" value="%7$s" %4$s /> %6$s ', $args['section'], $args['id'], $value, checked($value, $key, false), $this->metakey, $label,$key);
            }
            
            $html .= sprintf('<br /><br /><span for="%1$s[%2$s]"> %3$s</span></td>', $args['section'], $args['id'], $args['desc']);

            echo $html;
        }

        /**
         * Callback for select field
         * 
         * @global type $post
         * @param type $args
         */
        public function cbxform_callback_select($args) {


            global $post;
            $post_id = $post->ID;

            $cbxform_meta = get_post_meta($post_id, '_cbxformmeta', TRUE);

            $value = $args['default'];


            if (isset($cbxform_meta['settings']) && isset($cbxform_meta['settings'][$args['section']])) {
                if (is_array($cbxform_meta['settings']) && !empty($cbxform_meta['settings']) && array_key_exists($args['id'], $cbxform_meta['settings'][$args['section']])) {
                    $value = $cbxform_meta['settings'][$args['section']][$args['id']][0];
                }
            }

            $size = isset($args['size']) && !is_null($args['size']) ? $args['size'] : 'regular';
            $html = sprintf('<td><span style="" class="cbxform_label"><strong> %s</strong></span>', $args['label']);
            $html .= '</td>';
            $html .= sprintf('<td><select class="%1$s"  name="%4$s[settings][%2$s][%3$s][]" id="%2$s[%3$s]">', $size, $args['section'], $args['id'], $this->metakey);

            foreach ($args['options'] as $key => $label) {

                $html .= sprintf('<option value="%s"%s>%s</option>', $key, selected($value, $key, false), $label);
            }

            $html .= sprintf('</select>');


            $html .= sprintf('<br><span class="description"> %s</span></td>', $args['desc']);

            echo $html;
        }

        /**
         * Tabbable JavaScript codes & Initiate Color Picker
         *
         * This code uses localstorage for displaying active tabs
         */
        function script() {
            ?>
            <script type="text/javascript">
                jQuery(document).ready(function ($) {
                    //Initiate Color Picker
                    $('.wp-color-picker-field').wpColorPicker();

                    // Switches option sections
                    $('.group').hide();
                    var activetab = '';
                    if (typeof (localStorage) != 'undefined') {
                        //get
                        activetab = localStorage.getItem("cbxactactivetabmeta");
                    }
                    if (activetab != '' && $(activetab).length) {
                        $(activetab).fadeIn();
                    } else {
                        $('.group:first').fadeIn();
                    }
                    $('.group .collapsed').each(function () {
                        $(this).find('input:checked').parent().parent().parent().nextAll().each(
                                function () {
                                    if ($(this).hasClass('last')) {
                                        $(this).removeClass('hidden');
                                        return false;
                                    }
                                    $(this).filter('.hidden').removeClass('hidden');
                                });
                    });

                    if (activetab != '' && $(activetab + '-tab').length) {
                        $(activetab + '-tab').addClass('nav-tab-active');
                    }
                    else {
                        $('.nav-tab-wrapper a:first').addClass('nav-tab-active');
                    }

                    $('.nav-tab-wrapper a').click(function (evt) {
                        $('.nav-tab-wrapper a').removeClass('nav-tab-active');
                        $(this).addClass('nav-tab-active').blur();
                        var clicked_group = $(this).attr('href');
                        if (typeof (localStorage) != 'undefined') {
                            //set
                            localStorage.setItem("cbxactactivetabmeta", $(this).attr('href'));
                        }
                        $('.group').hide();
                        $(clicked_group).fadeIn();
                        evt.preventDefault();
                    });

                    $('.wpsa-browse').on('click', function (event) {
                        event.preventDefault();

                        var self = $(this);

                        // Create the media frame.
                        var file_frame = wp.media.frames.file_frame = wp.media({
                            title: self.data('uploader_title'),
                            button: {
                                text: self.data('uploader_button_text')
                            },
                            multiple: false
                        });

                        file_frame.on('select', function () {
                            attachment = file_frame.state().get('selection').first().toJSON();

                            self.prev('.wpsa-url').val(attachment.url);
                        });

                        // Finally, open the modal
                        file_frame.open();
                    });

                    //add chooser

                });
            </script>

            <style type="text/css">
                /** WordPress 3.8 Fix **/
                .form-table th { padding: 20px 10px; }
                #wpbody-content .metabox-holder { padding-top: 5px; }
                .chosen-container-single, .chosen-container-multi{
                    min-width: 244px !important;
                }
                #poststuff h2.nav-tab-wrapper{
                    margin-bottom: 0px !important;
                    padding-bottom: 0px;;
                }
                .nav-tab-active, .nav-tab-active:hover{
                    background: #fff !important;
                }

                .nav-tab-active, .nav-tab-active:focus, .nav-tab-active:focus:active, .nav-tab-active:hover, .postbox{
                    border-top: 2px solid #0085ba;
                }
            </style>
            <?php
        }
    }
endif;