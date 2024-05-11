<div class="box-body table-responsive no-padding">
    <table class="table table-hover">
        <tbody>
            <?php foreach($result as $item): ?>
            <tr>
                <td width="5%">
                    <a href="<?php echo base_url() ?>users/update-user/<?php echo $item->ID ?>" class="btn btn-success btn-block btn-sm">
                        <i class="fa fa-edit"></i> 
                    </a>
                </td>
                <td width="35%"><?php echo $item->Username ?></td>
                <td width="60%"><?php echo $item->Last_name ?>, <?php echo $item->First_name ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>