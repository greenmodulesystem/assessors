
<?php if($result != null) {
    foreach($result as $key => $r){ ?>
        <tr>
            <td><?=date('Y/m/d', strtotime($r->Date_application))?></td>
            <td><?=strtoupper($r->Permit_no)?></td>
            <td><?=strtoupper($r->Business_name)?></td>
            <td><?=strtoupper($r->Street.", ".$r->Barangay.", SAGAY City")?></td>
            <td><?=strtoupper($r->Tradename)?></td>
            <td><?php $bline = '';
                foreach($r->Lines as $line){
                    $bline .= $line->Business_line.", ";
                }
                echo substr($bline, 0, -2);?></td>
            <td><?=strtoupper($r->Status)?></td>
        </tr>
<?php } 
}?> 
