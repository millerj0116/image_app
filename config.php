<?php
//connect to the DB
//Comment this out when you need to debug something:
error_reporting( E_ALL & ~E_NOTICE );

$database_name = 'jeff_image_app';
$username = 'jeff_image_app';
$password = 'RsVH7KsJzKGQQvNZ';
$host = 'localhost';

$db = new mysqli( $host, $username, $password, $database_name );
$folder = 'images';

//check for errors
if( $db->connect_errno > 0 ){
  die('Error: Failure to connect to the Database!');
}

//define the absolute URL. Use for links, like <a> & <img>.
define( 'SITE_URL', 'http://localhost/jeff-php/image_app' );

//define the file root. Use for include, and file upload stuff.
define( 'FILE_PATH', 'C:\xampp\htdocs\jeff-php\image_app' );

//Security! Random Salt for our passwords and cookies
define( 'SALT', 'ftXh3KS4iZ0maYYUUhWIZr5wHP9yFVVoVGkvactW666QZPnheCKAjT70LTiSIkv7Cf31RA2PcLNtuxGk5b8AgCFa9VBII2ka81T3' );

//no close
