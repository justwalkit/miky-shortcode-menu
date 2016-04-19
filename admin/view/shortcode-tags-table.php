<?php
/**
 * Shortcode tags table
 */
?>
<div id="col-left">
    <table class="shortcode-list" cellspacing="0">
        <tr>
            <th>Shortcode Tag</th>
            <th>Included In Menu</th>
            <th>Fields</th>
            <th>Action</th>
        </tr>
        <?php
            foreach($shortcode_tags as $tag => $function)
            {
                $shortcode = $this->shortcodeTag->get_shortcode($tag);
                $fields = array();
                $fields = $this->shortcodeField->get_fields_shortcode($tag);

                $fieldCount = 0;
                if(isset($developerAssignedFields[$tag]))
                {
                    $fieldCount = count($developerAssignedFields[$tag]);
                } else {
                    $fieldCount = count($fields);
                }
                ?>
                <tr>
                    <td><?php echo $tag; ?></td>
                    <td><?php if(isset($shortcode['included-menu']) && $shortcode['included-menu'] == 1){ echo 'Yes'; } ?></td>
                    <td><?php echo $fieldCount; ?></td>
                    <td>
                        <a href="options-general.php?page=coveloping-shortcode-menu&edit-coveloping-shortcode=<?php echo esc_attr($tag); ?>" class="button button-primary">Edit</a>

                        <?php
                        if(!isset($developerAssignedShortcodes[$tag]))
                        {
                            ?><a href="options-general.php?page=coveloping-shortcode-menu&coveloping-shortcode=<?php echo esc_attr($tag); ?>" class="button">Manage Fields</a><?php
                        }
                        ?>
                    </td>
                </tr>
            <?php
            }
        ?>
    </table>
</div>
