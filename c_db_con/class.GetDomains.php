<?php

class getDomains
{
    private $db;

    function __construct($DB_con)
    {
        $this->db = $DB_con;
    }

    public function getDb()
    {
        $stmt = $this->db->prepare("SELECT website, campaign_id FROM tbl_customers");
        $stmt->execute();
        while($row = $stmt->fetchAll(PDO::FETCH_ASSOC))
        {
            return $row;
        }
    }

    public function getUsername($campaign_id)
    {
        $stmt = $this->db->prepare("SELECT username FROM tbl_customers WHERE campaign_id = :campaign_id");
        $stmt->bindparam(':campaign_id', $campaign_id);
        $stmt->execute();
        while($row = $stmt->fetch(PDO::FETCH_ASSOC))
        {
            return $row;
        }
    }

}

























