<?php

// WP Style Switcher
// version 1.6, 2005-02-06
// 
// copyright 2004-2005 Alex King
// http://www.alexking.org/software/wordpress/

/*
Plugin Name: WP Style Switcher
Plugin URI: http://www.alexking.org/software/wordpress/
Description: A CSS Style Switcher for WordPress.
Author: Alex King
Author URI: http://www.alexking.org/
*/ 


// change this to the name of default style you want to use
$wp_style_default = 'wp-default';

// set this to "dropdown" instead of "text" to use a dropdown list switcher.
$wp_style_presentation = 'text';


function wp_style_cookie($default = "") {
	global $wp_style_default;
	if (empty($default)) {
		$default = $wp_style_default;
	}
	$expire = time() + 30000000;
	$urlinfo = parse_url(get_settings('home'));
	$path = $urlinfo['path'];
	$domain = $urlinfo['host'];
	if ($domain == 'localhost') {
		$domain = '';
	}
	if (!empty($_GET["wpstyle"])) {
		setcookie("wpstyle"
				 ,stripslashes($_GET["wpstyle"])
				 ,$expire
				 ,$path
				 ,$domain
				 );
		header("Location: ".get_settings('home').'/');
	}
	else if (empty($_COOKIE["wpstyle"])) {
		setcookie("wpstyle"
				 ,$default
				 ,$expire
				 ,$path
				 ,$domain
				 );
	}
}

function wp_stylesheet($default = "") {
	global $wp_style_default;
	if (empty($default)) {
		$default = $wp_style_default;
	}
	if (!empty($_COOKIE["wpstyle"]) && file_exists('wp-style/'.$_COOKIE["wpstyle"].'/style.css')) {
		$style = $_COOKIE["wpstyle"];
	}
	else {
		$style = $default;
	}
	echo get_settings('home').'/wp-style/'.$style.'/style.css';
}

function wp_style_switcher($in_list = 1, $type = "text", $preview = 0) {
	global $wp_style_default, $wp_style_presentation;
	$styles = array();
	$path = "wp-style/";
	if ($handle = opendir($path)) {
		while (false !== ($file = readdir($handle))) {
			if (is_dir($path.$file) && file_exists($path.$file.'/style.css') && substr($file, 0, 1) != '.') {
				$styles[] = $file;
			}
		}
	}
	closedir($handle);
	if (count($styles) > 0) {
		asort($styles);
		reset($styles);
		$ss = '<ul id="styleswitcher">'."\n";
		if ($wp_style_presentation == 'dropdown') {
			$ss .= '<li>'."\n"
				 . '	<select name="styleswitcher" onchange="location.href=\''.get_settings('home').'/index.php?wpstyle=\' + this.options[this.selectedIndex].value;">'."\n"
				 ;
			foreach ($styles as $style) {
				if ((!empty($_COOKIE["wpstyle"]) && $_COOKIE["wpstyle"] == $style) ||
				    (empty($_COOKIE["wpstyle"]) && $wp_style_default == $style)) {
					$ss .= '		<option value="'.$style.'" selected="selected">'
					     . htmlspecialchars(ucwords(str_replace(array('_','-'), ' ', $style)))
					     . '</option>'."\n"
					     ;
				}
				else {
					$ss .= '		<option value="'.$style.'">'
					     . htmlspecialchars(ucwords(str_replace(array('_','-'), ' ', $style)))
					     . '</option>'."\n"
					     ;
				}
			}
			$ss .= '	</select>'."\n"
				 . '</li>'."\n"
				 ;
		}
		else {
			foreach ($styles as $style) {
				switch ($type) {
					case "sample":
						if (file_exists('wp-style/'.$style.'/sample.gif')) {
							$sample = get_settings('home').'/wp-style/'.$style.'/sample.gif';
						}
						else {
							$sample = get_settings('home').'/wp-style/sample.gif';
						}
						$display = '<img src="'.$sample.'" alt="'
								   .htmlspecialchars(ucwords(str_replace(array('_','-'), ' ', $style)))
								   .'" title="Use this Style" />';
						break;
					default:
						$display = htmlspecialchars(ucwords(str_replace(array('_','-'), ' ', $style)));
						break;
				}
				if ($preview != 0) {
					if (file_exists('wp-style/'.$style.'/screenshot.gif')) {
						$display .= '</a><br /><a href="'.get_settings('home')
									.'/wp-style/'.$style.'/screenshot.gif">Screenshot';
					}
				}
				if (!empty($_COOKIE["wpstyle"]) && $_COOKIE["wpstyle"] == $style) {
					$ss .= '	<li>'.$display.'</li>'."\n";
				}
				else {
					$ss .= '	<li><a href="'
						   .get_settings('home').'/'.get_settings('blogfilename')
						   .'?wpstyle='.urlencode($style).'">'
						   .$display.'</a></li>'."\n";
				}
			}
		}
		$ss .= '</ul>';
	}
	if ($in_list == 1) {
		$ss = '<li id="style">Style:'.$ss.'</li>';
	}
	echo $ss;
}

wp_style_cookie();

?>