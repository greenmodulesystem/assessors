<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Billing_Model extends CI_Model {

    
    function __construct()
    {
        parent::__construct();
		$model_list = [
            'treasurers/Assessment_Model' => 'MAss',
		];
        $this->load->model($model_list);
    }

    //---------------------------------- 08-04-2020  ----------------------------------//
    public function get_arrears($ID){
        $this->db->where('Application_ID', $ID);
        $this->db->order_by('ID', 'desc');
        $query = $this->db->get('tbl_cycle');
        $cycles = $query->result();

        $data = [];
        foreach($cycles as $cycle) {
            $this->db->where('Cycle_ID', $cycle->ID);
            $this->db->order_by('ID', 'desc');
            $query2 = $this->db->get('tbl_assessment');
            $assess = $query2->first_row();

            if($assess != null){
                $this->db->select_sum('Balance');
                $this->db->where('Assessment_ID', $assess->ID);
                $query3 = $this->db->get('tbl_billing_fees');
                $balance = $query3->result();
    
                foreach($balance as $bal) {
                    if($bal->Balance > 0 && $cycle->Cycle_date != date('Y')) {
                        array_push($data, array(
                                "Years" => $cycle->Cycle_date,
                            )
                        );
                    }
                }
            }

        }

        return $data;
    }
    //---------------------------------- 08-04-2020  ----------------------------------//

    public function billing_information($information,$ID,$mode){
        $year = $this->getcycleDate($ID)->Cycle_date;

        $data = [];

        $div = $mode == 3 ? 1 : ($mode == 2 ? 2 : 4); 

        foreach($information as $key => $infos){
            if($key == 'Business Tax' && $infos != null) {
                foreach($infos as $key2 => $info){
                    // $due = round($info,2);
                    $due = $info;
                    // var_dump($due);
                    $per_qtr = $due/$div;
                    // echo $per_qtr;
                    for($x=0;$x < $div;$x++) {

                        // if($x+1 == 1) {
                        //     $date = $year.'-01-31';
                        // } else if($x+1 == 2) {
                        //     $date = $year.'-04-20';
                        // } else if($x+1 == 3) {
                        //     $date = $year.'-07-20';
                        // } else if($x+1 == 4) {
                        //     $date = $year.'-10-20';
                        // }
                        $due_det = $this->db->get('tbl_due_date');
                        $due_detu = $due_det->result();
                            //added 4/13/2024
                        if($x+1 == 1) {
                            $date = $year.'-'.$due_detu[0]->Mm.'-'.$due_detu[0]->Dd;
                        } else if($x+1 == 2) {
                            $date = $year.'-'.$due_detu[1]->Mm.'-'.$due_detu[1]->Dd;
                        } else if($x+1 == 3) {
                            $date = $year.'-'.$due_detu[2]->Mm.'-'.$due_detu[2]->Dd;
                        } else if($x+1 == 4) {
                            $date = $year.'-'.$due_detu[3]->Mm.'-'.$due_detu[3]->Dd;
                        }
                    // echo 'DUE: '.$due.'  -  QTR: '.$per_qtr.'</br>';
                        // $amount[$x] = round($per_qtr,2) > round($due,2) ? round($due,2) : round($per_qtr,2);
                        $amount[$x] = $per_qtr > $due ? $due : $per_qtr;

                        if(date('Y-m-d') > $date) {
                            // $bal[$x] = 0;
                            // for($y=$x;$y >= 0; $y--){
                            //     $bal[$x] += $amount[$y];
                            // }
                            $surcharge = $amount[$x] * 0.25;
                            $permonth = ((date('Y') - date('Y',strtotime($date))) * 12) + (date('m') - date('m',strtotime($date)));
                            $interest = (($amount[$x] + $surcharge) * (2 * $permonth)) / 100;
                        } else {
                            $surcharge = 0;
                            $interest = 0;
                        }

                        array_push($data, array(
                            "Qtr" => $x+1,
                            "Line_of_business" => $key2,
                            "Due_date" => $date,
                            "Balance" => $amount[$x],
                            "Discount" => 0,
                            "Surcharge" => round($surcharge,2),
                            "Interest" => round($interest,2)
                            )
                        );
                        // $due -= round($per_qtr,2);
                        $due -= $per_qtr;
                    }
                }
            }
        }

        // echo json_encode($data);
        return $data;
    }
    
    public function billing_fees($ID){
        $this->db->where('Assessment_ID', $ID);
        $query = $this->db->get('tbl_billing_fees');
        $result = $query->result();

        $data = [];
        foreach($result as $key => $r) {
            array_push($data, array(
                "Assessment_ID" => $r->Assessment_ID,
                "Qtr" => $r->Qtr,
                "Line_of_business" => $r->Line_of_business,
                "Due_date" => $r->Due_date,
                "Balance" => $r->Balance,
                "Discount" => $r->Discount,
                "Surcharge" => $r->Surcharge,
                "Interest" => $r->Interest
                )
            );
        }

        return $data;
    }

    public function billing_fees_copy($ID){
        $this->db->where('Assessment_ID', $ID);
        $query = $this->db->get('tbl_billing_fees_copy');
        $result = $query->result();

        $data = [];
        foreach($result as $key => $r) {
            array_push($data, array(
                "Assessment_ID" => $r->Assessment_ID,
                "Qtr" => $r->Qtr,
                "Line_of_business" => $r->Line_of_business,
                "Due_date" => $r->Due_date,
                "Balance" => $r->Balance,
                "Discount" => $r->Discount,
                "Surcharge" => $r->Surcharge,
                "Interest" => $r->Interest
                )
            );
        }

        return $data;
    }

    public function billing_statement($ID){
        $this->db->where('Assessment_ID', $ID);
        $query = $this->db->get('tbl_billing_fees');
        return $query->result();
    }
    
    public function billing_statement_copy($ID){
        $this->db->where('Assessment_ID', $ID);
        $query = $this->db->get('tbl_billing_fees_copy');
        return $query->result();
    }
    
    public function collection($ID){
        $cycle = $this->getcycleID($ID);
        $query = $this->db->get_where('tbl_collection',array('Cycle_ID'=>$cycle->ID));
        return $query->num_rows();
    }

    public function fees($bills,$ID){
        $table = "tbl_billing_fees";
        $table2 = "tbl_billing_fees_copy";
        $data = [];
        foreach($bills as $key => $bill) {
            array_push($data, array(
                "Assessment_ID" => $ID,
                "Qtr" => $bill['Qtr'],
                "Line_of_business" => $bill['Line_of_business'],
                "Due_date" => $bill['Due_date'],
                "Balance" => $bill['Balance'],
                "Discount" => $bill['Discount'],
                "Surcharge" => $bill['Surcharge'],
                "Interest" => $bill['Interest']
                )
            );
        }
        $this->db->insert_batch($table, $data);
        $this->db->insert_batch($table2, $data);
        
        $module = "Billing";
        $action = "Add";
        $cyc = $this->MAss->assessment_cycle($ID);
        $this->MAss->update_logs($module,$table,$action,$cyc->Cycle_ID);
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

    public function update_fees($info){
        $table = "tbl_billing_fees";
        $Fees_amounts = $info['Fees_amount'];
        $data = [];
        for($Qtr = 1;$Qtr <= 4; $Qtr++) {
            array_push($data, array(
                    "Qtr" => $Qtr,
                    $info['Type'] => $Fees_amounts[$Qtr-1],
                )
            );
        }
        $this->db->where('Assessment_ID', $info['Assessment_ID']);
        $this->db->where('Line_of_business', $info['Line_of_business']);
        $this->db->update_batch($table, $data, 'Qtr');

        $module = "Billing";
        $action = "Update";
        $cyc = $this->MAss->assessment_cycle($ID);
        $this->MAss->update_logs($module,$table,$action,$cyc->Cycle_ID);
    }
    
    public function update_fees_copy($info){
        $table = "tbl_billing_fees_copy";
        $Fees_amounts = $info['Fees_amount'];
        $data = [];
        for($Qtr = 1;$Qtr <= 4; $Qtr++) {
            array_push($data, array(
                    "Qtr" => $Qtr,
                    $info['Type'] => $Fees_amounts[$Qtr-1],
                )
            );
        }
        $this->db->where('Assessment_ID', $info['Assessment_ID']);
        $this->db->where('Line_of_business', $info['Line_of_business']);
        $this->db->update_batch($table, $data, 'Qtr');
    }
}