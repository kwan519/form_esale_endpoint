<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require __DIR__ . '/vendor/autoload.php';

include_once 'c_db_con/a_db_config.php';

$message = json_decode(file_get_contents('php://input'));

if($message == '') {
    die();
}
////
//$request = file_get_contents('php://input');
//file_put_contents('output.txt', $request.PHP_EOL , FILE_APPEND | LOCK_EX);


//$meh = '{"origin_url":"https:\/\/unblockdrains.today\/","ip":"94.8.29.155","cid":"27279","fran_label":"Yasser","fran_email":"-","url_end_point":"https:\/\/form.esales-hub.com\/e_endpoint.php","Location":"Alcester","Service":"servicepagetwo","site_id":"32","lang_id":"1","order_number":"2","yourname":"dev dev","telephonenumber":"01302639561","emailinput":"devteam@esaleshub.co.uk","postcode":"DN1 1AF","textareahere":"dev","g-recaptcha-response":"","subject":null,"from_name":"","from_email":"","cc":"","message":null}';

$out = $message;

//$out = json_decode($meh);

//var_dump($out);


function _e($string) {
    return strip_tags($string);
}

unset($out->url_end_point);
unset($out->subject);
unset($out->from_name);
unset($out->from_email);
//unset($out->fran_label);
unset($out->fran_email);
unset($out->cc);
unset($out->{'g-recaptcha-response'});
unset($out->message);
unset($out->verify);

$to_store_together = (array)$out;
unset($to_store_together['website']);
unset($to_store_together['origin_url']);
unset($to_store_together['ip']);
unset($to_store_together['fran_label']);
unset($to_store_together['cid']);
unset($to_store_together['yourname']);
unset($to_store_together['telephonenumber']);
unset($to_store_together['emailinput']);
unset($to_store_together['postcode']);
unset($to_store_together['Service']);
unset($to_store_together['site_id']);
unset($to_store_together['lang_id']);
unset($to_store_together['order_number']);
unset($to_store_together['Location']);
unset($to_store_together['recaptcha_response']);

$final_to_store = array();

foreach($to_store_together as $k => $v) {
    $field_lable = str_replace("_", " ", $k) . ":";
    $field_lable = _e($field_lable);
    $final_to_store[$field_lable] = _e($v);
}


$out->textareahere = $final_to_store;

$out->yourname = _e($out->yourname);
$out->telephonenumber = _e($out->telephonenumber);
$out->emailinput = _e($out->emailinput);
$out->postcode = _e($out->postcode);
$out->ip = _e($out->ip);
if(empty($out->ip)) {
    $out->ip = "0.0";
}
$out->cid = _e($out->cid);

$out->Location = _e($out->Location);
$out->site_id = _e($out->site_id);
$out->lang_id = _e($out->lang_id);
$out->order_number = _e($out->order_number);
$out->fran_label = _e($out->fran_label);

if($out->site_id == 130){
    
    $url = 'https://webleads.abinitiosoftware.co.uk/api/LeadDetails';

    $custID = "7014126";
    $pw = "boZl14isTaw4";

    $ip = $out->ip;
    $cid = $out->cid;
    $location = $our->Location;
    $order_number = $out->order_number;
    $fran_label = $out->fran_label;
    $postcode = $out->postcode;
    $emailinput = $out->emailinput;
    $yourname = $out->yourname;
    $telephonenumber = $out->telephonenumber;
    $textarea = $out->textareahere;



    $data = array(
        'AB_CUSTID' => $custID, 
        'AB_PWORD' => $pw, 
        'IP' => $ip,
        'CID' => $cid,
        'LOCATION' => $location,
        'ORDER_NUMBER' => $order_number,
        'FRAN_LABEL' => $fran_label,
        'POSTCODE' => $postcode,
        'EMAILINPUT' => $emailinput,
        'YOURNAME' => $yourname,
        'TELEPHONENUMBER' => $telephonenumber,
        'TEXTAREA' => $textarea
        
    );

    // use key 'http' even if you send the request to https://...
    $options = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data)
        )
    );
    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    if ($result === FALSE) { 
        echo "ERROR";
        var_dump($result);
    }

    


}


if($out != '') {
    if($out->origin_url != '' && $out->cid != '') {
        if($storeDomains->checkAgainstDB($out->origin_url, $out->cid) == 1) {

            // Step1 : Save images to S3 Bucket
            $saveImages = $storeS3Bucket->uploadImages($out->images, $out->site_id);

            // Step2 : Add Data to Database localhost
            // $get_last_row_id_did_it_store == false :: store data to database not success
            $get_last_row_id_did_it_store = $storeDomains->insertDataToDB($out->fran_label,$out->yourname,$out->telephonenumber,$out->emailinput,$out->postcode,$out->textareahere,$out->ip, $out->cid,$out->Location,$out->site_id,$out->lang_id,$out->order_number);
            

            // Step3 : Send out email
            if(in_array($out->cid, [82769, 46038])){

                switch($out->typeoflead){
                    case "Boiler Installs":
                        $out->typeoflead = "Boiler Installation lead";
                    break;
                    case "repairs":
                        $out->typeoflead = "Boiler repair lead";
                    break;
                    case "servicing":
                        $out->typeoflead = "Boiler Servicing lead";
                    break;
                }

                $data = [
                    "email" => $out->emailinput,
                    "name" => $out->yourname,
                    "page_name" => "Shnugg",
                    "page_url" => "https://local.shnugg.co.uk/",
                    "phone_number" => $out->telephonenumber,
                    "source" => "esaleshub",
                    "platform" => "esaleshub",
                    "type_of_lead" => $out->typeoflead
                ];


                try {

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, "https://booking.shnugg.co.uk/prospect-webhook");
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                    $output = curl_exec($ch);
                    if ($output === false) {
                        $error = curl_error($ch);
                        throw new Exception($error);
                    }
                    curl_close($ch);

                    $mail = new PHPMailer(true);

                    $mail->isSMTP();
                    $mail->setFrom('donotreply@esaleshub.co', 'eSalesHub Do Not Reply');
                    $mail->addAddress('devteam@esaleshub.co.uk', 'eSaleshub Dev Team');
                    $mail->addAddress('adamc@esaleshub.co.uk', 'Adam Creighton');

                    $mail->Username = 'AKIA2JNEHEV5UEDE5IHD';
                    $mail->Password = 'BJRIjCb6UohK/iF4/e9S+Pw/Us4QMSIwxtFnCwbNHAW3';
                    $mail->addCustomHeader('esales_hub_email_iam');
                    $mail->Host = 'email-smtp.eu-west-1.amazonaws.com';
                    $mail->Subject = 'Shnugg Integration Success';


                    $mail->Body = <<<HTML
There was a prospect sent to the Shnugg API.<br />
<br />
Return Message: $output
<br/>
Data Sent: $data
HTML;

                    $mail->SMTPAuth = true;
                    $mail->SMTPSecure = 'tls';
                    $mail->Port = 587;
                    $mail->isHTML(true);
                    $mail->AltBody = <<<HTML
There was a prospect sent to the Shnugg API.

Return Message: $output

Data Sent: $data
HTML;

                    $mail->send();

                }catch (Exception $e){

                    $mail = new PHPMailer(true);

                    $mail->isSMTP();
                    $mail->setFrom('donotreply@esaleshub.co', 'eSalesHub Do Not Reply');
                    $mail->addAddress('devteam@esaleshub.co.uk', 'eSaleshub Dev Team');
                    $mail->addAddress('adamc@esaleshub.co.uk', 'Adam Creighton');

                    $mail->Username = 'AKIA2JNEHEV5UEDE5IHD';
                    $mail->Password = 'BJRIjCb6UohK/iF4/e9S+Pw/Us4QMSIwxtFnCwbNHAW3';
                    $mail->addCustomHeader('esales_hub_email_iam');
                    $mail->Host = 'email-smtp.eu-west-1.amazonaws.com';
                    $mail->Subject = 'Shnugg Integration Error';


                    $mail->Body = <<<HTML
There was an error sending a prospect to the Shnugg API.<br />
<br />
Error: $output
<br/>
Data Sent: $data
HTML;

                    $mail->SMTPAuth = true;
                    $mail->SMTPSecure = 'tls';
                    $mail->Port = 587;
                    $mail->isHTML(true);
                    $mail->AltBody = <<<HTML
There was an error sending a prospect to the Shnugg API.

Error: $output

Data Sent: $data
HTML;

                    $mail->send();

                }

            }

        } else {
            echo "2";
            die();
        }
    } else {
        echo "2";
        die();
    }

}




