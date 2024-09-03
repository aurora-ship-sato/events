<?php

/**
 * Plugin Name: Custom Events Templates Loader
 * Description: Load custom event templates from the plugin.
 * Version: 1.0
 * Author: sato kanamu
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

// プラグインが有効化されたときのフック
register_activation_hook(__FILE__, 'cet_activate_plugin');
function cet_activate_plugin()
{
  // JSONファイルのパス
  $json_file_path = plugin_dir_path(__FILE__) . 'acf-export-2024-09-03.json';

  // JSONファイルの内容を取得
  $json_content = file_get_contents($json_file_path);

  if ($json_content) {
    $field_groups = json_decode($json_content, true);

    if (!empty($field_groups)) {
      foreach ($field_groups as $group) {
        // ACFのインポート関数を使用してカスタムフィールドグループをインポート
        acf_import_field_group($group);
      }
    }
  }
}

// プラグインが無効化されたときのフック
register_deactivation_hook(__FILE__, 'cet_deactivate_plugin');
function cet_deactivate_plugin()
{
  // 削除するフィールドグループのキー（JSONファイルから取得）
  $field_group_key = 'group_5f694267a7c7c';

  if (function_exists('acf_delete_field_group')) {
    acf_delete_field_group($field_group_key);
  }
}

