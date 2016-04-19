<?php
require_once dirname(__FILE__) .'/includes/class-coveloping-shortcode-tag.php';
require_once dirname(__FILE__) .'/includes/class-coveloping-shortcode-field.php';

/**
 * The admin area needs to have a settings page where the user can select a shortcode and create the form elements
 * they want to display on the form dialog box
 *
 * This class will create a page that lists all the shortcodes that have been registered with wordpress.
 * From this page you can edit the shortcode and attach this shortcode to be in the menu.
 *
 * Clicking on the manage fields button will allow the user to create a number of form elements that will be added to
 * the dialog box.
 */
class Coveloping_Shortcode_Admin_Shortcode_Settings
{
    /**
     * @var string
     */
    private $optionName = 'coveloping-shortcode-fields';

    /**
     * @var string
     */
    private $pageSlug = 'coveloping-shortcode-menu';

    /**
     * @var bool
     */
    private $fieldUpdated = false;

    /**
     * @var bool
     */
    private $fieldDeleted = false;

    /**
     * Add the admin menu page to the settings menu
     * Add the javascript and css files needed for the page
     * Start the admin page
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_options_settings_page' ) );

        add_action( 'admin_enqueue_scripts', array($this, 'admin_shortcode_scripts') );

        add_action( 'admin_init', array($this, 'admin_process_form'));

        $this->shortcodeTag = new Coveloping_Shortcode_Tag( $this->optionName );
        $this->shortcodeField = new Coveloping_Shortcode_Field( $this->optionName );
    }

    /**
     * Process the form from the correct POST and GET actions from the page
     */
    public function admin_process_form()
    {
        // On posting the form store the new field
        if(isset($_POST['add-field-submit']) || isset($_POST['edit-field-submit']))
        {
            $this->fieldUpdated = $this->shortcodeField->add_new_field($_POST);
        }

        // On a delete action send the shortcode and field ID to be deleted
        if(!empty($_GET['field']) && !empty($_GET['coveloping-shortcode']) && isset($_GET['action']) && $_GET['action'] == 'delete')
        {
            $this->fieldDeleted = $this->shortcodeField->delete_field($_GET['coveloping-shortcode'], $_GET['field']);
        }
    }

    /**
     * Register the javascript and CSS files needed to create the shortcode
     */
    public function admin_shortcode_scripts()
    {
        wp_enqueue_script( 'coveloping-admin-shortcode-settings', plugin_dir_url(__FILE__).'js/admin-shortcode-settings.js', array('jquery', 'jquery-ui-sortable'));

        wp_enqueue_style( 'coveloping-shortcode-button-style', plugin_dir_url(__FILE__).'css/shortcode-button-style.css');
    }

    /**
     * Add the shortcode page to settings menu
     */
    public function add_options_settings_page()
    {
        $page = add_options_page('Coveloping Shortcode Menu', 'Shortcode Menu', 'edit_posts', $this->pageSlug, array($this, 'create_options_page'));

        add_action('load-'.$page, array($this, 'add_help_tab'));
    }

    /**
     * Create the options page.
     * Depending on the GET on the page will let us know if we need to display the all shortcode form
     * or a single shortcode form element
     */
    public function create_options_page()
    {
        $this->add_help_tab();
        ?>
        <div class="wrap coveloping-shortcode-settings-page">
            <h2>Coveloping Shortcode Menu</h2>

            <?php
                if(isset($_GET['coveloping-shortcode']))
                {
                    $this->display_shortcode_fields($_GET['coveloping-shortcode']);
                } else {
                    $this->display_list_shortcodes();
                }
            ?>
        </div>
        <?php
    }

    /**
     * Display the form to create the different field elements
     *
     * @param $shortcode
     */
    private function display_shortcode_fields( $shortcode )
    {
        // default fields
        $field = array('field-id' => '', 'attribute' => '', 'title' => '', 'order' => '', 'type' => '', 'default-value' => '',
                        'options-value' => '', 'options-text' => '');

        // if there is a get field set search for the existing field in the shortcode array
        if(isset($_GET['field']) && isset($_GET['action']) && $_GET['action'] == 'edit')
        {
            $field = $this->shortcodeField->get_field($shortcode, $_GET['field']);
            $field['field-id'] = $_GET['field'];
        }

        // If it's a new field set it to have a unique ID
        if(empty($field['field-id']))
        {
            $field['field-id'] = uniqid('field-');
        }

        $this->display_messages();
        ?>
        <p><a href="options-general.php?page=<?php echo $this->pageSlug; ?>">&#8656; Back To Shortcodes</a></p>
        <h3>Manage Field For Shortcode: <?php echo $shortcode; ?></h3>
        <?php
            if(isset($_GET['action']))
            {
                printf('<a href="options-general.php?page=%s&coveloping-shortcode=%s" class="add-new-h2">Add field</a>', $this->pageSlug, $shortcode);
            }
        ?>
        <style>
            .remove-multiple-option,
            .add-multiple-option
            {
                cursor: pointer;
            }
        </style>
        <div id="col-container">
            <div id="col-right">
                <p>Drag the fields to reorder there position on the form.</p>
                <?php
                $listfields = get_option($this->optionName, array());
                ?>
                <table id="list-fields-table" class="shortcode-list" cellspacing="0" style="width: 100%;">
                    <thead>
                    <tr>
                        <th>Attribute</th>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Value</th>
                        <th>Order</th>
                        <th>Action</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php
                    if(!empty($listfields[$shortcode]['fields']))
                    {
                        foreach ($listfields[$shortcode]['fields'] as $listfieldId => $listfield) {
                            ?>
                            <tr id="<?php echo $listfieldId; ?>">
                                <td><?php if(!empty($listfield['attribute'])){echo $listfield['attribute'];} ?></td>
                                <td><?php if(!empty($listfield['title'])){echo $listfield['title'];} ?></td>
                                <td><?php echo $listfield['type']; ?></td>
                                <td><?php echo $this->truncate($listfield['default-value'], 50); ?></td>
                                <td class="order_cell"><?php echo $listfield['order']; ?></td>
                                <td>
                                    <a href="options-general.php?page=coveloping-shortcode-menu&coveloping-shortcode=<?php echo $shortcode; ?>&field=<?php echo $listfieldId; ?>&action=edit" class="button button-primary">Edit</a>
                                    <a href="options-general.php?page=coveloping-shortcode-menu&coveloping-shortcode=<?php echo $shortcode; ?>&field=<?php echo $listfieldId; ?>&action=delete" class="button button">Delete</a>
                                </td>
                            </tr>
                        <?php
                        }
                    }
                    ?>
                    </tbody>
                </table>
            </div>

            <div id="col-left">
                <div class="form-wrap">
                    <?php
                        if(isset($_GET['action']) && $_GET['action'] == 'edit')
                        {
                            ?><h3>Save Field</h3><?php
                        } else {
                            ?><h3>Add New Field</h3><?php
                        }
                    ?>
                    <form id="edittag" method="post" action="" class="validate">
                        <input type="hidden" name="shortcode" id="shortcode" value="<?php echo esc_attr($shortcode); ?>">
                        <input type="hidden" name="field_id" value="<?php echo esc_attr($field['field-id']); ?>" />
                        <input type="hidden" name="field-order" id="field-order" value="<?php echo esc_attr(count($this->shortcodeField->get_fields_shortcode($shortcode)) + 1); ?>"/>
                        <?php wp_nonce_field( 'coveloping-shortcode-nonce-form' ); ?>
                        <div class="form-field">
                            <label for="field-type">Type: </label>
                            <select name="field-type" id="field-type" required="required" class="postform">
                                <option value="">Select Field Type</option>
                                <option value="checkbox" <?php selected('checkbox', $field['type']); ?>>Checkbox</option>
                                <option value="color" <?php selected('color', $field['type']); ?>>Color Picker</option>
                                <option value="date" <?php selected('date', $field['type']); ?>>Date Picker</option>
                                <option value="divide" <?php selected('divide', $field['type']); ?>>Divider</option>
                                <option value="info" <?php selected('info', $field['type']); ?>>Information</option>
                                <option value="radio" <?php selected('radio', $field['type']); ?>>Radio Button</option>
                                <option value="select" <?php selected('select', $field['type']); ?>>Select</option>
                                <option value="text" <?php selected('text', $field['type']); ?>>Textbox</option>
                                <option value="textarea" <?php selected('textarea', $field['type']); ?>>Textarea</option>
                            </select>
                        </div>

                        <?php
                        /**
                         * Depending on the field type we need to show different form elements
                         */
                        $hideAttribute = 'hidden';
                        $hideTitle = 'hidden';
                        $hideCheckbox = 'hidden';
                        $hideValueOptions = 'hidden';
                        $hideTextareaValueOptions = 'hidden';
                        $hideMultiOptions = 'hidden';

                            if(!empty($field['type']))
                            {
                                switch($field['type'])
                                {
                                    case 'checkbox':
                                        $hideAttribute = '';
                                        $hideTitle = '';
                                        $hideCheckbox = '';
                                    break;

                                    case 'color':
                                    case 'date':
                                    case 'text':
                                        $hideAttribute = '';
                                        $hideTitle = '';
                                        $hideValueOptions = '';
                                    break;

                                    case 'info':
                                        $hideTextareaValueOptions = '';
                                    break;

                                    case 'radio':
                                    case 'select':
                                        $hideAttribute = '';
                                        $hideTitle = '';
                                        $hideMultiOptions = '';
                                    break;

                                    case 'textarea':
                                        $hideAttribute = '';
                                        $hideTitle = '';
                                        $hideTextareaValueOptions = '';
                                    break;
                                }
                            }
                        ?>

                        <div id="shortcode-attribute-option" class="form-field form-required <?php echo esc_attr($hideAttribute); ?>">
                            <label for="field-shortcode-attribute">Shortcode Attribute: </label>
                            <input name="field-shortcode-attribute" id="field-shortcode-attribute" type="text" value="<?php echo esc_attr($field['attribute']); ?>" size="40">
                            <p>
                                <small>The attribute that is used on the shortcode</small>
                            </p>
                        </div>

                        <div id="field-title-option" class="form-field form-required <?php echo esc_attr($hideTitle); ?>">
                            <label for="field-title">Title: </label>
                            <input name="field-title" id="field-title" type="text" value="<?php echo esc_attr($field['title']); ?>" size="40">
                            <p>
                                <small>Text used on the frontend form</small>
                            </p>
                        </div>

                        <div class="field-options">
                            <div id="checkbox-options" class="<?php echo esc_attr($hideCheckbox); ?>">
                                <div class="form-field-checkbox">
                                    <label for="field-default-checked">Default Checked: </label>
                                    <input type="checkbox" value="1" name="field-default-checked" id="field-default-checked" <?php if($field['type'] == 'checkbox'){ checked(1, $field['default-value']); } ?>/>
                                </div>
                            </div>

                            <div id="default-value-options" class="<?php echo esc_attr($hideValueOptions); ?>">
                                <div class="form-field">
                                    <label for="field-default-value">Default Value: </label>
                                    <input type="text" value="<?php echo esc_attr($field['default-value']); ?>" name="field-default-value" id="field-default-value" size="40" aria-required="true"/>
                                </div>
                            </div>

                            <div id="textarea-default-value-options" class="<?php echo esc_attr($hideTextareaValueOptions); ?>">
                                <div class="form-field">
                                    <label for="field-default-textarea-value">Default Content: </label>
                                    <textarea name="field-default-textarea-value" id="field-default-textarea-value" size="40" aria-required="true"/><?php echo $field['default-value']; ?></textarea>
                                </div>
                            </div>

                            <div id="multi-options" class="<?php echo esc_attr($hideMultiOptions); ?>">
                                <div class="form-field-options">
                                    <label for="field-options">Field Options: </label>

                                    <?php
                                        if(is_array($field['options-value']) && !empty($field['options-value']) && !empty($field['options-text']))
                                        {
                                            foreach($field['options-value'] as $index => $value)
                                            {
                                                ?>
                                                <div class="field-options">
                                                    <input type="text" name="field-options-value[]" placeholder="Value" value="<?php echo esc_attr($value); ?>" />
                                                    <input type="text" name="field-options-text[]" placeholder="Text" value="<?php echo esc_attr($field['options-text'][$index]); ?>" />
                                                    <span class="remove-multiple-option button">Remove Item</span>
                                                </div>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <div class="field-options">
                                                <input type="text" value="" name="field-options-value[]" placeholder="Value" />
                                                <input type="text" value="" name="field-options-text[]" placeholder="Text" />
                                                <span class="remove-multiple-option button">Remove Item</span>
                                            </div>
                                            <?php
                                        }
                                    ?>
                                    <p><span class="add-multiple-option button">Add Item</span></p>
                                </div>
                            </div>
                        </div>

                        <?php
                            if(isset($_GET['action']) && $_GET['action'] == 'edit')
                            {
                                ?><p class="submit"><input type="submit" name="edit-field-submit" id="edit-field-submit" class="button button-primary" value="Edit Field" /></p><?php
                            } else {
                                ?><p class="submit"><input type="submit" name="add-field-submit" id="add-field-submit" class="button button-primary" value="Add New Field" /></p><?php
                            }
                        ?>
                    </form>

                    <div id="field-options-clone" class="hidden">
                        <input type="text" value="" name="field-options-value[]" placeholder="Value" />
                        <input type="text" value="" name="field-options-text[]" placeholder="Text" />
                        <span class="remove-multiple-option button">Remove Item</span>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Display a list of all the shortcodes on the page and allow the user to edit the shortcode options
     */
    private function    display_list_shortcodes()
    {
        global $shortcode_tags;

        // set default for success updates
        $successUpdate = false;
        $successDelete = false;

        // Store the shortcode for the menu options
        if(!empty($_POST['add-shortcode-menu-submit']))
        {
            $successUpdate = $this->shortcodeTag->update_shortcode_menu($_POST);
        }

        // delete the shortcode
        if(!empty($_POST['delete-shortcode-menu-submit']))
        {
            $successDelete = $this->shortcodeTag->delete_shortcode_menu($_POST);
        }

        $developerAssignedShortcodes = array();
        $developerAssignedShortcodes = apply_filters('coveloping_shortcode_button', $developerAssignedShortcodes);

        $developerAssignedFields = array();
        $developerAssignedFields = apply_filters('coveloping_shortcode_form', $developerAssignedFields);

        $this->shortcodeTag->update_shortcode_settings_from_developer( $developerAssignedShortcodes );

        if(!empty($shortcode_tags))
        {
            if(isset($_GET['edit-coveloping-shortcode']))
            {
                $selectedShortcode = $_GET['edit-coveloping-shortcode'];
                $selectedShortcodeData = $this->shortcodeTag->get_shortcode($selectedShortcode);
            }
            ?>
            <div id="col-container">
                <?php
                    if($successUpdate)
                    {
                        ?><div id="message" class="updated fade"><p>Shortcode Updated</p></div><?php
                    }

                    if($successDelete)
                    {
                        ?><div id="message" class="error fade"><p>Shortcode Deleted</p></div><?php
                    }
                ?>
                <?php require_once plugin_dir_path( __FILE__ ) . 'view/shortcode-tag-menu-setting.php'; ?>
                <?php require_once plugin_dir_path( __FILE__ ) . 'view/shortcode-tags-table.php'; ?>
            </div>
            <?php
        }
    }

    /**
     * @param $str
     * @param $len
     * @return string
     */
    private function truncate($str, $len)
    {
        $tail = max(0, $len-10);
        $trunk = substr($str, 0, $tail);
        $trunk .= strrev(preg_replace('~^..+?[\s,:]\b|^...~', '...', strrev(substr($str, $tail, $len-$tail))));
        return $trunk;
    }

    /**
     * Add the help tab to the page
     */
    public function add_help_tab()
    {
        $screen = get_current_screen();

        ob_start();
        require_once dirname(__FILE__).'/view/help-add-shortcode-to-menu.php';
        $helpAddShortcodeMenu = ob_get_contents();
        ob_end_clean();

        $screen->add_help_tab(
            array(
                'id' => 'coveloping-shortcode-menu-add',
                'title' => __('Add Shortcode To Menu', 'coveloping-shortcode-menu'),
                'content' => $helpAddShortcodeMenu
            )
        );

        ob_start();
        require_once dirname(__FILE__).'/view/help-add-fields-to-shortcode-form.php';
        $helpAddFieldsToForm = ob_get_contents();
        ob_end_clean();

        $screen->add_help_tab(
            array(
                'id' => 'coveloping-shortcode-menu-add-fields',
                'title' => __('Add Fields To Form', 'coveloping-shortcode-menu'),
                'content' => $helpAddFieldsToForm
            )
        );

        ob_start();
        require_once dirname(__FILE__).'/view/help-developers.php';
        $helpDevelopers = ob_get_contents();
        ob_end_clean();

        $screen->add_help_tab(
            array(
                'id' => 'coveloping-shortcode-menu-developers',
                'title' => __('How Can Developers Use Shortcode Menu?', 'coveloping-shortcode-menu'),
                'content' => $helpDevelopers
            )
        );
    }

    /**
     * Display the status messages to the user
     */
    private function display_messages()
    {
        $shortcodeFieldErrors = $this->shortcodeField->get_errors();
        if(!empty( $shortcodeFieldErrors ))
        {
            ?>
            <div id="message" class="error fade">
                <?php
                    foreach($shortcodeFieldErrors as $errors)
                    {
                        printf('<p>%s</p>', $errors);
                    }
                ?>
            </div>
            <?php
        }

        // If fields is successfully updated display update message
        if($this->fieldUpdated)
        {
            ?><div id="message" class="updated fade"><p>Field Updated</p></div><?php
        }

        // If field is successfully deleted display delete message
        if($this->fieldDeleted)
        {
            ?><div id="message" class="error fade"><p>Field Deleted</p></div><?php
        }
    }
}