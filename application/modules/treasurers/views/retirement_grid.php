<?php if ($result != null) {
    foreach ($result as $key => $profile) {
        $Tradename = $profile->Tradename == '' ? $profile->Business_name : $profile->Tradename; ?>
        <tr>
            <td><?= strtoupper($profile->Tax_payer) ?></td>
            <td><?= strtoupper($Tradename) ?></td>
            <td><?= date('F d, Y', strtotime($profile->Date_application)) ?></td>
            <td>
                <button class="btn btn-default btn-sm flat check_bill" data-id="<?= $profile->ID ?>" style="width:10vw;">
                    Check Bill
                </button>
            </td>
        </tr>
<?php }
} ?>