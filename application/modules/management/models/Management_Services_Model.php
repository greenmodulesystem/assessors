<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Management_Services_Model extends CI_Model {

    
    function __construct()
    {
        parent::__construct();
    }

    public function save(){
        $data = array('BusLine_ID'=>$this->bID,'Rate'=>$this->rate);
        $table = "tbl_fees_mayor_permit_v2";
        $this->db->insert($table, $data);
    }
}