<fieldset>
    <legend class="s32">ACL acoSync</legend>
</fieldset>

<div class="row group_permissions_table">
    <div class="col-md-12">
        <div class="panel panel-default">
            <!-- Start .panel -->
            <div class="panel-heading">
                <h4 class="panel-title"><i class="fa fa-user"></i>Set Exclusion
                    [<?php echo $this->Form->postLink('acoSync', ['controller' => 'Access', 'action' => 'sync'], ['title' => 'Click to sync new Controllers ans Actions', 'class' => 'color-blue']); ?>
                    ]</h4>
            </div>
            <div class="panel-body">
                <table class="table table-hover table-bordered">
                    <thead>
                    <tr>
                        <th>Controller / Action</th>
                        <th>Excluded</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($acos as $key => $aco): ?>
                        <?php
                        if ($key > 0) {
                            break;
                        }
                        ?>
                        <tr>
                            <td>All Controllers</td>
                            <td>
                                <span class="aco_exclusion <?php echo $aco->excluded ? '' : 'opacity02' ?>" rel="<?php echo $aco->id; ?>">&#10004;</span>
                            </td>
                        </tr>
                        <?php foreach ($aco->children as $cont): ?>
                            <tr>
                                <td><?php echo str_repeat("&nbsp;", 5); ?><strong><?php echo $cont->alias; ?></strong></td>
                                <td>
                                    <span class="aco_exclusion <?php echo $cont->excluded ? '' : 'opacity02' ?>" rel="<?php echo $cont->id; ?>">&#10004;</span>
                                </td>
                            </tr>
                            <?php foreach ($cont->children as $act): ?>
                                <tr>
                                    <td><?php echo str_repeat("&nbsp;", 20); ?><?php echo $act->alias; ?></td>
                                    <td>
                                        <span class="aco_exclusion <?php echo $act->excluded ? '' : 'opacity02' ?>" rel="<?php echo $act->id; ?>">&#10004;</span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php echo $this->Html->css('Pcl.pcl.css'); ?>

