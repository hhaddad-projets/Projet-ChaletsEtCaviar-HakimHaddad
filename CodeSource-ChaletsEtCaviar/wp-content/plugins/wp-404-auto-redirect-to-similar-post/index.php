<?php
// Silence is golden.

// ... And redirection to domain root is love <3
if(!isset($_SERVER['SCRIPT_URL']) || empty($_SERVER['SCRIPT_URL']) || !isset($_SERVER['SCRIPT_URI']) || empty($_SERVER['SCRIPT_URI']))
    return;

$domain = str_replace($_SERVER['SCRIPT_URL'], '', $_SERVER['SCRIPT_URI']);
if(empty($domain))
    return;

header('Location: ' . $domain, true, 301);
exit();