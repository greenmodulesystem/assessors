
<?php if($result != null) {
    foreach($result as $key => $count){?>
        <tr>
            <td><?=strtoupper($key)?></td>
            <td><?=strtoupper($count)?></td>
        </tr>
<?php } 
}?> 
