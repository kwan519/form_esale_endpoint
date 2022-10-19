<?php

//include_once 'class.dbconnection.php';

include_once 'class.storeDomains.php';
include_once 'class.storeS3Bucket.php';

$storeDomains = new storeDomains($n_DB_con);
$storeS3Bucket = new storeS3Bucket();