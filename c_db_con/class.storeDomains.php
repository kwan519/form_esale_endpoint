<?php

class storeDomains
{
    private $ndb;

    function __construct($n_DB_con)
    {
        $this->ndb = $n_DB_con;
    }

    public function storeRecords($domain_list)
    {
        foreach($domain_list as $domain) {
            try {
                $stmt_insert = $this->ndb->prepare("INSERT INTO tbl_web_sites(website, campaign_id) VALUES(:website, :campaign_id) ON DUPLICATE KEY UPDATE website=VALUES(website)");
                $stmt_insert->bindparam(":website", $domain['website']);
                $stmt_insert->bindparam(":campaign_id", $domain['campaign_id']);
                $stmt_insert->execute();
            } catch (PDOException $e) {

                return false;
            }
        }
    }

    public function checkAgainstDB($website, $campaign_id)
    {
        $stmt = $this->ndb->prepare("SELECT * FROM tbl_web_sites WHERE website = :website AND campaign_id = :campaign_id");
        $stmt->BindParam(':website', $website);
        $stmt->BindParam(':campaign_id', $campaign_id);
        $stmt->execute();
        if($stmt->rowCount()>0) {
            return "1";
        } else {
            return "2";
        }
    }

    public function insertDataToDB($fran_label,$yourname, $telephone, $email, $postcode, $textarea, $email_ip, $campaign_id, $location, $site_id, $lang_id, $order_number, $image_urls) {
        $to_encode = json_encode($textarea);

        if($fran_label == '') {
            $fran_label = '';
        }

        try {
            $stmt_insert = $this->ndb->prepare("INSERT INTO tbl_email_inser_da(fran_label,yourname,telephone,emailaddress,postcode,textarea,email_ip,campaign_id,location, site_id, lang_id, order_number, image_urls) VALUES(:fran_label,:yourname,:telephone,:emailaddress,:postcode,:textarea, :email_ip, :campaign_id, :location, :site_id, :lang_id, :order_number, :image_urls)
");

            $stmt_insert->bindparam(":fran_label", $fran_label);
            $stmt_insert->bindparam(":yourname", $yourname);
            $stmt_insert->bindparam(":telephone", $telephone);
            $stmt_insert->bindparam(":emailaddress", $email);
            $stmt_insert->bindparam(":postcode", $postcode);
            $stmt_insert->bindparam(":textarea", $to_encode);
            $stmt_insert->bindparam(":email_ip", $email_ip);
            $stmt_insert->bindparam(":campaign_id", $campaign_id);
            $stmt_insert->bindparam(":location", $location);
            $stmt_insert->bindparam(":site_id", $site_id);
            $stmt_insert->bindparam(":lang_id", $lang_id);
            $stmt_insert->bindparam(":order_number", $order_number);
            $stmt_insert->bindparam(":image_urls", $image_urls);
            $stmt_insert->execute();
            $id = $this->ndb->lastInsertId();
            return $id;
        } catch (PDOException $e) {
//            echo $e->getMessage();                                                                               
            return false;
        }
    }

    public function sendemailToClient($mail, $to, $date_created, $yourname, $telephone, $email_address, $postcode, $textarea, $location, $username,$campaign_id,$row_id)
    {

//        var_dump($to);
//        var_dump($mail);

        $mail->isSMTP();
        $mail->setFrom('donotreply@esaleshub.co', 'eSalesHub Lead Do Not Reply');
        $mail->addAddress('devteam@esaleshub.co.uk', 'eSaleshub Dev Team');

        foreach($to as $k) {
            $mail->addAddress($k);
        }

//        $mail->Username = 'AKIAIVCK66UQ3EHNL67A';
//        $mail->Password = 'AkixGbVBdPwRi38eLSTiirNxMA3kCJgALFGo9qiKtlfF';
//        $mail->Host = 'email-smtp.eu-west-1.amazonaws.com';

        $mail->Username = 'AKIA2JNEHEV57INXKZSR';
        $mail->Password = 'BAIjGCtA0YMPMopH5m3mAn62HwVNpabEvp5azFOg/ADC';
        $mail->addCustomHeader('ses-smtp-forms.esaleshub');
        $mail->Host = 'email-smtp.eu-west-1.amazonaws.com';

        $mail->Subject = 'eSalesHub Lead - ' . $username;

        // 89406 === Connells
        if($campaign_id === '89406') {

            $meh = json_decode($textarea, true);

            $mail->Body = '
    Name ' . $yourname . ' <br/>
Email Address ' . $email_address . ' <br/>
Contact Number ' . $telephone . ' <br/>
Postcode ' . $postcode . ' <br/>
Visit Property ' . $meh['textareahere:'] . ' <br/>
Last Pages Visited ' . $location . ' <br/>
Account:  Connells  <br/>
        ';

            $mail->SMTPAuth = true;
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            $mail->isHTML(true);

        //  61576 === Sequence
        } else if($campaign_id === '61576') {

            $meh = json_decode($textarea, true);

            $mail->Body = '
    Name ' . $yourname . ' <br/>
Email Address ' . $email_address . ' <br/>
Contact Number ' . $telephone . ' <br/>
Postcode ' . $postcode . ' <br/>
Visit Property ' . $meh['textareahere:'] . ' <br/>
Last Pages Visited ' . $location . ' <br/>
Account:  Sequence  <br/>
        ';

            $mail->SMTPAuth = true;
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            $mail->isHTML(true);

            //  35470 === Securatis
        }  else if($campaign_id === '35470'){

//            $apiUrl = 'https://support.securatis-security-systems.co.uk/api/leads';
//            $apiName = 'esales';
//            $apiUser = 'esales';
//            $auth = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyIjoiZXNhbGVzIiwibmFtZSI6ImVzYWxlcyIsIkFQSV9USU1FIjoxNjUxMjQwMTMwfQ.Fw8l_1mU4Za8HpssaPdh5MS5VXLRMXQlS0TmxIgvPhY';
//
//            $leadData = array(
//                'source'        => 'eSales Hub',
//                'status'        => '2',
//                'name'          => "esaleshub lead - ".$username,
//                'contact'       => $yourname,
//                'email'         => $email_address,
//                'phonenumber'   => $telephone,
//                'address'       => $postcode,
//                'postcode'      => $postcode,
//                'description'   => $mainBody['textareahere'],
//                'assigned'      => '7'
//            );
//
//            $curl = curl_init();
//            curl_setopt( $curl, CURLOPT_URL, $apiUrl );
//            curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
//            curl_setopt( $curl, CURLOPT_POSTFIELDS, $leadData );
//            curl_setopt( $curl, CURLOPT_HTTPHEADER, array('authtoken: '.$auth));
//            $result = curl_exec( $curl );
//            curl_close( $curl );

            $mail->Body = '
        Name ' . $yourname . '  <br/>
        Telephone ' . $telephone  . '  <br/>
        Email address ' . $email_address   . '  <br/>
        Postcode ' . $postcode  . '  <br/>
        Location ' . $location  . '  <br/>
        Date Created ' . $date_created  . '  <br/>
        ';

            foreach(json_decode($textarea) as $k => $v) {
                $mail->Body .= "
                $k  $v <br/>
            ";
            }

            $mail->SMTPAuth = true;
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            $mail->isHTML(true);
            $mail->AltBody = "esaleshub lead - ' . $username . ' \r\n
        Name ' . $yourname . '  \r\n
        Telephone ' . $telephone  . '  \r\n
        Email address ' . $email_address   . '  \r\n
        Postcode ' . $postcode  . '  \r\n
        Location ' . $location  . '  \r\n
        Date Created ' . $date_created  . '  \r\n
        ";


        } else {
            $mail->Body = '
        Name ' . $yourname . '  <br/>
        Telephone ' . $telephone  . '  <br/>
        Email address ' . $email_address   . '  <br/>
        Postcode ' . $postcode  . '  <br/>
        Location ' . $location  . '  <br/>
        Date Created ' . $date_created  . '  <br/>
        ';

            foreach(json_decode($textarea) as $k => $v) {
                $mail->Body .= "
                $k  $v <br/>
            ";
            }

            $mail->SMTPAuth = true;
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            $mail->isHTML(true);
            $mail->AltBody = "esaleshub lead - ' . $username . ' \r\n
        Name ' . $yourname . '  \r\n
        Telephone ' . $telephone  . '  \r\n
        Email address ' . $email_address   . '  \r\n
        Postcode ' . $postcode  . '  \r\n
        Location ' . $location  . '  \r\n
        Date Created ' . $date_created  . '  \r\n
        ";
        }

//        echo "<pre>";
//        print_r($mail->Body);
//        echo "</pre>";


        if(!$mail->send()) {
            echo "Email not sent. " , $mail->ErrorInfo , PHP_EOL;
            $this->hasEmailBeenSent($row_id, $campaign_id, $did_it_get_sent = 0);
        } else {
            echo "Email sent!" , PHP_EOL;
            $this->hasEmailBeenSent($row_id, $campaign_id, $did_it_get_sent = 1);
        }

    }

    public function hasEmailBeenSent($row_id, $campaign_id, $did_it_get_sent)
    {

            try
            {
                $stmt_update = $this->ndb->prepare("UPDATE tbl_email_inser_da SET did_email_get_sent = :did_email_get_sent WHERE id=:id AND campaign_id = :campaign_id ");
                $stmt_update->bindparam(":did_email_get_sent",$did_it_get_sent);
                $stmt_update->bindparam(":id",$row_id);
                $stmt_update->bindparam(":campaign_id",$campaign_id);
                $stmt_update->execute();
                return true;
            }
            catch(PDOException $e)
            {
//                echo $e->getMessage();
                return false;
            }

    }

    /**
     * @return mixed
     */
    public function getRecordsThatHaventBeenSent()
    {
        $stmt = $this->ndb->prepare("SELECT * FROM  tbl_email_inser_da WHERE did_email_get_sent = 0");
        $stmt->execute();
        while($row = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
            return $row;
        }


    }

}

