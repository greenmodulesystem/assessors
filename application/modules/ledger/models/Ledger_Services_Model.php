<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ledger_Services_Model extends CI_Model
{


    function __construct()
    {
        parent::__construct();
    }

    public function save()
    {
        $data = array('BusLine_ID' => $this->bID, 'Rate' => $this->rate);
        $table = "tbl_fees_mayor_permit_v2";
        $this->db->insert($table, $data);
    }
    public function get_mayors_permit_list()
    {
        $this->db->select(
            'mp.*, b.Description'
        );
        $this->db->order_by('ID', 'asc');
        $this->db->from('tbl_fees_mayor_permit_v2 mp');
        if ($this->search) {
            $this->db->group_start();
            $this->db->like('b.Description', $this->search);
            $this->db->group_end();
        }
        $this->db->join('tbl_business_line b', 'b.ID=mp.BusLine_ID', 'left');
        $result = $this->db->get()->result();
        return $result;
    }
    public function get_applicants()
    {
        $this->db->select(
            '*'
        );
        $this->db->from('tbl_application_form');
        // $this->db->join('tbl_fees_solid_waste w', 'w.ID = c.Solid_waste_ID', 'left');
        $this->db->where('Cancelled', 0);
        $this->db->where('Retired', 0);
        if ($this->search) {
            $this->db->group_start();
            // $this->db->like('Last_name', $this->search);
            // $this->db->or_like('First_name', $this->search);
            // $this->db->or_like('Middle_name', $this->search);
            $this->db->or_like('Tax_payer', $this->search);
            $this->db->group_end();
        }
        $query = $this->db->get();
        return $query->result();
        // var_dump($query->result());
    }
}
