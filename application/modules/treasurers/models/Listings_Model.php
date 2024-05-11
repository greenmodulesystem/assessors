<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Listings_Model extends CI_Model {

    
    function __construct()
    {
        parent::__construct();
    }

    public function lines($brgy='',$class='',$from='',$to=''){
        $this->db->select(
            'p.Permit_no,'.
            's.Status,'.
            'a.ID,'.
            'a.Business_name,'.
            'a.Trade_name_franchise, '.
            'a.Street,'.
            'a.Tax_payer,'.
            'a.Owner_address,'.
            'b.Barangay,'.
            'bl.Business_line,'.
            'bl.Business_category,'.
            'bl.Capitalization,'.
            'bl.Essential,'.
            'bl.NonEssential'
        );
        $this->db->from('tbl_application_form a');
        $this->db->join('tbl_barangay b', 'b.ID = a.Barangay_ID', 'left');
        $this->db->join('tbl_application_status s', 's.ID = a.Application_status_ID', 'left');
        $this->db->join('tbl_cycle c', 'c.Application_ID = a.ID', 'left');
        $this->db->join('tbl_permit p', 'p.Cycle_ID = c.ID', 'left');
        $this->db->join('tbl_application_business_line bl', 'bl.Cycle_ID = c.ID', 'left');
        $this->db->where('p.Date_release !=', null);
        if($brgy != '') {
            $this->db->where('b.Barangay', $brgy);
        }
        if($class != ''){
            $this->db->where('bl.Business_category', $class);
        }
        if($from == '' && $to == ''){
            $this->db->where('c.Cycle_date', date('Y'));
        } else {
            $this->db->where('p.Date_release >=', date('Y-m-d', strtotime($from)));
            $this->db->where('p.Date_release <=', date('Y-m-d', strtotime($to)));
            $this->db->where('c.Cycle_date >=', date('Y', strtotime($from)));
            $this->db->where('c.Cycle_date <=', date('Y', strtotime($to)));
        }
        $this->db->order_by('a.Business_name', 'asc');
        $query = $this->db->get()->result();
        return $query;
    }

    public function owner($brgy='',$class='',$from='',$to=''){
        $this->db->select(
            'c.Date_application,'.
            'p.Permit_no,'.
            's.Status,'.
            'a.ID,'.
            'a.Business_name,'.
            'a.Trade_name_franchise AS Tradename,'.
            'a.Street,'.
            'b.Barangay'
        );
        $this->db->from('tbl_application_form a');
        $this->db->join('tbl_barangay b', 'b.ID = a.Barangay_ID', 'left');
        $this->db->join('tbl_application_status s', 's.ID = a.Application_status_ID', 'left');
        $this->db->join('tbl_cycle c', 'c.Application_ID = a.ID', 'left');
        $this->db->join('tbl_permit p', 'p.Cycle_ID = c.ID', 'left');
        $this->db->join('tbl_business_type t', 't.ID = a.Business_type_ID', 'left');
        $this->db->where('p.Permit_no !=', null);
        if($brgy != '') {
            $this->db->where('b.Barangay', $brgy);
        }
        if($class != ''){
            $this->db->where('t.Type', $class);
        }
        if($from == '' && $to == ''){
            $this->db->where('c.Cycle_date', date('Y'));
        } else {
            $this->db->where('DATE(c.Date_application) >=', date('Y-m-d', strtotime($from)));
            $this->db->where('DATE(c.Date_application) <=', date('Y-m-d', strtotime($to)));
        }
        $this->db->order_by('a.Business_name', 'asc');
        $result = $this->db->get()->result();
        
        foreach($result as $r) {
            $cycle = $this->getcycleID($r->ID);
            $this->db->select('Business_line');
            $this->db->where('Cycle_ID', $cycle->ID);
            $this->db->order_by('ID', 'asc');
            $query = $this->db->get('tbl_application_business_line');
            $r->Lines = $query->result();
        }

        return $result;
    }

    public function types_all($brgy='',$class='',$from='',$to=''){
        $this->db->select(
            'a.Business_line,'.
            'a.Business_category,'.
            'COUNT(a.Business_line) AS Count'
        );
        $this->db->from('tbl_application_business_line a');
        $this->db->join('tbl_permit p', 'p.Cycle_ID = a.Cycle_ID', 'left');
        $this->db->join('tbl_cycle c', 'c.ID = a.Cycle_ID', 'left');
        $this->db->where('p.Permit_no !=', null);
        if($class != ''){
            $this->db->where('c.Cycle_date', $class);
        } else if($from == '' && $to == ''){
            $this->db->where('c.Cycle_date', date('Y'));
        } else {
            $this->db->where('DATE(c.Date_application) >=', date('Y-m-d', strtotime($from)));
            $this->db->where('DATE(c.Date_application) <=', date('Y-m-d', strtotime($to)));
        }
        $this->db->group_by('a.Business_line');
        $this->db->order_by('a.Business_line','asc');
        $result = $this->db->get()->result();
        
        $wholesaler = [];
        $service = [];
        $manufacturer = [];
        $food = [];
        $amusement = [];
        $banks = [];
        $others = [];
        
        $w_count = 0;
        $s_count = 0;
        $m_count = 0;
        $f_count = 0;
        $a_count = 0;
        $b_count = 0;
        $o_count = 0;

        foreach($result as $r){
            if(trim(strtoupper($r->Business_category)) == 'WHOLESALER' ||
            trim(strtoupper($r->Business_category)) == 'RETAILER' ||
            trim(strtoupper($r->Business_category)) == 'DEALER') {
                $w_count+=$r->Count;
                array_push($wholesaler, $r);
            } else if(trim(strtoupper($r->Business_category)) == 'SERVICE'){
                $s_count+=$r->Count;
                array_push($service, $r);
            } else if(trim(strtoupper($r->Business_category)) == 'PRODUCER' ||
            trim(strtoupper($r->Business_category)) == 'MANUFACTURER'){
                $m_count+=$r->Count;
                array_push($manufacturer, $r);
            } else if(trim(strtoupper($r->Business_category)) == 'FOOD ESTABLISHMENT'){
                $f_count+=$r->Count;
                array_push($food, $r);
            } else if(trim(strtoupper($r->Business_category)) == 'AMUSEMENT'){
                $a_count+=$r->Count;
                array_push($amusement, $r);
            } else if(trim(strtoupper($r->Business_category)) == 'FINANCIAL'){
                $b_count+=$r->Count;
                array_push($banks, $r);
            } else if(trim(strtoupper($r->Business_category)) == 'OTHERS'){
                $o_count+=$r->Count;
                array_push($others, $r);
            } 
        }

        $wholesaler['Total_count'] = $w_count;
        $service['Total_count'] = $s_count;
        $manufacturer['Total_count'] = $m_count;
        $food['Total_count'] = $f_count;
        $amusement['Total_count'] = $a_count;
        $banks['Total_count'] = $b_count;
        $others['Total_count'] = $o_count;

        $data = new ArrayObject( 
            array(
                'I. WHOLESALERS/RETAILERS/DEALERS' => $wholesaler,
                'II. SERVICES' => $service,
                'III. MANUFACTURERS/PRODUCERS' => $manufacturer,
                'IV. FOOD ESTABLISHMENTS' => $food,
                'V. AMUSEMENT' => $amusement,
                'VI. BANKS & OTHER FINANCIAL INSTITUTIONS' => $banks,
                'VII. OTHERS' => $others,
            )
        );
        return $data;
    }

    public function types_summary($brgy='',$class='',$from='',$to=''){
        $this->db->select(
            'a.Business_category,'.
            'COUNT(a.Business_category) AS Count'
        );
        $this->db->from('tbl_application_business_line a');
        $this->db->join('tbl_permit p', 'p.Cycle_ID = a.Cycle_ID', 'left');
        $this->db->join('tbl_cycle c', 'c.ID = a.Cycle_ID', 'left');
        $this->db->where('p.Permit_no !=', null);
        if($class != ''){
            $this->db->where('c.Cycle_date', $class);
        } else if($from == '' && $to == ''){
            $this->db->where('c.Cycle_date', date('Y'));
        } else {
            $this->db->where('DATE(c.Date_application) >=', date('Y-m-d', strtotime($from)));
            $this->db->where('DATE(c.Date_application) <=', date('Y-m-d', strtotime($to)));
        }
        $this->db->group_by('a.Business_category');
        $this->db->order_by('a.Business_category','asc');
        $result = $this->db->get()->result();

        $w_count = 0;
        $s_count = 0;
        $m_count = 0;
        $f_count = 0;
        $a_count = 0;
        $b_count = 0;
        $o_count = 0;

        foreach($result as $r){
            if(trim(strtoupper($r->Business_category)) == 'WHOLESALER' ||
            trim(strtoupper($r->Business_category)) == 'RETAILER' ||
            trim(strtoupper($r->Business_category)) == 'DEALER') {
                $w_count+=$r->Count;
            } else if(trim(strtoupper($r->Business_category)) == 'SERVICE'){
                $s_count+=$r->Count;
            } else if(trim(strtoupper($r->Business_category)) == 'PRODUCER' ||
            trim(strtoupper($r->Business_category)) == 'MANUFACTURER'){
                $m_count+=$r->Count;
            } else if(trim(strtoupper($r->Business_category)) == 'FOOD ESTABLISHMENT'){
                $f_count+=$r->Count;
            } else if(trim(strtoupper($r->Business_category)) == 'AMUSEMENT'){
                $a_count+=$r->Count;
            } else if(trim(strtoupper($r->Business_category)) == 'FINANCIAL'){
                $b_count+=$r->Count;
            } else if(trim(strtoupper($r->Business_category)) == 'OTHERS'){
                $o_count+=$r->Count;
            } 
        }

        $data = new ArrayObject( 
            array(
                'I. WHOLESALERS/RETAILERS/DEALERS' => $w_count,
                'II. SERVICES' => $s_count,
                'III. MANUFACTURERS/PRODUCERS' => $m_count,
                'IV. FOOD ESTABLISHMENTS' => $f_count,
                'V. AMUSEMENT' => $a_count,
                'VI. BANKS & OTHER FINANCIAL INSTITUTIONS' => $b_count,
                'VII. OTHERS' => $o_count,
                'TOTAL' => $w_count + $s_count + $m_count + $f_count + $a_count + $b_count + $o_count
            )
        );
        return $data;
    }

    public function get_barangay(){
        $this->db->select(
            'Barangay,'
        );
        $this->db->order_by('Barangay','asc');
        $query = $this->db->get('tbl_barangay');
        return $query->result();
    }
    
    public function get_class(){
        $this->db->select(
            'Description,'
        );
        $this->db->order_by('Description','asc');
        $this->db->where('ParentID', 0);
        $query = $this->db->get('tbl_business_line');
        return $query->result();
    }
    
    public function get_ownership(){
        $this->db->select(
            'Type,'
        );
        $this->db->order_by('ID','asc');
        $query = $this->db->get('tbl_business_type');
        return $query->result();
    }

    private function getcycleDate($ID){
        $this->db->select('tbl_cycle.Cycle_date');
        $this->db->order_by('ID', 'desc');
        $query = $this->db->get_where('tbl_cycle',array('Application_ID'=>$ID))->first_row();        
        return $query;
    }

    private function getcycleID($ID){
        $cycle = $this->getcycleDate($ID);   
        $this->db->select('ID');
        $this->db->from('tbl_cycle');
        $this->db->where('Cycle_date', $cycle->Cycle_date);
        $this->db->where('Application_ID', $ID);
        $this->db->order_by('ID', 'desc');
        $query = $this->db->get()->first_row();    
        return $query;
    }
}