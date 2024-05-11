
<?php if($result != null) {
    foreach($result as $key => $lines){?>
        <tr class="success">
            <td colspan="2"><?=strtoupper($key)?></td>
        </tr>
        <?php foreach($lines as $key => $line){
            if($key != 'Total_count') { ?>
                <tr>
                    <td>&emsp;<?=strtoupper($line->Business_line)?></td>
                    <td><?=strtoupper($line->Count)?></td>
                </tr>
        <?php }
        } ?>
        <tr class="info">
            <td class="text-right">TOTAL COUNT</td>
            <td><?=$lines['Total_count']?></td>
        </tr>
<?php } 
}?> 
