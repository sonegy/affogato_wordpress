<?php
/*
Plugin Name: Affogato Comment System
Plugin URI: http://commentweb-beta.libplanet.so
Description: skplanet planetX realtime comments plugin. Affogato!
Version: 0.1.1
Author: LEE,JAE-IL <sonegy@sk.com>
Author URI: http://commentweb-beta.libplanet.so
License: A "Slug" license name e.g. GPL2
*/
if (!function_exists('json_decode')) {
  require_once('lib/JSON.php');
  $__JSON = new Services_JSON(); 

  function json_decode($value) {
    global $__JSON;
    return $__JSON.decode($value);
  } 
}

global $affogatoOptions;  # main options array in wp database options table
global $affogatoComments_defaults;
$affogatoOptions = get_option('affogatoComments');
$affogatoComments_defaults = array (
  'appKey' => '',
  'domainProtocol' => 'http'
);

define('DNS_ERROR', false);
define('AFFOGATO_APP_KEY',          $affogatoOptions['appKey']);
define('AFFOGATO_DOMAIN',           'commentweb-beta.libplanet.so');
define('AFFOGATO_ADMIN_DOMAIN_URL', 'https://developers.skplanetx.com/my-center/app-station/comment-plugin/');
define('AFFOGATO_PLUGIN_URL',       '//' . AFFOGATO_DOMAIN . '/script/plugin.js');
define('AFFOGATO_API',              DNS_ERROR ? '1.234.67.16' : AFFOGATO_DOMAIN); 
define('AFFOGATO_API_COMMENTS_URL', 'http' . '://' . AFFOGATO_API . '/api/apps/' . AFFOGATO_APP_ID . '/comments');
define('AFFOGATO_PREFIX_PAGE_ID',   'affogato-');
define('AFFOGATO_VERSION',          '0.1.1');
define('REMOTE_CALL_TIMEOUT',       3);

/////////////////////////////////////////////////////
// plugin activation, deactivation, uninstall
register_activation_hook(__FILE__, 'affogatoComments_init_hack');
register_deactivation_hook(__FILE__, 'affogatoComments_deactivate_hack'); # hack may not be needed here
register_uninstall_hook(__FILE__, 'affogatoComments_uninit_hack');    # or here
# stupid wordpress, can't call function in an included file in activation hook?
function affogatoComments_init_hack() {
  global $affogatoComments_defaults;
  delete_option('affogatoComments');
  add_option('affogatoComments', $affogatoComments_defaults); 
}
function affogatoComments_deactivate_hack() {
  //delete_option('affogatoComments');
}
function affogatoComments_uninit_hack() { 
  delete_option('affogatoComments');
}
function affogato_comments_sanatize($input) {
  return $input;
}
/////////////////////////////////////////////////////

function affogato_page_id() {
  global $post;
  return AFFOGATO_PREFIX_PAGE_ID . $post->ID;
}

/**
  * Filters/Actions
  **/
// ugly global hack for comments closine
$EMBED = false;
function affogato_comments_template($value) {
  global $EMBED;
  global $post;
  global $comments;

  $EMBED = true;
  return dirname(__FILE__) . '/comments.php';
}

/*
function affogato_comments_text() {
  return 'text';
}

function affogato_comments_number($comment_text) {
  global $post;
  return '--';
}
 */

function affogato_get_comments_number($count) {
  /*
  $response = wp_remote_get(
    AFFOGATO_API_COMMENTS_URL . '?page_id=' . urlencode(affogato_page_id()), 
    array('timeout' => REMOTE_CALL_TIMEOUT ));

  if ($response['response']['code'] == 200) {
    $result = json_decode($response['body']);
    return intval($result->total_count);
  }
  return 'err';
   */
  return '-';
}

function affogato_admin_init() {
  register_setting('affogatoComments_options', 'affogatoComments', 'affogato_comments_sanatize');
}

function affogato_manage() {
  include('manage.php');
}
function affogato_admin_manage() {
  if (AFFOGATO_APP_KEY) {
    ?><iframe src="<?php echo AFFOGATO_ADMIN_DOMAIN_URL; ?>" width="100%" height="100%"  /><?php
  } else {
    echo "<p>AFFOGATO APP KEY NOT DEFINED</p>";
  }
}

function affogato_admin_menu() {
  add_submenu_page(
    'options-general.php', 
    'affogato settings', 
    'Affogato Comments', 
    'manage_options', 
    'affogato', 
    'affogato_manage');
  add_submenu_page(
    'edit-comments.php', 
    'affogato comments manager', 
    'Affogato Comments', 
    'moderate_comments', 
    'affogato', 
    'affogato_admin_manage');
}
add_action('admin_init', 'affogato_admin_init' );
add_action('admin_menu', 'affogato_admin_menu');

add_filter('comments_template', 'affogato_comments_template');
/*
add_filter('comments_text', 'affogato_comments_text');
add_filter('comments_number', 'affogato_comments_number');
 */
add_filter('get_comments_number', 'affogato_get_comments_number');
?>
