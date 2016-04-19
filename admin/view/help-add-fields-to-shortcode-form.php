<?php
/**
 * Add fields to the shortcode form
 */
?>
<h2>How do I add attributes to the shortcode?</h2>
<p>A shortcode can be provided with extra data by adding attributes to the shortcode. For example if you had an image-gallery shortcode
which allowed you to change the image sizes with a attribute of image-size, you could use the following shortcode.</p>

<p><strong>[image-gallery image-size="large"][/image-gallery]</strong></p>

<p>The shortcode menu plugin allows you to attach new attributes to your shortcode by adding fields to the pop-up form for each of your
shortcodes. On the Shortcode menu settings page "Settings -> Shortcode menu" next to your shortcode you will see a button
"Manage fields", this will take you to a new page where you can add new fields to the form.</p>

<p>You start off by selecting the type of form field you want to add, once you select the type the content for the attribute will be displayed.</p>

<p>The shortcode attribute field is used as the attribute text the shortcode is expecting, using the above example I would type
in "image-size".</p>

<p>The title field will be the text of the label used on the form.</p>

<p>The default value will be default text for the shortcode form.</p>