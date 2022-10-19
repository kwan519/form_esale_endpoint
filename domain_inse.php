<?php
include_once 'c_db_con/a_db_config.php';
include_once 'c_db_con/c_db_config.php';
$message = json_decode(file_get_contents('php://input'));
$domain_list = $getDomains->getDb();
$storeDomains->storeRecords($domain_list);