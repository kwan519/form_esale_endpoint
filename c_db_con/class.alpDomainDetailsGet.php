<?php

class getAlpDetails
{
    private $alp;

    function __construct($alp_DB_con)
    {
        $this->alp = $alp_DB_con;
    }

    public function getClientDetails($campaign_id,$location,$site_id,$lang_id,$order_number) {


        $site_token_id = '';

        $stmt_find = $this->alp->prepare("SELECT site_token_id FROM site_token_tbl WHERE site_id = :site_id AND site_token_match IN ('Email') AND deleted != 1");
        $stmt_find->bindparam(':site_id', $site_id);
        $stmt_find->execute();
        while($row_find = $stmt_find->fetch(PDO::FETCH_ASSOC)) {
            $site_token_id = $row_find['site_token_id'];
        }


        $stmt = $this->alp->prepare("SELECT * FROM site_data_import_tbl WHERE site_id = :site_id AND lang_id = :lang_id AND order_number = :order_number AND site_token_id = :site_token_id ");
        $stmt->bindparam(':site_id', $site_id);
        $stmt->bindparam(':lang_id', $lang_id);
        $stmt->bindparam(':order_number', $order_number);
        $stmt->bindparam(':site_token_id', $site_token_id);
        $stmt->execute();
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return $row;
        }



    }



}

