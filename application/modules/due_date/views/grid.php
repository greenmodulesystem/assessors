<?php
if (!empty($fees_list)) {
    foreach ($fees_list as $value) {
?>
        <tr>
            <td> - </td>
            <td> <?= @$value->Description ?> </td>
            <td> <?= @$value->Rate ?> </td>
        </tr>
<?php
    }
}
?>