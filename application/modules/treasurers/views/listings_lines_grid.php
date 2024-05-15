
<?php if($result != null) {
    foreach($result as $key => $line){ 
        $Gross = $line->Essential == null ? $line->NonEssential : $line->Essential?>
        <tr>
            <td><?=strtoupper($line->Permit_no)?><br>&emsp;<?=strtoupper($line->Status)?></td>
            <td><?=strtoupper($line->Trade_name_franchise)?><br>&emsp;
                <?=strtoupper($line->Street.", ".$line->Barangay.", SAGAY City")?>
            </td>
            <td><?=strtoupper($line->Tax_payer)?><br>&emsp;<?=strtoupper($line->Owner_address)?></td>
            <td><?=strtoupper($line->Barangay)?></td>
            <td><?=strtoupper($line->Business_category)?><br>&emsp;<?=strtoupper($line->Business_line)?></td>
            <td><?=$line->Capitalization == 0 ? " - " : number_format($line->Capitalization, 2)?></td>
            <td><?=$Gross == null ? " - " : number_format($Gross, 2)?></td>
        </tr>
<?php } 
}?> 
