<?php if (@$applicants) {
    foreach (@$applicants as $val) {
?>
        <tr>
            <td class="text-center">
                <?= $val->Tax_payer=="" || $val->Tax_payer==" " ? $val->Business_name : $val->Tax_payer ?>
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-success view-cycle" data-id="<?=$val->ID?>">VIEW</button>
            </td>
        </tr>
<?php }
} else {
    echo '<td colspan="2">No Applicants</td>';
} ?>