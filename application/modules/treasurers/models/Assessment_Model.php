<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Assessment_Model extends CI_Model
{

    public $Application_ID;

    function __construct()
    {
        parent::__construct();
        $model_list = [
            'treasurers/Fees_Model' => 'MFees',
            'treasurers/Profiles_Model' => 'MProfiles',
        ];
        $this->load->model($model_list);

        $libray_list = [
            "Logs_library" => 'logs'
        ];
        $this->load->library($libray_list);
    }

    public function approve_assessment($data, $ID)
    {
        $this->db->set($data);
        $this->db->where('ID', $ID);
        $table = "tbl_assessment";
        $this->db->update($table);

        $module = "Assessment - APPROVED";
        $action = "Update";
        $cyc = $this->assessment_cycle($ID);
        $this->update_logs($module, $table, $action, $cyc->Cycle_ID);
    }

    public function approval_cancel($data, $ID)
    {
        $this->db->set($data);
        $this->db->where('ID', $ID);
        $table = "tbl_assessment";
        $this->db->update($table);

        $module = "Assessment - ASSESSED";
        $action = "Add";
        $this->update_logs($module, $table, $action, $cycle->ID);
    }

    public function assess($data, $ID)
    {
        $cycle = $this->getcycleID($ID);
        $data['Cycle_ID'] = $cycle->ID;
        $table = "tbl_assessment";
        $this->db->insert($table, $data);
        $last_id = $this->db->insert_id();

        $module = "Assessment - ASSESSED";
        $action = "Add";
        $this->update_logs($module, $table, $action, $cycle->ID);

        return $last_id;
    }

    public function assessment($ID)
    {
        $cycle = $this->getcycleID($ID);
        $this->db->where('Cycle_ID', $cycle->ID);
        $this->db->order_by('Date_assessed', 'desc');
        $query = $this->db->get('tbl_assessment');
        return $query->first_row();
    }

    public function assessment_cycle($Ass_ID)
    {
        $this->db->where('ID', $Ass_ID);
        $query = $this->db->get('tbl_assessment');
        return $query->first_row();
    }

    public function assessment_details($ID)
    {
        $cycle = $this->getcycleID($ID);
        $this->db->select(
            'd.*,' .
                's.Category,' .
                's.Sanitary_fee,' .
                'w.Size,' .
                'w.Waste_fee'
        );
        $this->db->from('tbl_assessment_details d');
        $this->db->join('tbl_fees_sanitary s', 's.ID = d.Category_ID', 'left');
        $this->db->join('tbl_cenro_line c', 'c.Cycle_ID = ' . $cycle->ID, 'left');
        $this->db->join('tbl_fees_solid_waste w', 'w.ID = c.Solid_waste_ID', 'left');
        $this->db->where('d.Cycle_ID', $cycle->ID);
        $query = $this->db->get();
        return $query->first_row();
    }

    public function assessment_fees($a_ID)
    {
        $this->db->where('Assessment_ID', $a_ID);
        $query = $this->db->get('tbl_assessment_fees');
        $result = $query->result();

        $tax = [];
        $reg = [];
        $chr = [];

        foreach ($result as $r) {
            if ($r->Fee_category == 'Business Tax') {
                $tax[$r->Fee_name] = $r->Fee;
            } else if ($r->Fee_category == 'Regulatory Fee') {
                $reg[$r->Fee_name] = $r->Fee;
            } else {
                $chr[$r->Fee_name] = $r->Fee;
            }
        }

        $data = new ArrayObject(
            array(
                'Business Tax' => $tax,
                'Regulatory Fee' => $reg,
                'Other Charge' => $chr
            )
        );
        return $data;
    }

    public function assessment_fees2($a_ID, $ID)
    {
        $cycle = $this->getcycleID($ID);
        $this->db->where('Assessment_ID', $a_ID);
        $query = $this->db->get('tbl_assessment_fees');
        $result = $query->result();

        $collect = $this->db->get_where('tbl_collection', array('Cycle_ID' => $cycle->ID));

        $tax = [];
        $reg = [];
        $chr = [];

        foreach ($result as $r) {
            if ($r->Fee_category == 'Business Tax') {
                $tax[$r->Fee_name] = array(
                    "Status" => $r->Fee_status,
                    /* "Fee" => $collect->num_rows() > 0 ? 0 : $r->Fee */
                    "Fee" => $r->Fee
                );
            } else if ($r->Fee_category == 'Regulatory Fee') {
                $reg[$r->Fee_name] = array(
                    "Status" => $r->Fee_status,
                    /* "Fee" => $collect->num_rows() > 0 ? 0 : $r->Fee */
                    "Fee" => $r->Fee
                );
            } else {
                $chr[$r->Fee_name]  = array(
                    "Status" => $r->Fee_status,
                    /* "Fee" => $collect->num_rows() > 0 ? 0 : $r->Fee */
                    "Fee" => $r->Fee
                );
            }
        }

        $data = new ArrayObject(
            array(
                'Business Tax' => $tax,
                'Regulatory Fee' => $reg,
                'Other Charge' => $chr
            )
        );
        return $data;
    }

    public function assessment_fees3($a_ID)
    {
        $this->db->where('Assessment_ID', $a_ID);
        $query = $this->db->get('tbl_assessment_fees');
        $result = $query->result();

        $tax = [];
        $reg = [];
        $chr = [];

        foreach ($result as $r) {
            if ($r->Fee_category == 'Business Tax') {
                $tax[$r->Fee_name] = array(
                    "Status" => $r->Fee_status,
                    "Fee" => $r->Fee
                );
            } else if ($r->Fee_category == 'Regulatory Fee') {
                $reg[$r->Fee_name] = array(
                    "Status" => $r->Fee_status,
                    "Fee" => $r->Fee
                );
            } else {
                $chr[$r->Fee_name]  = array(
                    "Status" => $r->Fee_status,
                    "Fee" => $r->Fee
                );
            }
        }

        $data = new ArrayObject(
            array(
                'Business Tax' => $tax,
                'Regulatory Fee' => $reg,
                'Other Charge' => $chr
            )
        );
        return $data;
    }

    public function assessment_history($ID)
    {
        $this->db->where('ID', $ID);
        $query = $this->db->get('tbl_assessment');
        return $query->first_row();
    }

    public function assessment_information($ID)
    {
        $details = $this->assessment_details($ID);
        $amt = 0; // 12272018 update declared default variable
        if ($details == null) {
            return null;
        } else {
            $lines = $this->assessment_lines($ID);
            $fixed = $this->MFees->fixed_fees();
            $profile = $this->MProfiles->get_profile($ID);
            $electrical = $this->getElectrical($ID);
            $other_taxes = $this->get_other_tax(null, null);
            $fixed_taxes = $this->get_fixed_tax(null);

            $others = [];
            if ($details->Waste_fee != 0) {
                $others['Solid Waste Management Fee'] = $details->Waste_fee;
            }
            if ($details->Sanitary_fee != 0) {
                $others['Sanitary Fee'] = $details->Sanitary_fee;
            }
            if ($electrical->Rate != 0) {
                $others['Building Inspection Fee - Local Share 80%'] = (float)$electrical->Rate * 0.8;
                $others['Building Inspection Fee - Nat\'l Share 5%'] = (float)$electrical->Rate * 0.05;
                $others['Building Inspection Fee - OBO  Share 15%'] = (float)$electrical->Rate * 0.15;
            }
            if ($details->Flammable != 0) {
                $others['Flammable Storage Permit Fee'] = $details->Flammable;
            }
            if ($details->DSAFee != 0) {
                $others['Designated Smoking Area Fee '] = $details->DSAFee;
            }
            if ($details->Trucking != 0) {
                $dt = $details->Trucking;
                $truck_fee = 0;
                if ($details->Trucking <= 2 ) {
                    $truck_fee = 500;
                } else {
                    // $truck_fee = ($dt % 2 == 0 ? ( $dt / 2 ) * 100  : ( (int) ($dt / 2) + 1 ) * 100 ) + 500;
                    $truck_fee = (($dt - 2) * 100 ) + 500;
                    // $truck_fee += 500;
                }
                $others['Trucking Permit Fee'] = $truck_fee;
            }
            if ($details->Delivery_Permit != 0) {
                $others['Delivery Permit Fee '] = $details->Delivery_Permit;
            }
            if ($details->Beach_Operator != 0) {
                $others['Beach Operator Fee'] = $details->Beach_Operator;
            }

            $regulatory = []; //regulatory fees : mayor's permit fee, business license, health fee


            foreach ($fixed as $fix) { //other fees to be added. these are fixed fees but subject changes.
                $others[$fix->Category] = $fix->Fee;
            }
            $others['Health Card'] = ($profile->Total_number_employees * 80);
            $others['Occupation Tax'] = ($profile->Total_number_employees * 150);

            if ($profile->Status == 'NEW') {
                foreach ($lines as $key => $line) { //get all business lines then add mayor's permit fee
                    if (strpos($line->Business_line, 'Storage of Flammable Substance') !== false) { //01-07-2020
                    } else if (strpos($line->Business_line, 'Computer') !== false || strpos($line->Business_line, 'Internet') !== false) {
                        $regulatory[$line->Business_line . ' - Mayor\'s Permit'] = $line->NoOfUnits * 150;
                    } else if (strpos($line->Business_line, 'Cigarette Retailer') !== false) {
                        $regulatory[$line->Business_line . ' - Mayor\'s Permit'] = 500;
                    } else if (strpos($line->Business_line, 'Videoke') !== false || strpos($line->Business_line, 'Karaoke') !== false) {
                        $regulatory[$line->Business_line . ' - Mayor\'s Permit'] = $line->NoOfUnits * 220;
                    } else {
                        $regulatory[$line->Business_line . ' - Mayor\'s Permit'] = $line->Fee;
                    }
                } //if application is new, initial tax is added based on declared capital
                $tax = null;
                $initial = $this->get_other_tax(1, "INITIAL TAX");
                $others['Business Plate'] = 150;
                // var_dump($initial);
                $regulatory['Business License'] = $initial->percent1 * $initial->percent2 * $profile->Capitalization;
            } else { //if application is renewal. tax is added
                $tax = []; //initializes tax array
                $others['Sticker'] = 20;
                foreach ($lines as $key => $line) { //loops all business line
                    if ($line->Essential != null || $line->NonEssential != null) { //checker if gross is declared or set
                        $Gross = ($line->Essential == null) ? $line->NonEssential : $line->Essential; //sets the gross variable
                        // $amt = tax
                        if ($line->Exempted) {
                            $amt = 0;
                        } elseif (in_array($line->Business_category, $other_taxes)) { //checks if the business category belongs to the amusement, printing, franchise, or financial/banks
                            $tax_category = $this->get_other_tax(2, $line->Business_Category);
                            $amt = ($tax_category->percent1 / 100) * ($tax_category->percent2 / 100) * $Gross;
                        } elseif (in_array($line->Business_line, $fixed_taxes)) { //checks if the business belongs to the declared fix amount for tax
                            $tax_category = $this->get_fixed_tax($line->Busines_line);
                            if (strpos(strtoupper($line->Business_line), 'REAL ESTATE (SUBDIVISION OPERATOR)') !== false) { //for real estates
                                $amt = $tax_category->Fee * $profile->Business_Area;
                            } elseif (strpos(strtoupper($line->Business_line), 'MART') !== false) { //checks if business line has a mart on it.
                                $amt = $tax_category->Fee * $Gross;
                            } else {
                                $amt = $tax_category->Fee;
                            }
                        } else {
                            if (
                                trim(strtoupper($line->Business_category)) == 'MANUFACTURER' ||
                                trim(strtoupper($line->Business_line)) == 'MANUFACTURER' ||
                                trim(strtoupper($line->Business_line)) == 'ASSEMBLER' ||
                                trim(strtoupper($line->Business_line)) == 'REPACKERS' ||
                                trim(strtoupper($line->Business_line)) == 'PROCESSORS' ||
                                trim(strtoupper($line->Business_line)) == 'BREWERS' ||
                                trim(strtoupper($line->Business_line)) == 'DISTITILLERS' ||
                                trim(strtoupper($line->Business_line)) == 'RECTIFIERS'
                            ) {
                                //checks table based on gross
                                $this->db->where('Gross_from <=', $Gross);
                                $this->db->where('Gross_to >=', $Gross);
                                $query = $this->db->get('tbl_tax_manufacturer')->first_row();
                                if ($query) { //if gross is within the tax table
                                    $amt = $query->Tax;
                                } else { //if gross is not within the taxable range or beyond the range of the last declared amount.
                                    $this->db->order_by('Gross_to', 'desc');
                                    $query = $this->db->get('tbl_tax_manufacturer')->first_row(); //get the last declared amount for the tax table
                                    $this->db->where('Category', 'MANUFACTURER');
                                    $query2 = $this->db->get('tbl_tax_other')->first_row(); //get the variables for computation of excess and the tax
                                    $excess = $Gross - ($query->Gross_to + 1);
                                    $amt = (($query2->percent2 / 100) * ($query2->percent1 / 100) * $excess) + ($query->Tax);
                                }
                            } elseif (
                                trim(strtoupper($line->Business_category)) == 'CONTRACTORS' ||
                                trim(strtoupper($line->Business_category)) == 'SERVICES' ||
                                trim(strtoupper($line->Business_category)) == 'SERVICES ESTABLISHMENTS' ||
                                trim(strtoupper($line->Business_category)) == 'BUSINESS ESTABLISHMENTS' ||
                                trim(strtoupper($line->Business_category)) == 'AGENCIES'
                            ) {
                                $this->db->where('Gross_from <=', $Gross);
                                $this->db->where('Gross_to >=', $Gross);
                                $query = $this->db->get('tbl_tax_manufacturer')->first_row();
                                if ($query) {
                                    $amt = $query->Tax;
                                } else {
                                    $this->db->order_by('Gross_to', 'desc');
                                    $query = $this->db->get('tbl_tax_manufacturer')->first_row();
                                    $this->db->where('Category', 'MANUFACTURER');
                                    $query2 = $this->db->get('tbl_tax_other')->first_row();
                                    $excess = $Gross - ((int)$query->Gross_to + 1);
                                    $amt = (($query2->percent2 / 100) * ($query2->percent1 / 100) * $excess) + ($query->Tax);
                                }
                            } elseif (
                                trim(strtoupper($line->Business_category)) == 'WHOLESALER' ||
                                trim(strtoupper($line->Business_category)) == 'DISTRIBUTORS' ||
                                trim(strtoupper($line->Business_category)) == 'SUPPLIER' ||
                                trim(strtoupper($line->Business_category)) == 'DEALER'
                            ) {
                                $this->db->where('Gross_from <=', $Gross);
                                $this->db->where('Gross_to >=', $Gross);
                                $query = $this->db->get('tbl_tax_dealer')->first_row();
                                if ($query) {
                                    $amt = $query->Tax;
                                } else {
                                    $this->db->order_by('Gross_to', 'desc');
                                    $query = $this->db->get('tbl_tax_dealer')->first_row();
                                    $this->db->where('Category', 'DEALER');
                                    $query2 = $this->db->get('tbl_tax_other')->first_row();
                                    $excess = $Gross - ((int)$query->Gross_to + 1);
                                    $amt = (($query2->percent2 / 100) * ($query2->percent1 / 100) * $excess) + ($query->Tax);
                                }
                            } elseif (
                                trim(strtoupper($line->Business_category)) == 'RETAILER'
                            ) {
                                $query = $this->db->get('tbl_tax_retailer')->first_row(); //yes it has a separate table why not

                                if ($query->gross_less >= $Gross) {
                                    $amt = ($query->tax / 100) * $query->gross_less;
                                } else {
                                    $amt = (($query->tax / 100) * $query->gross_less) + (($query->tax_excess / 100) * ($Gross - $query->gross_more));
                                }
                            }
                        }

                        //FINAL CALCULATION. Checks if gross is essential or notFF
                        $mp = $line->Fee;
                        $rate = ($line->Essential == null) ? $amt : $amt / 2;
                        $rt_mp = $rate * 0.2;
                        if ($rate != 0) {
                            $tax[$line->Business_line] = $rate;
                        }
                        // $regulatory[$line->Business_line . ' - Mayor\'s Permit'] = $mp > $rt_mp ? $mp : $rt_mp;
                        $regulatory[$line->Business_line . ' - Mayor\'s Permit'] = $rt_mp;
                    } elseif ($line->Essential != null && $line->NonEssential != null) { //checks if both essential and non essential gross is declared.
                        //gross is array instead of just a variable which allows the system to compute both declared essential and non essential gross. to be optimized.
                        $Gross = [];
                        $Gross[0] = $line->Essential;
                        $Gross[1] = $line->NonEssential;
                        for ($x = 0; $x <= 1; $x++) {
                            if ($line->Exempted) {
                                $amt += 0;
                            } elseif (in_array($line->Business_category, $other_taxes)) {
                                $tax_category = $this->get_other_tax(2, $line->Business_Category);
                                $amt += ($tax_category->percent1 / 100) * ($tax_category->percent2 / 100) * $Gross[$x];
                            } elseif (in_array($line->Business_line, $fixed_taxes)) {
                                $tax_category = $this->get_fixed_tax($line->Busines_line);
                                if (strpos(strtoupper($line->Business_line), 'REAL ESTATE(SUBDIVISION OPERATOR)') !== false) {
                                    $amt += $tax_category->Fee * $profile->Business_Area;
                                } elseif (strpos(strtoupper($line->Business_line), 'MART') !== false) {
                                    $amt += $tax_category->Fee * $Gross[$x];
                                } else {
                                    $amt += $tax_category->Fee;
                                }
                            } else {
                                if (
                                    trim(strtoupper($line->Business_category)) == 'MANUFACTURER' ||
                                    trim(strtoupper($line->Business_line)) == 'MANUFACTURER' ||
                                    trim(strtoupper($line->Business_line)) == 'ASSEMBLER' ||
                                    trim(strtoupper($line->Business_line)) == 'REPACKERS' ||
                                    trim(strtoupper($line->Business_line)) == 'PROCESSORS' ||
                                    trim(strtoupper($line->Business_line)) == 'BREWERS' ||
                                    trim(strtoupper($line->Business_line)) == 'DISTITILLERS' ||
                                    trim(strtoupper($line->Business_line)) == 'RECTIFIERS'
                                ) {
                                    $this->db->where('Gross_from <=', $Gross[$x]);
                                    $this->db->where('Gross_to >=', $Gross[$x]);
                                    $query = $this->db->get('tbl_tax_manufacturer')->first_row();
                                    if ($query) {
                                        $amt += $query->Tax;
                                    } else {
                                        $this->db->order_by('Gross_to', 'desc');
                                        $query += $this->db->get('tbl_tax_manufacturer')->first_row();
                                        $this->db->where('Category', 'MANUFACTURER');
                                        $query2 = $this->db->get('tbl_tax_other')->first_row();
                                        $query2 += $this->db->get('tbl_tax_other')->first_row();
                                        $excess = $Gross[$x] - ((int)$query->Gross_to + 1);
                                        $amt = (($query2->percent2 / 100) * ($query2->percent1 / 100) * $excess) + ($query->Tax);
                                    }
                                } elseif (
                                    trim(strtoupper($line->Business_category)) == 'CONTRACTORS' ||
                                    trim(strtoupper($line->Business_line)) == 'SERVICES' ||
                                    trim(strtoupper($line->Business_line)) == 'SERVICES ESTABLISHMENTS' ||
                                    trim(strtoupper($line->Business_line)) == 'BUSINESS ESTABLISHMENTS' ||
                                    trim(strtoupper($line->Business_line)) == 'AGENCIES'
                                ) {
                                    $this->db->where('Gross_from <=', $Gross[$x]);
                                    $this->db->where('Gross_to >=', $Gross[$x]);
                                    $query = $this->db->get('tbl_tax_manufacturer')->first_row();
                                    if ($query) {
                                        $amt += $query->Tax;
                                    } else {
                                        $this->db->order_by('Gross_to', 'desc');
                                        $query = $this->db->get('tbl_tax_manufacturer')->first_row();
                                        $this->db->where('Category', 'MANUFACTURER');
                                        $query2 = $this->db->get('tbl_tax_other')->first_row();
                                        $excess = $Gross[$x] - ((int)$query->Gross_to + 1);
                                        $amt = (($query2->percent2 / 100) * ($query2->percent1 / 100) * $excess) + ($query->Tax);
                                    }
                                } elseif (
                                    trim(strtoupper($line->Business_category)) == 'WHOLESALER' ||
                                    trim(strtoupper($line->Business_line)) == 'DISTRIBUTORS' ||
                                    trim(strtoupper($line->Business_line)) == 'SUPPLIER' ||
                                    trim(strtoupper($line->Business_line)) == 'DEALER'
                                ) {
                                    $this->db->where('Gross_from <=', $Gross[$x]);
                                    $this->db->where('Gross_to >=', $Gross[$x]);
                                    $query = $this->db->get('tbl_tax_dealer')->first_row();
                                    if ($query) {
                                        $amt += $query->Tax;
                                    } else {
                                        $this->db->order_by('Gross_to', 'desc');
                                        $query = $this->db->get('tbl_tax_dealer')->first_row();
                                        $this->db->where('Category', 'DEALER');
                                        $query2 = $this->db->get('tbl_tax_other')->first_row();
                                        $excess = $Gross[$x] - ((int)$query->Gross_to + 1);
                                        $amt = (($query2->percent2 / 100) * ($query2->percent1 / 100) * $excess) + ($query->Tax);
                                    }
                                } elseif (
                                    trim(strtoupper($line->Business_category)) == 'RETAILER'
                                ) {
                                    $query = $this->db->get('tbl_tax_retailer')->first_row();

                                    if ($query->gross_less >= $Gross[$x]) {
                                        $amt += ($query->tax / 100) * $query->gross_less;
                                    } else {
                                        $amt += (($query->tax / 100) * $query->gross_less) + (($query->tax_excess / 100) * ($Gross[$x] - $query->gross_more));
                                    }
                                }
                            }
                        }
                        $rate = $amt / 2;
                        foreach ($lines as $key => $line) { //get all business lines then add mayor's permit fee
                            if (strpos($line->Business_line, 'Storage of Flammable Substance') !== false) { //01-07-2020
                            } else if (strpos($line->Business_line, 'Computer') !== false || strpos($line->Business_line, 'Internet') !== false) {
                                $regulatory[$line->Business_line . ' - Mayor\'s Permit'] = $line->NoOfUnits * 150;
                            } else if (strpos($line->Business_line, 'Cigarette Retailer') !== false) {
                                $regulatory[$line->Business_line . ' - Mayor\'s Permit'] = 500;
                            } else if (strpos($line->Business_line, 'Videoke') !== false || strpos($line->Business_line, 'Karaoke') !== false) {
                                $regulatory[$line->Business_line . ' - Mayor\'s Permit'] = $line->NoOfUnits * 220;
                            } else {
                                $regulatory[$line->Business_line . ' - Mayor\'s Permit'] = $line->Fee;
                            }
                        }
                        if ($rate != 0) {
                            $tax[$line->Business_line] = $rate;
                        }
                        $mp = $line->Fee;
                        $rt_mp = $rate * 0.2;
                        if ($rate != 0) {
                            $tax[$line->Business_line] = $rate;
                        }
                        // $regulatory[$line->Business_line . ' - Mayor\'s Permit'] = $mp > $rt_mp ? $mp : $rt_mp;
                        $regulatory[$line->Business_line . ' - Mayor\'s Permit'] = $rt_mp;
                    }
                }

                // old code{
                // else if (strpos(strtoupper($line->Business_line), 'GASOLINE') !== false) {
                //     $amt = 0;
                // } 
                // else if (strpos(strtoupper($line->Business_line), 'CRUDE OIL') !== false) {
                //     $amt = 0;
                // } 
                // else if (strpos(strtoupper($line->Business_line), 'GAS RETAILER') !== false) {
                //     $amt = 0;
                // }

                // else if (
                //     trim(strtoupper($line->Business_category)) == 'MANUFACTURER' ||
                //     trim(strtoupper($line->Business_line)) == 'MANUFACTURER' ||
                //     trim(strtoupper($line->Business_line)) == 'ASSEMBLER' ||
                //     trim(strtoupper($line->Business_line)) == 'REPACKERS' ||
                //     trim(strtoupper($line->Business_line)) == 'PROCESSORS' ||
                //     trim(strtoupper($line->Business_line)) == 'BREWERS' ||
                //     trim(strtoupper($line->Business_line)) == 'DISTITILLERS' ||
                //     trim(strtoupper($line->Business_line)) == 'RECTIFIERS'
                // ) {
                //     if($Gross < 10000){
                //         $amt = 180;
                //     }
                //     else if($Gross >= 10000 && $Gross < 15000){
                //         $amt = 240;
                //     }
                //     else if($Gross >= 15000 && $Gross < 20000){
                //         $amt = 330;
                //     }
                //     else if($Gross >= 20000 && $Gross < 30000){
                //         $amt = 485;
                //     }
                //     else if($Gross >= 30000 && $Gross < 40000){
                //         $amt = 725;
                //     }
                //     else if($Gross >= 40000 && $Gross < 50000){
                //         $amt = 905;
                //     }
                //     else if($Gross >= 50000 && $Gross < 75000){
                //         $amt = 1450;
                //     }
                //     else if($Gross >= 75000 && $Gross < 100000){
                //         $amt = 1815;
                //     }
                //     else if($Gross >= 100000 && $Gross < 150000){
                //         $amt = 2420;
                //     }
                //     else if($Gross >= 150000 && $Gross < 200000){
                //         $amt = 3025;
                //     }
                //     else if($Gross >= 200000 && $Gross < 300000){
                //         $amt = 4235;
                //     }
                //     else if($Gross >= 300000 && $Gross < 500000){
                //         $amt = 6000;
                //     }
                //     else if($Gross >= 500000 && $Gross < 750000){
                //         $amt = 8800;
                //     }
                //     else if($Gross >= 750000 && $Gross < 1000000){
                //         $amt = 11000;
                //     }
                //     else if($Gross >= 1000000 && $Gross < 2000000){
                //         $amt = 15125;
                //     }
                //     else if($Gross >= 2000000 && $Gross < 3000000){
                //         $amt = 18150;
                //     }
                //     else if($Gross >= 3000000 && $Gross < 4000000){
                //         $amt = 21780;
                //     }
                //     else if($Gross >= 4000000 && $Gross < 5000000){
                //         $amt = 25410;
                //     }
                //     else if($Gross >= 5000000 && $Gross < 6500000){
                //         $amt = 26810;
                //     }
                //     else if ($Gross > 6500000) {
                //         $excess = $Gross - 6500000;
                //         $amt = 26812.50 + (0.41 * (0.01 * $excess));
                //     }

                // } 
                // else if(
                //     trim(strtoupper($line->Business_category)) == 'DEALER' || 
                //     trim(strtoupper($line->Business_category)) == 'WHOLESALER' || 
                //     trim(strtoupper($line->Business_category)) == 'DISTRIBUTOR'
                // ) {
                //     if($Gross < 1000){
                //         $amt = 19;
                //     }
                //     else if ($Gross >= 1000 && $Gross < 2000){
                //         $amt = 35;
                //     }
                //     else if ($Gross >= 2000 && $Gross < 3000){
                //         $amt = 55;
                //     }
                //     else if ($Gross >= 3000 && $Gross < 4000){
                //         $amt = 79;
                //     }
                //     else if ($Gross >= 4000 && $Gross < 5000){
                //         $amt = 110;
                //     }
                //     else if ($Gross >= 5000 && $Gross < 6000){
                //         $amt = 133;
                //     }
                //     else if ($Gross >= 6000 && $Gross < 7000){
                //         $amt = 157;
                //     }
                //     else if ($Gross >= 7000 && $Gross < 8000){
                //         $amt = 180;
                //     }
                //     else if ($Gross >= 8000 && $Gross < 10000){
                //         $amt = 205;
                //     }
                //     else if ($Gross >= 10000 && $Gross < 15000){
                //         $amt = 242;
                //     }
                //     else if ($Gross >= 15000 && $Gross < 20000){
                //         $amt = 302;
                //     }
                //     else if ($Gross >= 20000 && $Gross < 30000){
                //         $amt = 363;
                //     }
                //     else if ($Gross >= 30000 && $Gross < 40000){
                //         $amt = 484;
                //     }
                //     else if ($Gross >= 40000 && $Gross < 50000){
                //         $amt = 726;
                //     }
                //     else if ($Gross >= 50000 && $Gross < 75000){
                //         $amt = 1089;
                //     }
                //     else if ($Gross >= 75000 && $Gross < 100000){
                //         $amt = 1452;
                //     }
                //     else if ($Gross >= 100000 && $Gross < 150000){
                //         $amt = 2057;
                //     }
                //     else if ($Gross >= 150000 && $Gross < 200000){
                //         $amt = 2662;
                //     }
                //     else if ($Gross >= 200000 && $Gross < 300000){
                //         $amt = 3630;
                //     }
                //     else if ($Gross >= 300000 && $Gross < 500000){
                //         $amt = 4840;
                //     }
                //     else if ($Gross >= 500000 && $Gross < 750000){
                //         $amt = 7260;
                //     }
                //     else if ($Gross >= 750000 && $Gross < 1000000){
                //         $amt = 9680;
                //     }
                //     else if ($Gross >= 1000000 && $Gross < 2000000){
                //         $amt = 11000;
                //     }
                //     else if ($Gross > 2000000){
                //         $excess = $Gross - 2000000;
                //         $amt = 11000 + (0.55 * (0.01 * $excess));
                //     }

                // } 
                // else if(trim(strtoupper($line->Business_category)) == 'RETAILER') {
                //     if($Gross <= 50000) {
                //         $amt = 0;
                //     }
                //     else if($Gross > 50000 && $Gross <= 400000) {
                //         $amt = $Gross * 0.022;
                //     } 
                //     else if($Gross > 400000){
                //         $excess = $Gross - 400000;
                //         $amt = ($Gross * 0.022) + ($excess * 0.011);
                //     }
                // } 
                // else if(trim(strtoupper($line->Business_category)) == 'CONTRACTOR') {
                //     if($Gross < 5000){
                //         $amt = 30;
                //     }
                //     else if($Gross >= 5000 && $Gross < 10000){
                //         $amt = 67;
                //     }
                //     else if($Gross >= 10000 && $Gross < 15000){
                //         $amt = 114;
                //     }
                //     else if($Gross >= 15000 && $Gross < 20000){
                //         $amt = 181;
                //     }
                //     else if($Gross >= 20000 && $Gross < 30000){
                //         $amt = 302;
                //     }
                //     else if($Gross >= 30000 && $Gross < 40000){
                //         $amt = 423;
                //     }
                //     else if($Gross >= 40000 && $Gross < 50000){
                //         $amt = 605;
                //     }
                //     else if($Gross >= 50000 && $Gross < 75000){
                //         $amt = 968;
                //     }
                //     else if($Gross >= 75000 && $Gross < 100000){
                //         $amt = 1452;
                //     }
                //     else if($Gross >= 100000 && $Gross < 150000){
                //         $amt = 2178;
                //     }
                //     else if($Gross >= 150000 && $Gross < 200000){
                //         $amt = 2904;
                //     }
                //     else if($Gross >= 200000 && $Gross < 250000){
                //         $amt = 3993;
                //     }
                //     else if($Gross >= 250000 && $Gross < 300000){
                //         $amt = 5082;
                //     }
                //     else if($Gross >= 300000 && $Gross < 400000){
                //         $amt = 6776;
                //     }
                //     else if($Gross >= 400000 && $Gross < 500000){
                //         $amt = 9075;
                //     }
                //     else if($Gross >= 500000 && $Gross < 750000){
                //         $amt = 10175;
                //     }
                //     else if($Gross >= 750000 && $Gross < 1000000){
                //         $amt = 11275;
                //     }
                //     else if($Gross >= 1000000 && $Gross < 2000000){
                //         $amt = 12650;
                //     }
                //     else if($Gross > 2000000){
                //         //FOR VERIFICATION
                //         $excess = $Gross - 2000000;
                //         $amt = $excess * 0.01 * 0.55 + 12650;

                //         // $excess = $Gross;
                //         // $amt = $excess * 0.01 * 0.55 + 12650;
                //     }

                // } 
                // else if(
                //     trim(strtoupper($line->Business_category)) == 'FINANCIAL' ||
                //     trim(strtoupper($line->Business_line)) == 'FINANCIAL' ||
                //     trim(strtoupper($line->Business_line)) == 'BANK' ||
                //     trim(strtoupper($line->Business_line)) == 'BANKS' ||
                //     trim(strtoupper($line->Business_line)) == 'BANKING'
                // ) {
                //     $amt = (0.55 * (0.01 * $Gross));
                // }
                // }
                // echo json_encode($data);
            }

            $data = new ArrayObject(
                array(
                    'Business Tax' => $tax,
                    'Regulatory Fee' => $regulatory,
                    'Other Charge' => $others
                )
            );
            return $data;
        }
    }


    public function get_other_tax($type, $category)
    {
        if ($type && $category) {

            $this->db->where('Type', $type);
            $this->db->where('Category', $category);
            $query = $this->db->get('tbl_tax_other');
            // var_dump($query);
            return $query->first_row();
        } else {
            $this->db->where('Category', 'AMUSEMENT');
            $this->db->where('Category', 'FRANCHISE');
            $this->db->where('Category', 'PRINTING AND PUBLICATION');
            $this->db->where('Category', 'FINANCIAL');
            $query = $this->db->get('tbl_tax_other');
            return $query->result();
        }
    }

    public function get_fixed_tax($bline)
    {
        $this->db->select(
            '*'
        );
        $this->db->from('tbl_tax_fixed');
        if ($bline) {
            $this->db->like('Description', $bline);
        }
        $query = $this->db->get();
        return $query->result();
    }

    // public function assessment_lines($ID){
    //     $cycle = $this->getcycleID($ID);
    //     $this->db->select(
    //         'a.*,'.
    //         's.Asset_size,'.
    //         's.Characteristics,'.
    //         'f.Fee'
    //     );
    //     $this->db->from('tbl_application_business_line a');
    //     $this->db->join('tbl_assessment_asset s', 's.Asset_from <= a.Capitalization AND s.Asset_to >= a.Capitalization', 'left');
    //     $this->db->join('tbl_fees_mayors_permit f', 'f.Category = a.Business_category AND f.Characteristics = s.Characteristics', 'left');
    //     $this->db->where('a.Cycle_ID', $cycle->ID);
    //     $this->db->order_by('a.ID', 'asc');
    //     $query = $this->db->get();
    //     return $query->result();
    // }

    public function assessment_lines($ID)
    {
        $cycle = $this->getcycleID($ID);
        $this->db->where('Cycle_ID', $cycle->ID);
        $this->db->where('Retirement', 0);
        $this->db->order_by('ID', 'asc');
        $query = $this->db->get('tbl_application_business_line');
        $result = $query->result();

        foreach ($result as $key => $r) {
            if ($r->Assessment_asset_ID == null) {
                $r->Asset_size = null;
                $r->Characteristics = null;
                $r->Fee = null;
            } else {
                // $this->db->select(
                //     's.Asset_size,'.
                //     's.Characteristics,'.
                //     'f.Fee'
                // );
                // $this->db->from('tbl_application_business_line a');
                // $this->db->join('tbl_assessment_asset s', 's.Asset_from <= a.Capitalization AND s.Asset_to >= a.Capitalization', 'left');
                // $this->db->join('tbl_fees_mayors_permit f', 'f.Category = a.Business_category AND f.Characteristics = s.Characteristics', 'left');
                // $this->db->where('a.ID', $r->ID);
                // $result2 = $this->db->get()->first_row();
                $this->db->where('ID', $r->Assessment_asset_ID);
                $asset = $this->db->get('tbl_assessment_asset')->first_row();

                $this->db->where('Category', $r->Business_category);
                $this->db->where('Characteristics', $asset->Characteristics);
                $mayors = $this->db->get('tbl_fees_mayors_permit')->first_row();

                $r->Asset_size = $asset->Asset_size;
                $r->Characteristics = $asset->Characteristics;
                $r->Fee = @$mayors->Fee;
            }
        }

        return $result;
    }

    public function delete($ID)
    {
        $this->db->delete("tbl_assessment_fees", array('Assessment_ID' => $ID));
        $this->db->delete("tbl_billing_fees", array('Assessment_ID' => $ID));
        $this->db->delete("tbl_assessment", array('ID' => $ID));

        $module = "Assessment - OVERRIDE";
        $table = "tbl_assessment, tbl_assessment_fees, tbl_billing_fees";
        $action = "Delete";
        $cyc = $this->assessment_cycle($ID);
        $this->update_logs($module, $table, $action, $cyc->Cycle_ID);
    }

    public function details($data, $ID)
    {
        $cycle = $this->getcycleID($ID);
        $data['Cycle_ID'] = $cycle->ID;
        $table = "tbl_assessment_details";
        $this->db->insert($table, $data);

        $module = "Assessment - DETAILS";
        $action = "Add";
        $this->update_logs($module, $table, $action, $cycle->ID);
    }

    public function fees($array, $ID)
    {
        $table = "tbl_assessment_fees";
        $Fee_names = $array['Fee_name'];
        $Fee_category = $array['Fee_category'];
        $Fee_stat = $array['Fee_stat'];
        $Fee = $array['Fee'];
        $data = [];
        foreach ($Fee_names as $key => $Fee_name) {
            array_push(
                $data,
                array(
                    "Assessment_ID" => $ID,
                    "Fee_name" => $Fee_name,
                    "Fee_category" => $Fee_category[$key],
                    "Fee_status" => ($Fee_stat[$key] == '') ? null : $Fee_stat[$key],
                    "Fee" => $Fee[$key]
                )
            );
        }
        $this->db->insert_batch($table, $data);

        $module = "Assessment - ASSESSED";
        $action = "Add";
        $cyc = $this->assessment_cycle($ID);
        $this->update_logs($module, $table, $action, $cyc->Cycle_ID);
    }

    // private function getAsset($Cap){
    //     $this->db->where('Asset_from <=', $Cap);
    //     $this->db->where('Asset_to >=', $Cap);
    //     $query = $this->db->get('tbl_assessment_asset');
    //     return $query->first_row();
    // }

    private function getcycleDate($ID)
    {
        $this->db->select('tbl_cycle.Cycle_date');
        $this->db->order_by('ID', 'desc');
        $query = $this->db->get_where('tbl_cycle', array('Application_ID' => $ID))->first_row();
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

    public function getElectrical($ID)
    {
        $cycle = $this->getcycleID($ID);
        $this->db->select_sum('e.Rate');
        $this->db->from('tbl_city_engineer c');
        $this->db->join('tbl_city_engineer_line e', 'e.City_engineer_ID = c.ID', 'left');
        $this->db->where('c.Cycle_ID', $cycle->ID);
        $query = $this->db->get();
        return $query->first_row();
    }

    // private function getFee($Cat,$Char){
    //     $this->db->where('Category', $Cat);
    //     $this->db->where('Characteristics', $Char);
    //     $query = $this->db->get('tbl_fees_mayors_permit');
    //     return $query->first_row();
    // }

    // public function lines($array,$ID){
    //     $cycle = $this->getcycleID($ID);
    //     $table = "tbl_assessment_business_line";
    //     $Business_line_IDs = $array['Business_line_ID'];
    //     $Capital = $array['Capital'];
    //     $Gross = $array['Gross'];
    //     $data = [];
    //     foreach($Business_line_IDs as $key => $Business_line_ID) {
    //         array_push($data, array(
    //                 "Cycle_ID" => $cycle->ID,
    //                 "Business_line_ID" => $Business_line_ID,
    //                 "Capital" => $Capital[$key],
    //                 "Gross" => $Gross[$key]
    //             )
    //         );
    //     }
    //     $this->db->insert_batch($table, $data);
    // }

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

    public function history_lines($ID)
    {
        $this->db->where('Cycle_ID', $ID);
        $this->db->order_by('ID', 'asc');
        $query = $this->db->get('tbl_application_business_line');
        $result = $query->result();

        foreach ($result as $key => $r) {
            if ($r->Assessment_asset_ID == null) {
                $r->Asset_size = null;
                $r->Characteristics = null;
                $r->Fee = null;
            } else {
                $this->db->where('ID', $r->Assessment_asset_ID);
                $asset = $this->db->get('tbl_assessment_asset')->first_row();

                $this->db->where('Category', $r->Business_category);
                $this->db->where('Characteristics', $asset->Characteristics);
                $mayors = $this->db->get('tbl_fees_mayors_permit')->first_row();

                $r->Asset_size = $asset->Asset_size;
                $r->Characteristics = $asset->Characteristics;
                $r->Fee = @$mayors->Fee;
            }
        }

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
