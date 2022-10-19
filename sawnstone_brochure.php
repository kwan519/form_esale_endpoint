<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require __DIR__ . '/vendor/autoload.php';

include_once 'c_db_con/a_db_config.php';

$message = json_decode(file_get_contents('php://input'));

if($message == '') {
    die();
}

$out = $message;

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


if($out != '') {
    if($out->origin_url != '' && $out->cid != '') {
        if($storeDomains->checkAgainstDB($out->origin_url, $out->cid) == 1) {

            $get_last_row_id_did_it_store = $storeDomains->insertDataToDB($out->fran_label,$out->yourname,$out->telephonenumber,$out->emailinput,$out->postcode,$out->textareahere,$out->ip, $out->cid,$out->Location,$out->site_id,$out->lang_id,$out->order_number);

            if($get_last_row_id_did_it_store != false) {
                echo "1";
            } else {
                echo "2";
            }

            try {




//                $mail->addAttachment("https://sawnstoneltd.co.uk/".$file_name);

                $mail = new PHPMailer();

                $mail->isSMTP();
                $mail->setFrom('donotreply@esaleshub.co', 'eSalesHub Do Not Reply');
                $mail->addAddress('devteam@esaleshub.co.uk', 'eSaleshub Dev Team');
                $mail->addAddress('andyw@esaleshub.co.uk', 'Andy');
                $mail->addAddress('sales@sawnstoneltd.co.uk', 'Sales');
                $mail->addAddress($out->emailinput, $out->yourname);

                $mail->Username = 'AKIA2JNEHEV57INXKZSR';
                $mail->Password = 'BAIjGCtA0YMPMopH5m3mAn62HwVNpabEvp5azFOg/ADC';
                $mail->addCustomHeader('ses-smtp-forms.esaleshub');
                $mail->Host = 'email-smtp.eu-west-1.amazonaws.com';



                $mail->Subject = 'Sawn Stone LTD - Samples Brochure ';


                $mail->Body = <<<HTML
Hi,<br />
Attached to this email is the samples brochure for Granite, Quartz and LG-Himacs. If you have any problems opening the PDF or if it is not attached correctly, please contact us on 0800 048 5904.
<br />
<br />
HTML;

                $mail->SMTPAuth = true;
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;
                $mail->isHTML(true);
                //$mail->addAttachment("https://sawnstoneltd.co.uk/images/ktichen-worktop-shop-logo.png");

                //$url = "https://sawnstoneltd.co.uk/images/samplesbrochure.pdf";
                $filename = "samplesbrochure.pdf";

//                $mail->addStringAttachment(file_get_contents($url), $filename);

//                $mail->addAttachment("/uploads/samplesbrochure.pdf");

                $mail->AddAttachment($_SERVER["DOCUMENT_ROOT"] . '/uploads/samplesbrochure.pdf', 'samplesbrochure.pdf');


                $mail->send();


            }catch (Exception $e){

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




