<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Due_date_Services_Model extends CI_Model
{


    function __construct()
    {
        parent::__construct();
    }

    public function save()
    { {

            $data = array(
                'Dd' => $this->dd,
                'Mm' => $this->mm,
            );

            $this->db->trans_start();
            $this->db->where('ID', $this->ID);
            $this->db->update('tbl_due_date', $data);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('has_error' => true);
            } else {
                $this->db->trans_commit();
                return array('message' => SAVED_SUCCESSFUL, 'has_error' => false);
            }
        }
    }
}
