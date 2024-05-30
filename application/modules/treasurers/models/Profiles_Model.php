<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Profiles_Model extends CI_Model
{


    function __construct()
    {
        parent::__construct();
        $libray_list = [
            "Logs_library" => 'logs'
        ];
        $this->load->library($libray_list);
    }

    //added function
    public function get_retirement_data($ID)
    {
        $this->db->where('ApplicationID', $ID);
        $this->db->where('DateUpdated', null);
        $this->db->order_by('ID', 'desc');
        $result = $this->db->get('tbl_retirement')->first_row();
        return $result;
    }

    public function get_App_ID($ID)
    {
        $this->db->where('ID', $ID);
        $result = $this->db->get('tbl_cycle')->first_row();
        return $result->Application_ID;
    }

    public function getcycleDate($ID)
    {
        $this->db->select('tbl_cycle.Cycle_date');
        $this->db->order_by('ID', 'desc');
        $query = $this->db->get_where('tbl_cycle', array('Application_ID' => $ID))->first_row();
        return $query;
    }

    public function getcycleYear($ID)
    {
        $this->db->select('tbl_cycle.Cycle_date');
        $query = $this->db->get_where('tbl_cycle', array('ID' => $ID))->first_row();
        return $query;
    }

    private function getcycleID($ID)
    {
        $cycle = $this->getcycleDate($ID);
        $this->db->select('ID');
        $this->db->from('tbl_cycle');
        $this->db->where('Cycle_date', $cycle->Cycle_date);
        $this->db->where('Application_ID', $ID);
        $this->db->order_by('ID', 'desc');
        $query = $this->db->get()->first_row();
        return $query;
    }

    public function get_profile($ID)
    {
        $this->db->select(
            'a.ID,' .
                'a.Last_name,' .
                'a.First_name,' .
                'a.Middle_name,' .
                'a.Tax_payer,' .
                'a.Business_name,' .
                'a.Business_telephone_number AS Tel_num,' .
                'a.Business_mobile_number AS Mob_num,' .
                's.Status,' .
                'a.Total_number_employees,' .
                'a.Building_name,' .
                'a.Street,' .
                'a.Trade_name_franchise AS Tradename,' .
                'p.Purok,' .
                'b.Barangay,' .
                'c.Cycle_date,' .
                'a.Payment_mode_ID,' .
                'm.Mode,' .
                'r.Date_accepted,' .
                'c.ID AS Cycle_ID,' .
                'c.Date_application,' .
                'z.Capitalization AS Capitalization'
        );
        $this->db->from('tbl_application_form a');
        $this->db->join('tbl_barangay b', 'b.ID = a.Barangay_ID', 'left');
        $this->db->join('tbl_purok p', 'p.ID = a.Purok_ID', 'left');
        $this->db->join('tbl_application_status s', 's.ID = a.Application_status_ID', 'left');
        $this->db->join('tbl_cycle c', 'c.Application_ID = a.ID', 'left');
        $this->db->join('tbl_payment_mode m', 'm.ID = a.Payment_mode_ID', 'left');
        $this->db->join('tbl_ready_to_assess r', 'r.Cycle_ID = c.ID', 'left');
        $this->db->join('tbl_application_business_line z', 'z.Application_ID = a.ID', 'left');
        $this->db->where('a.ID', $ID);
        $this->db->order_by('c.Cycle_date', 'desc');
        $query = $this->db->get()->first_row();

        $rate = $this->db->get_where('tbl_city_engineer', array('Cycle_ID' => $query->Cycle_ID));
        if ($rate->num_rows() < 1) {
            $query->Rate = null;
        } else {
            $query->Rate = 1;
        }

        $cenro = $this->db->get_where('tbl_cenro_line', array('Cycle_ID' => $query->Cycle_ID));
        if ($cenro->num_rows() < 1) {
            $query->Cenro = null;
        } else {
            $query->Cenro = $cenro->first_row()->Solid_waste_ID;
        }

        $ready = $this->db->get_where('tbl_ready_to_assess', array('Cycle_ID' => $query->Cycle_ID));
        if ($ready->num_rows() < 1) {
            $query->Ready = null;
        } else {
            $query->Ready = 1;
        }

        $cycle = $this->getcycleDate($query->ID);
        $this->db->where('Application_ID', $query->ID);
        $this->db->where('Cycle_date', $cycle->Cycle_date);
        $count = $this->db->get('tbl_cycle');
        if ($count->num_rows() > 1) {
            $query->Line_status = 'ADD LINE';
        } else {
            $query->Line_status = null;
        }

        return $query;
    }

    function search($search = '')
    {
        $this->db->select(
            'o.Opened,' .
                'o.Opened_by,' .
                'a.ID,' .
                'a.Last_name,' .
                'a.First_name,' .
                'a.Middle_name,' .
                'a.Tax_payer,' .
                'a.Trade_name_franchise AS Tradename,' .
                'a.Business_name,' .
                'MAX(c.Date_application) AS Date_application'
        );
        $this->db->from('tbl_cycle c');
        $this->db->join('tbl_application_form a', 'a.ID = c.Application_ID', 'left');
        $this->db->join('tbl_open_applicant o', 'o.Application_ID = a.ID', 'left');
        $this->db->like('a.Business_name', $search);
        $this->db->or_like('a.First_name', $search);
        $this->db->or_like('a.Middle_name', $search);
        $this->db->or_like('a.Last_name', $search);
        $this->db->or_like('a.Tax_payer', $search);
        $this->db->order_by('a.Business_name', 'asc');
        $this->db->group_by('a.ID');
        $query = $this->db->get();
        return $query->result();
    }

    private function getRate($ID)
    {
        $this->db->select(
            'l.Rate'
        );
        $this->db->from('tbl_city_engineer e');
        $this->db->join('tbl_city_engineer_line l', 'l.City_engineer_ID = e.ID', 'left');
        $this->db->join('tbl_pay_type p', 'p.ID = l.Pay_type_ID', 'left');
        $this->db->where('e.Cycle_ID', $ID);
        $this->db->where('p.Type', 'Electrical Permit Fee');
        $query = $this->db->get();
        return $query->first_row();

        // if($query == null) {
        //     return $query;
        // } else {
        //     foreach($query as $key => $q){
        //         $result[$key] = null;
        //         $permit = $this->db->get_where('tbl_permit',array('Cycle_ID'=>$q->Cycle_ID));
        //         if($permit->num_rows() < 1) {
        //             $r = $this->getRate($q->Cycle_ID);
        //             $result[$key] = $query[$key];
        //             $result[$key]->Rate = ($r == null) ? null : $r->Rate;
        //         }
        //     }

        //     return $result;
        // }
    }

    public function update($Employees, $Payment_mode_ID, $ID)
    {
        $this->db->set('Total_number_employees', $Employees);
        $this->db->set('Payment_mode_ID', (int)$Payment_mode_ID);
        $this->db->where('ID', $ID);
        $this->db->update('tbl_application_form');

        $this->logs->User_ID = $_SESSION['User_details']->ID;
        $this->logs->Last_name = $_SESSION['User_details']->Last_name;
        $this->logs->Module = "Application - EMPLOYEES";
        $this->logs->Table = "tbl_application_form";
        $this->logs->Action = "Update";
        $this->logs->Application_ID = $ID;
        $this->logs->record();
    }

    // ----------------------------------- TEMP FUNCTION ---------------------------------- //
    public function update_cenro($Solid_waste_ID, $ID, $Date)
    {
        $cycle = $this->getcycleID($ID);
        $cenro = $this->db->get_where('tbl_cenro_line', array('Cycle_ID' => $cycle->ID));
        if ($cenro->num_rows() < 1) {
            $data['Cycle_ID'] = $cycle->ID;
            $data['Solid_waste_ID'] = $Solid_waste_ID;
            $data['Date_billed'] = $Date;
            $table = "tbl_cenro_line";
            $this->db->insert($table, $data);
        } else {
            $this->db->set('Solid_waste_ID', (int)$Solid_waste_ID);
            $this->db->set('Date_billed', $Date);
            $this->db->where('Cycle_ID', $cycle->ID);
            $this->db->update('tbl_cenro_line');
        }
    }
    // ----------------------------------- TEMP FUNCTION ---------------------------------- //

    public function open($ID, $data)
    {
        $this->db->where('Application_ID', $ID);
        $this->db->update('tbl_open_applicant', $data);
    }

    public function close($user, $data)
    {
        $this->db->where('Opened_by', $user);
        $this->db->or_where('Opened_by', '');
        $this->db->update('tbl_open_applicant', $data);
    }
}
