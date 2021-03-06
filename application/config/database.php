<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the 'Database Connection'
| page of the User Guide.
|
| -------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| -------------------------------------------------------------------
|
|	['hostname'] The hostname of your database server.
|	['username'] The username used to connect to the database
|	['password'] The password used to connect to the database
|	['database'] The name of the database you want to connect to
|	['dbdriver'] The database type. ie: mysql.  Currently supported:
				 mysql, mysqli, postgre, odbc, mssql, sqlite, oci8
|	['dbprefix'] You can add an optional prefix, which will be added
|				 to the table name when using the  Active Record class
|	['pconnect'] TRTRUEALSE - Whether to use a persistent connection
|	['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
|	['cache_on'] TRUE/FALSE - Enables/disables query caching
|	['cachedir'] The path to the folder where cache files should be stored
|	['char_set'] The character set used in communicating with the database
|	['dbcollat'] The character collation used in communicating with the database
|				 NOTE: For MySQL and MySQLi databases, this setting is only used
| 				 as a backup if your server is running PHP < 5.2.3 or MySQL < 5.0.7
|				 (and in table creation queries made with DB Forge).
| 				 There is an incompatibility in PHP with mysql_real_escape_string() which
| 				 can make your site vulnerable to SQL injection if you are using a
| 				 multi-byte character set and are running versions lower than these.
| 				 Sites using Latin-1 or UTF-8 database character set and collation are unaffected.
|	['swap_pre'] A default table prefix that should be swapped with the dbprefix
|	['autoinit'] Whether or not to automatically initialize the database.
|	['stricton'] TRUE/FALSE - forces 'Strict Mode' connections
|							- good for ensuring strict SQL while developing
|
| The $active_group variable lets you choose which connection group to
| make active.  By default there is only one group (the 'default' group).
|
| The $active_record variables lets you determine whether or not to load
| the active record class
*/

// 财务里面channel.php 1585行也需要改一些数据库连接配置

$active_group = 'guoguovpn';
$active_record = TRUE;

$online_host = array(
    'www.phpvpn100.com',
    'www.phpvpn100.com:8088' //用于切换用的，如果有的大的改动，可以现在这个端口测试，没问题再转到80端口
    );
if (in_array($_SERVER['HTTP_HOST'], $online_host)) {
    $db['guoguovpn']['hostname'] = 'localhost';
    $db['guoguovpn']['username'] = 'root';
    $db['guoguovpn']['password'] = 'baixiaoshi7080#';
    $db['guoguovpn']['database'] = 'guoguovpn';
    $db['guoguovpn']['dbdriver'] = 'mysqli';
} else {
    $db['guoguovpn']['hostname'] = '192.168.1.106';
    $db['guoguovpn']['username'] = 'beibei';
    $db['guoguovpn']['password'] = '';
    $db['guoguovpn']['database'] = 'guoguovpn';
    $db['guoguovpn']['dbdriver'] = 'mysql';
}



$db['guoguovpn']['dbprefix'] = '';
$db['guoguovpn']['pconnect'] = TRUE;
$db['guoguovpn']['db_debug'] = TRUE;
$db['guoguovpn']['cache_on'] = FALSE;
$db['guoguovpn']['cachedir'] = '';
$db['guoguovpn']['char_set'] = 'utf8';
$db['guoguovpn']['dbcollat'] = 'utf8_general_ci';
$db['guoguovpn']['swap_pre'] = '';
$db['guoguovpn']['autoinit'] = TRUE;
$db['guoguovpn']['stricton'] = FALSE;



