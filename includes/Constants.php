<?php
date_default_timezone_set('UTC');
//if we're still in development, set this to true
define('DEVELOPMENT',TRUE);
/*
* if the environment is a localhost installation, set this to TRUE
* if the environment is a public development or staging server, set this to FALSE
*/
define('LOCALHOST',TRUE);

if(DEVELOPMENT){
  if(LOCALHOST){
    //Database credentials
    define('DB_HOST', '' );
    define('DB_NAME', 'mailerlite' );
    define('DB_USER', 'mailerlite' );
    define('DB_PASS', 'MailerLite' );

    //vhosts specified address, or local address
    define('SERVERADDRESS', 'http://mailerlitedev.com/' );

    //local path
    define('SERVERPATH', 'C:\wamp\www\developertest' );

    //API endpoint
    define('APIENDPATH', 'http://mailerlitedev.com/API/' );
  }
  else{
    //Database credentials
    define('DB_HOST', '' );
    define('DB_NAME', 'mailerlite' );
    define('DB_USER', 'mailerlite' );
    define('DB_PASS', 'MailerLite' );

    //vhosts specified address, or local address
    define('SERVERADDRESS', 'http://mailerlitedev.com/' );

    //local path
    define('SERVERPATH', 'C:\wamp\www\developertest' );

    //API endpoint
    define('APIENDPATH', 'http://mailerlitedev.com/API' );
  }
}
else{
//production
  //Database credentials
  define('DB_HOST', '' );
  define('DB_NAME', 'mailerlite' );
  define('DB_USER', 'mailerlite' );
  define('DB_PASS', 'MailerLite' );

  //vhosts specified address, or local address
  define('SERVERADDRESS', 'http://mailerlitedev.com/' );

  //local path
  define('SERVERPATH', 'C:\wamp\www\developertest' );

  //API endpoint
  define('APIENDPATH', 'http://mailerlitedev.com/API' );
}

//site details
define('SITENAME', 'MailerLite Subscribers');
define('SUPPORT', 'treymelton@gmail.com');
define("ADMIN",'treymelton@gmail.com');

/* Debug
 * debug levels
 * 0 = off
 * 1 = write to log
 * 2 = write to log and email
 * 3 = email only
 */
define('DEBUG_LEVEL', 1);

//debugging on or off
define("DEBUG_ARG",1);

//salt encrypting. This constant is SACRED, and cannot be changed without losing the root of EVERYONES data
define("SALT",'678^&*%5$4');

//make fake client token and secret for demo
define('CLIENTTOKEN','a36eedaf320ed66c2614f24d9ee52aa0');
define('CLIENTSECRET','2213effba38505ad5ec5c2e8edf9b773');

//make client demo ID
define('CLIENTID',1);

?>