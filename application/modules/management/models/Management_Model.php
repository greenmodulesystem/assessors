<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Management_Model extends CI_Model
{

    public $Application_ID;

    function __construct()
    {
        parent::__construct();
        // $model_list = [ 
        //     'treasurers/Fees_Model' => 'MFees',
        //     'treasurers/Profiles_Model' => 'MProfiles',
        // ];
        // $this->load->model($model_list);

        // $libray_list = [
        //     "Logs_library" => 'logs'
        // ];
        // $this->load->library($libray_list);
    }

  
    public function history_details($ID)
    {
        $this->db->select(
            'd.*,' .
                's.Category,' .
                's.Sanitary_fee,' .
                'w.Size,' .
                'w.Waste_fee'
        );
        $this->db->from('tbl_assessment_details d');
        $this->db->join('tbl_fees_sanitary s', 's.ID = d.Category_ID', 'left');
        $this->db->join('tbl_cenro_line c', 'c.Cycle_ID = ' . $ID, 'left');
        $this->db->join('tbl_fees_solid_waste w', 'w.ID = c.Solid_waste_ID', 'left');
        $this->db->where('d.Cycle_ID', $ID);
        $query = $this->db->get();
        return $query->first_row();
    }

    public function get_mayors_permit_list()
    {
        $this->db->select(
            'mp.*, b.Description'
        );
        $this->db->order_by('ID', 'asc');
        $this->db->from('tbl_fees_mayor_permit_v2 mp');
        $this->db->join('tbl_business_line b', 'b.ID=mp.BusLine_ID', 'left');
        $result = $this->db->get()->result();
        return $result;
    }

    public function get_business_line()
    {
        $this->db->order_by('Description', 'asc');
        $this->db->where('ParentID!=', 0);
        $query = $this->db->get('tbl_business_line');
        $result = $query->result();
        return $result;
    }

    public function update_assessment($data, $ID, $Ass_ID)
    {
        $table = "tbl_assessment";
        $this->db->where('ID', $Ass_ID);
        $this->db->update($table, $data);

        $module = "Assessment - ASSESSED";
        $action = "Update";
        $cyc = $this->assessment_cycle($ID);
        $this->update_logs($module, $table, $action, $cyc->Cycle_ID);
    }

    public function update_details($data, $AppID, $ID)
    {
        $table = "tbl_assessment_details";
        $cycle = $this->getcycleID($AppID);
        $data['Cycle_ID'] = $cycle->ID;
        $this->db->where('ID', $ID);
        $this->db->update($table, $data);

        $module = "Assessment - DETAILS";
        $action = "Update";
        $this->update_logs($module, $table, $action, $cycle->ID);
    }

    public function update_fees($array, $ID)
    {
        $table = "tbl_assessment_fees";
        $Fee_names = $array['Fee_name'];
        $Fee = $array['Fee'];
        $data = [];
        foreach ($Fee_names as $key => $Fee_name) {
            array_push(
                $data,
                array(
                    "Fee_name" => $Fee_name,
                    "Fee" => $Fee[$key]
                )
            );
        }
        $this->db->where('Assessment_ID', $ID);
        $this->db->update_batch($table, $data, 'Fee_name');

        $module = "Assessment - ASSESSED";
        $action = "Update";
        $cyc = $this->assessment_cycle($ID);
        $this->update_logs($module, $table, $action, $cyc->Cycle_ID);
    }

    public function update_lines($array, $App_ID)
    {
        $cycle = $this->getcycleID($App_ID);
        $table = "tbl_application_business_line";
        $IDs = $array['Business_line_ID'];
        $Capitalization = $array['Capitalization'];
        $Essential = $array['Essential'];
        $NonEssential = $array['NonEssential'];
        $Assessment_asset_ID = $array['Assessment_asset_ID'];
        $Exempted = $array['Exempted'];
        $data = [];
        foreach ($IDs as $key => $ID) {
            array_push(
                $data,
                array(
                    "ID" => $ID,
                    "Capitalization" => $Capitalization[$key],
                    "Essential" => ($Essential[$key] == '') ? null : $Essential[$key],
                    "NonEssential" => ($NonEssential[$key] == '') ? null : $NonEssential[$key],
                    "Assessment_asset_ID" => $Assessment_asset_ID[$key],
                    "Exempted" => $Exempted[$key],
                )
            );
        }
        $this->db->where('Cycle_ID', $cycle->ID);
        $this->db->update_batch($table, $data, 'ID');

        $module = "Business Lines";
        $action = "Update";
        $this->update_logs($module, $table, $action, $cycle->ID);
    }

    public function update_ready($ID)
    {
        $this->db->select('Cycle_ID');
        $this->db->from('tbl_assessment');
        $this->db->where('ID', $ID);
        $query = $this->db->get()->first_row();

        $this->db->set('Status', "Done");
        $this->db->where('Cycle_ID', $query->Cycle_ID);
        $this->db->update('tbl_ready_to_assess');

        $module = "Ready to Assess - DONE";
        $table = "tbl_ready_to_assess";
        $action = "Update";
        $this->update_logs($module, $table, $action, $query->Cycle_ID);
    }

    public function assessment_collection_status()
    {
        $status = [
            'ready' => false,
            'assess' => false,
            'payed' => false,
        ];
        $this->db->from('tbl_ready_to_assess r');
        $this->db->join('tbl_cycle c', 'c.ID=r.Cycle_ID', 'left');
        $this->db->join('tbl_application_form a', 'a.ID=c.Application_ID', 'left');
        $this->db->where('a.ID', $this->Application_ID);
        $ready_to_assess_query = $this->db->get()->first_row();

        if ($ready_to_assess_query != null) {
            $status['ready'] = true;
        }

        $this->db->from('tbl_assessment as');
        $this->db->join('tbl_cycle c', 'c.ID=as.Cycle_ID', 'left');
        $this->db->join('tbl_application_form a', 'a.ID=c.Application_ID', 'left');
        $this->db->order_by('as.ID', 'desc');
        $this->db->where('as.Status', 'Approved');
        $this->db->where('a.ID', $this->Application_ID);
        $assess_query = $this->db->get()->first_row();

        if ($assess_query != null) {
            $status['assess'] = true;
        }

        $this->db->from('tbl_collection co');
        $this->db->join('tbl_cycle c', 'c.ID=co.Cycle_ID', 'left');
        $this->db->join('tbl_application_form a', 'a.ID=c.Application_ID', 'left');
        $this->db->order_by('co.ID', 'desc');
        $this->db->where('a.ID', $this->Application_ID);
        $payed_query = $this->db->get()->first_row();

        if ($payed_query != null) {
            $status['payed'] = true;
        }

        return  $status;
    }

    public function update_logs($module, $table, $action, $ID)
    {
        $this->logs->User_ID = $_SESSION['User_details']->ID;
        $this->logs->Last_name = $_SESSION['User_details']->Last_name;
        $this->logs->Module = $module;
        $this->logs->Table = $table;
        $this->logs->Action = $action;
        $this->logs->Application_ID = $this->MProfiles->get_App_ID($ID);
        $this->logs->record();
    }
}
