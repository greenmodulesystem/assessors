<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tax_table_Services_Model extends CI_Model
{


    function __construct()
    {
        parent::__construct();
    }

    public function save()
    {
        $tax = $this->tax;
        $gross_from = $this->gross_from;
        $gross_to = $this->gross_to;
        $ID = $this->ID;

        $data = array();

        foreach ($tax as $k => $val) {
            array_push(
                $data,
                array(
                    'ID' => $ID[$k],
                    'tax' => $tax[$k],
                    'gross_from' => $gross_from[$k],
                    'gross_to' => $gross_to[$k]
                )
            );
        }

        $table = '';
        switch ($this->tbl) {
            case 'manufacturers':
                $table = 'tbl_tax_manufacturer';
                break;
            case 'dealers':
                $table = 'tbl_tax_dealer';
                break;
            case 'services':
                $table = 'tbl_tax_service';
                break;
        }
        $this->db->update_batch($table, $data, 'ID');
        return true;
    }

    public function save_retail()
    {
        $data = array(
            'tax' => $this->tax,
            'tax_excess' => $this->tax_e,
            'gross_less' => $this->gl,
            'gross_more' => $this->gm
        );
        $table = "tbl_tax_retailer";
        if ($this->ID != null) {
            $this->db->where('ID', $this->ID);
            $this->db->update($table, $data);
        } else {
            $this->db->insert($table, $data);
        }
    }

    public function save_other()
    {
        $data = array(
            'percent1' => $this->p1,
            'percent2' => $this->p2,
        );
        $table = "tbl_tax_other";
        $this->db->where('ID', $this->ID);
        $this->db->update($table, $data);
        return true;
    }

    public function save_fixed()
    {
        $data = array(
            'Fee' => $this->fee,
        );
        $table = "tbl_tax_fixed";
        $this->db->where('ID', $this->ID);
        $this->db->update($table, $data);
        return true;
    }

    public function save_fixed_new()
    {
        $data = array(
            'Fee' => $this->fee,
            'Description' => $this->description,
            'Category' => $this->category,
        );
        $table = "tbl_tax_fixed";
        $this->db->insert($table, $data);
        return true;
    }
}
