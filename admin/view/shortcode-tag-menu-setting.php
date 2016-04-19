<?php
/**
 * Shortcode tag menu
 */

$includeMenu = 0;
if(isset($selectedShortcodeData['included-menu']))
{
    $includeMenu = $selectedShortcodeData['included-menu'];
}

$menuTitle = '';
if(isset($selectedShortcodeData['menu-title']))
{
    $menuTitle = $selectedShortcodeData['menu-title'];
}
?>
<div id="col-right">
    <?php
    if(isset($_GET['edit-coveloping-shortcode']))
    {
        ?>
        <div class="form-wrap">
            <form id="edittag" method="post" action="" class="validate">
                <input type="hidden" name="shortcode" value="<?php echo esc_attr($selectedShortcode); ?>">
                <div class="form-field">
                    <label>Shortcode: </label> <?php echo $selectedShortcode; ?>
                </div>

                <div class="form-field-checkbox">
                    <label for="included-menu">Include In Menu: </label>
                    <input type="checkbox" name="included-menu" id="included-menu" value="1" <?php checked(1, $includeMenu); ?>/>
                </div>

                <div class="form-field">
                    <label for="menu-title">Menu Title:</label>
                    <input type="text" name="menu-title" id="menu-title" value="<?php echo esc_attr($menuTitle); ?>"/>
                </div>

                <p class="submit"><input type="submit" name="add-shortcode-menu-submit" id="add-shortcode-menu-submit" class="button button-primary" value="Save Shortcode Menu" /></p>
                <p class="submit"><input type="submit" name="delete-shortcode-menu-submit" id="delete-shortcode-menu-submit" class="button" value="Delete Shortcode Menu" /></p>
            </form>
        </div>
    <?php
    }
    ?>
</div>