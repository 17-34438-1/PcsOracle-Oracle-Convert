<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);
define('BASE_PATH', "http://".$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT']."/PcsOracle/");
define('PASS_PATH', 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/');
define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'].'/PcsOracle/');

//define('BASE_PATH', "http://cpatos.gov.bd/pcs/");
//define('PASS_PATH', 'http://cpatos.gov.bd/');
//echo $_SERVER['REMOTE_ADDR'];
//if(substr($_SERVER['REMOTE_ADDR'],0,7)=="192.168" or substr($_SERVER['REMOTE_ADDR'],0,4)=="10.1")
/*if($_SERVER['SERVER_NAME']=="192.168.16.42")
{
	//echo "in local";
	define('BASE_PATH', 'http://192.168.16.42/pcs/');
	define('PASS_PATH', 'http://192.168.16.42/');
}
else if($_SERVER['SERVER_NAME']=="192.168.16.173")
{
	//echo "in local";
	define('BASE_PATH', 'http://192.168.16.173/pcs/');
	define('PASS_PATH', 'http://192.168.16.173/');
}
else if($_SERVER['SERVER_NAME']=="122.152.54.179")
{
	//echo "in local";
	define('BASE_PATH', 'http://122.152.54.179/pcs/');
	define('PASS_PATH', 'http://122.152.54.179/');
}
else if($_SERVER['SERVER_NAME']=="119.18.146.205")
{
	//echo "in local";
	define('BASE_PATH', 'http://119.18.146.205/pcs/');
	define('PASS_PATH', 'http://119.18.146.205/');
}else if($_SERVER['SERVER_NAME']=="119.18.146.201")
{
	//echo "in local";
	define('BASE_PATH', 'http://119.18.146.201/pcs/');
	define('PASS_PATH', 'http://119.18.146.201/');
}
else 
{
	define('BASE_PATH', "http://cpatos.gov.bd/pcs/");
	define('PASS_PATH', 'http://cpatos.gov.bd/');
}*/

/*
if(substr($_SERVER['REMOTE_ADDR'],0,7)=="192.168" or substr($_SERVER['REMOTE_ADDR'],0,4)=="10.1")
{
	define('BASE_PATH', 'http://192.168.16.42/pcs/');
	define('PASS_PATH', 'http://192.168.16.42/');
}
else 
{
	if($_SERVER['SERVER_NAME']=="115.127.51.199")
	{
		define('BASE_PATH', 'http://115.127.51.199/pcs/');
		define('PASS_PATH', 'http://115.127.51.199/');
	}
	else if($_SERVER['SERVER_NAME']=="180.211.170.142")
	{
		define('BASE_PATH', 'http://180.211.170.142/pcs/');
		define('PASS_PATH', 'http://180.211.170.142/');
	}
	else
	{
		define('BASE_PATH', 'http://122.152.54.179/pcs/');
		define('PASS_PATH', 'http://122.152.54.179/');
	}
}
*/
define('ASSETS_PATH', BASE_PATH."assets/");
define('IMG_PATH', ASSETS_PATH."images/");
define('CSS_PATH', ASSETS_PATH."stylesheets/");
define('ASSETS_WEB_PATH', ASSETS_PATH."frontPage/");
define('ASSETS_JS_PATH', ASSETS_PATH."javascripts/");
define('IMG_RESOURCE_PATH', BASE_PATH.'resources/images/');
define('JTY_SIG_PATH', BASE_PATH.'resources/images/jetty_sarkar_signature_files/');
define('JS_PATH', BASE_PATH.'resources/scripts/');

define('GEN_TITLE', 'Chittagong Port Authority');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code
