<?php
$welcomeMsg = '
    ===== BUSA COMMAND-LINE-INTERFACE UTILITY =====
    
    This little script will act like a PHP "Interactive shell", much like 
    how you can run the command "php -a" in linux. The reason for 
    creating this script is:
    
    1) The "php -a" command supplies an "Interactive mode" NOT a 
    "Interactive shell" in Windows
    
    2) This script will boot-strap Drupal for you before supplying you 
    a CLI
    
    You can run this script with the command: php -f index-cli.php
    
    ========================================
    
';

print PHP_EOL . str_replace("    ", "", $welcomeMsg);

// Set time-zone to kill annoying PHP warning
date_default_timezone_set('America/New_York');

// Create variable(s) Drupal expects to be defined
global $_SERVER;
$_SERVER = array (
    'SCRIPT_URL' => '/server',
    'SCRIPT_URI' => 'http://127.0.0.1/cli',
    'HTTP_USER_AGENT' => 'CLI',
    'HTTP_HOST' => '127.0.0.1',
    'SERVER_SOFTWARE' => 'CLI',
    'SERVER_NAME' => 'CLI',
    'SERVER_ADDR' => '127.0.0.1',
    'SERVER_PORT' => 80,
    'REMOTE_ADDR' => '127.0.0.1',
    'DOCUMENT_ROOT' => __DIR__,
    'SCRIPT_FILENAME' => __FILE__,
    'REMOTE_PORT' => 80,
    'SERVER_PROTOCOL' => 'HTTP/1.1',
    'REQUEST_METHOD' => 'GET',
    'QUERY_STRING' => '',
    'REQUEST_URI' => '/cli',
    'SCRIPT_NAME' => '/cli',
    'PHP_SELF' => '/index-cli.php',
    'REQUEST_TIME' => time()
);

// Bootstrap-Drupal
print 'Bootstrapping Drupal... ';
define('DRUPAL_ROOT', getcwd());
require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

// Kill any output-buffers left open by Drupal
@ob_end_clean();
@ob_end_clean();
while (@ob_end_clean());
print " done.\n\n";

// Open input-reader
$fh = fopen('php://stdin', 'r');
$lastStdOut = '';
$cmd = '';
$bcLvl = 0;

// Read all input lines, execute...
while (true) {

    // Show PHP-CLI indicator
    if ( trim($lastStdOut) !== '' ) {
        print PHP_EOL;
        $lastStdOut = '';
    }
    if ( $bcLvl > 0 ) {
        print 'php { ';
    } else {
        print 'php > ';
    }
    
    $line = rtrim(fgets($fh));
    $bcLvl += substr_count($line, '{') - substr_count($line, '}');
    $cmd.= $line;
    $cmd = trim($cmd);
    if ( $bcLvl > 0 or substr($cmd, -1) !== ';' ) {
        continue;
    }
    
    // Execute the code from std-in
    $lastStdOut = '';
    ob_start();
    eval($cmd);
    $lastStdOut = ob_get_contents();
    ob_end_flush();
    
    $cmd = '';
}
