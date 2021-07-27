<?php
/**
 * xeki config for default variables
 */

$_BASE_PATH = '/';

//System vars
$_DEFAULT_FATAL_PAGE_ERROR = '_default_error.php';

#for develop escenar for display errors
$_DEBUG_MODE = true;

$AG_FORCE_SSL = true;
// this is for force ssl for custom domains
$AG_SSL_DOMAINS = array( ## keep emply for all domains
    'domain.com',
);

// $_ARRAY_MODULES_TO_LOAD_URLS = false; # false for no load
$_ARRAY_MODULES_TO_LOAD_URLS = array();
$_ARRAY_MODULES_TO_LOAD_URLS = array(
    # array No empty for load only the modules
//    "xeki_sitemap",
);