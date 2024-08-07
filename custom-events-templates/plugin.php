<?php

/**
 * Plugin Name: Custom Events Templates Loader
 * Description: Load custom event templates from the plugin.
 * Version: 1.0
 * Author: Your Name
 */

defined('ABSPATH') or die('No script kiddies please!');

// Define paths to the event template files
define('CUSTOM_EVENTS_TEMPLATES_DIR', plugin_dir_path(__FILE__));
define('CUSTOM_EVENTS_ARCHIVE', CUSTOM_EVENTS_TEMPLATES_DIR . 'archive-events.php');
define('CUSTOM_EVENTS_SINGLE', CUSTOM_EVENTS_TEMPLATES_DIR . 'single-events.php');

// Hook into WordPress to load the custom templates
function custom_events_load_templates($template)
{
  if (is_singular('events')) {
    return CUSTOM_EVENTS_SINGLE;
  } elseif (is_post_type_archive('events')) {
    return CUSTOM_EVENTS_ARCHIVE;
  }
  return $template;
}

add_filter('template_include', 'custom_events_load_templates');
