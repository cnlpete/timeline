<?php

namespace Timelinetool;

# Override separator due to W3C compatibility.
ini_set('arg_separator.output', '&amp;');

# Compress output.
ini_set('zlib.output_compression', "On");
ini_set('zlib.output_compression_level', 9);

# Set standard timezone for PHP5.
date_default_timezone_set('Europe/Berlin');

# print errors for now
ini_set('display_errors', 1);
ini_set('error_reporting', 1);
ini_set('log_errors', 1);

# Define a standard path
define('PATH_STANDARD', dirname(__FILE__));

# Define current url
define('CURRENT_URL', isset($_SERVER['REQUEST_URI']) ? WEBSITE_URL . $_SERVER['REQUEST_URI'] : WEBSITE_URL);

# Start user session.
@session_start();

# Initialize software
# @todo extension check
require_once PATH_STANDARD . '/vendor/timelinetool/controllers/index.controller.php';
$oIndex = new \Timelinetool\Controllers\Index(array_merge($_GET, $_POST), $_SESSION, $_COOKIE);
$oIndex->getRoutes();

echo $oIndex->show();

?>
