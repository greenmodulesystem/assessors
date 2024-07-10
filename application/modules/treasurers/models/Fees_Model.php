<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Fees_Model extends CI_Model
{


    function __construct()
    {
        parent::__construct();
    }

    public function asset_size_real()
    {
        $this->db->order_by('ID', 'asc');
        $query = $this->db->get('tbl_assessment_asset');
        return $query->result();
    }

    public function asset_size()
    {
        $this->db->select(
            'mp.*,' .
                'b.Description,'
        );
        $this->db->from('tbl_fees_mayor_permit_v2 mp');
        $this->db->join('tbl_business_line b', 'b.ID = mp.BusLine_ID', 'inner');
        $this->db->order_by('b.Description', 'asc');
        $query = $this->db->get()->result();
        return $query;
    }

    public function business_line($ID)
    {
        $this->db->where('Application_ID', $ID);
        $this->db->order_by('ID', 'desc');
        $query = $this->db->get('tbl_application_form_business_line');
        return $query->result();
    }

    public function fixed_fees()
    {
        $this->db->order_by('ID', 'asc');
        $query = $this->db->get('tbl_fees_fixed');
        return $query->result();
    }

    public function sanitary_fees()
    {
        $this->db->order_by('Category', 'asc');
        $query = $this->db->get('tbl_fees_sanitary');
        return $query->result();
    }

    public function solid_fees()
    {
        $this->db->order_by('Waste_Fee', 'asc');
        $query = $this->db->get('tbl_fees_garbage_collection');
        return $query->result();
    }

    public function payment_mode()
    {
        $this->db->order_by('ID', 'asc');
        $query = $this->db->get('tbl_payment_mode');
        return $query->result();
    }
}
