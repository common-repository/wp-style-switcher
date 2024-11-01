WP Style Switcher README
version 1.4, 2004-05-12

Step 1.

Put the wp-style-switcher.php file in your wp-content/plugins/ directory. 


Step 2.

Enable the plugin in your options.

Click on 'Plugins' in the admin area, then 'Activate' next to the WP Style Switcher row.


Step 3.

Include the stylesheet in your index.php file.
Find this line near the top of index.php:

 @import url( <?php echo $siteurl; ?>/wp-layout.css );

And change it to:

 @import url( <?php wp_stylesheet(); ?> );

Note: you can pass in the name of the style you want to use as the default like this:

 @import url( <?php wp_stylesheet("my-new-style"); ?> );


Step 4.

Add this to your index.php page in the #menu area. For example, right before the last </ul> tag in the page.

<?php wp_style_switcher(); ?>

Note: if you want to display the switcher as a drop down list, set the $wp_style_presentation value in the plugin file:

$wp_style_presentation = 'dropdown';

Note: if you want to put this elsewhere on the page, you can call the function like this and it will not output <li> tags around the switcher:

<?php wp_style_switcher(0); ?>

Note: if you want to display samples of the styles, you can call the function like this:

<?php wp_style_switcher(1, "sample"); ?>
<?php wp_style_switcher(0, "sample"); ?> (without the li tags around it)

Note: if you want to display links to screenshots of the styles, you can call the function like this:

<?php wp_style_switcher(1, "", 1); ?> (not showing the sample)
<?php wp_style_switcher(0, "sample", 1); ?> (showing the sample, without the li tags around it)


Step 5.

Put the styles you want to use with the switcher into a wp-style directory in your blog root. You can use the included samples if you like.

The directory structure must follow this pattern:

 wp-style/name-of-style/style.css

Optionally you can include:

 wp-style/name-of-style/sample.gif (should be 50px x 50px)
 wp-style/name-of-style/screenshot.gif (any size you like)
 wp-style/name-of-style/images/filename.gif (images used by your style)

You will also see this file in the wp-style directory, it is used as a default if there is no sample included with a style: 

 wp-style/sample.gif


That should be it - have fun!

--Alex
