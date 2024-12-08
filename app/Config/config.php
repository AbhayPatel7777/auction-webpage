<?php
define("DB_HOST","localhost");
define("DB_USER","apatel10");
define("DB_PASSWORD", "yquubyquub");
define("DB_NAME", "apatel10auction");

define("CONFIG_ADMIN", "Abhaykumar Patel");
define("CONFIG_ADMINEMAIL", "W0848266@myscc.ca");
define("CONFIG_URL", "https://apatel10.scweb.ca/auction");
define("CONFIG_AUCTIONNAME", "Web Guys Online Auction");

define("CONFIG_CURRENCY", "$");

date_default_timezone_set("America/Toronto");

define("LOG_LOCATION", __DIR__ . "/../../logs/app.log");


define("FILE_UPLOADLOC", "imgs/");

define("CLIENT_ID","ASfP4QYcs_SxczpizM61-GEOfalwOr0kpPOKl4TaKV7ZA9FF6heCvaPz-8WnEn3D4qt5usf6CeGLpbLd");
define("CLIENT_SECRET","EKhiZ-OBO4SyGpvZzfEY1KVNpQWIoxUbWUvzDO2WUw_oauE0GLEZhIuoUDqpIVkE-emM1JGgnGjn-XSW");
define("WEBHOOK_ID","84V83396TT692334A");

define("PAYPAL_CURRENCY","CAD");
define("PAYPAL_RETURNURL",CONFIG_URL . "/payment-successful.php");
define("PAYPAL_CANCELURL", CONFIG_URL . "/payment-cancel.php");
?>