<?php
include_once 'c_db_con/c_db_config.php';
include_once 'c_db_con/a_db_config.php';
include_once 'c_db_con/alp_db_config.php';


require __DIR__ . '/vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


$out = $storeDomains->getRecordsThatHaventBeenSent();


if(empty($out)) {
    die();
}

foreach($out as $r) {

    $campaign_id = $r['campaign_id'];

    if($campaign_id != '') {

        $username = $getDomains->getUsername($campaign_id);

        if($username != null) {


            $username = $username['username'];


            $location = $r['location'];
            $site_id = $r['site_id'];
            $lang_id = $r['lang_id'];
            $order_number = $r['order_number'];



            if($campaign_id != '') {
                $get_email = $getAlpDetails->getClientDetails($campaign_id,$location,$site_id,$lang_id,$order_number);

                if(!empty($get_email)) {
//                var_dump($r);
//                var_dump($get_email);



                    $to = explode(', ', $get_email['value']);
                    $mail = new PHPMailer;


                    $storeDomains->sendemailToClient($mail, $to, $r['date_of_contact'], $r['yourname'], $r['telephone'], $r['emailaddress'], $r['postcode'], $r['textarea'], $r['location'], $username,$campaign_id, $r['id']);

                    echo "<hr/>";


                }

            } else {
                die();
            }



        }



    }





}






///////$storeDomains->sendemailToClient($mail, $get_last_row_id_did_it_store = 0, $out->website, $out->fran_label,$out->fran_email,$out->yourname,$out->telephonenumber,$out->emailinput,$out->postcode,$out->textareahere,$out->cid, $alert = 1);




