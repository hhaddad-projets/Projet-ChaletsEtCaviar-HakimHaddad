<?php
// -- AJAX_SEARCH.PHP --
//mimic the actual admin-ajax
define('DOING_AJAX', true);

if (!isset( $_POST['action']))
    die('-1');

//make sure you update this line
//to the relative location of the wp-load.php
require_once('../../../wp-load.php');
//require_once('search.php');

//Typical headers
header('Content-Type: text/html');
send_nosniff_header();

//Disable caching
header('Cache-Control: no-cache');
header('Pragma: no-cache');

$action = esc_attr(trim($_POST['action']));

//A bit of security
$allowed_actions = WD_ASL_Ajax::getAll();
WD_ASL_Ajax::registerAll(true);


if(in_array($action, $allowed_actions)) {
    if(is_user_logged_in())
        do_action('ASL_'.$action);
    else
        do_action('ASL_nopriv_'.$action);
} else {
    die('-1');
}