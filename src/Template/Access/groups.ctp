<fieldset>
    <legend class="s32">Manage Group Permissions</legend>
</fieldset>


<div class="row group_permissions_table">
    <div class="col-md-12">
        <div class="panel panel-default">
            <!-- Start .panel -->
            <div class="panel-heading">
                <h4 class="panel-title"><i class="fa fa-user"></i>Set Permissions [<?php echo $this->Form->postLink('acoSync', ['controller' => 'Access', 'action' => 'sync'], ['title' => 'Click to sync new Controllers ans Actions', 'class' => 'color-blue']); ?>]</h4>
            </div>
            <div class="panel-body">
                <table class="table table-hover table-bordered">
                    <thead>
                    <tr>
                        <?php foreach ($groups as $group): ?>
                            <th><?php echo $group->name; ?></th>
                        <?php endforeach; ?>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($acos as $key => $aco): ?>
                        <?php
                        if ($key > 0) {
                            break;
                        }
                        if ($aco->aros) {
                            $gRos = \Cake\Utility\Hash::combine($aco->aros, '{n}.foreign_key', '{n}');
                        }
                        ?>
                        <tr>
                            <?php foreach ($groups as $group): ?>
                                <?php
                                $gPermitted = (isset($gRos[$group->id]) && $gRos[$group->id]->model == 'Groups' && $gRos[$group->id]->_joinData->_create == 1);
                                ?>
                                <td>
                                    <span class="aco_alias">All Controllers</span>
                                    <span class="aco_permission <?php echo $gPermitted ? '' : 'opacity02' ?>" rel="<?php echo $group->id . '_' . $aco->id; ?>">&#10004;</span>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                        <?php foreach ($aco->children as $cont): ?>
                            <?php
                            $cRos = [];
                            if ($cont->aros) {
                                $cRos = \Cake\Utility\Hash::combine($cont->aros, '{n}.foreign_key', '{n}');
                            }
                            ?>
                            <tr>
                                <?php foreach ($groups as $group): ?>
                                    <?php
                                    $gPermitted = (isset($gRos[$group->id]) && $gRos[$group->id]->model == 'Groups' && $gRos[$group->id]->_joinData->_create == 1);
                                    $cPermitted = (isset($cRos[$group->id]) && $cRos[$group->id]->model == 'Groups' && $cRos[$group->id]->_joinData->_create == 1);
                                    if (!isset($cRos[$group->id])) {
                                        $cPermitted = $gPermitted;
                                    }
                                    ?>
                                    <td>
                                        <span class="aco_alias"><?php echo str_repeat("&nbsp;", 5); ?><strong><?php echo $cont->alias; ?></strong></span>
                                        <span class="aco_permission <?php echo $cPermitted ? '' : 'opacity02'; ?>" rel="<?php echo $group->id . '_' . $cont->id; ?>">&#10004;</span>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                            <?php foreach ($cont->children as $act): ?>
                                <?php
                                $aRos = [];
                                if ($act->aros) {
                                    $aRos = \Cake\Utility\Hash::combine($act->aros, '{n}.foreign_key', '{n}');
                                }
                                ?>
                                <tr>
                                    <?php foreach ($groups as $group): ?>
                                        <?php
                                        $gPermitted = (isset($gRos[$group->id]) && $gRos[$group->id]->model == 'Groups' && $gRos[$group->id]->_joinData->_create == 1);
                                        $cPermitted = (isset($cRos[$group->id]) && $cRos[$group->id]->model == 'Groups' && $cRos[$group->id]->_joinData->_create == 1);
                                        if (!isset($cRos[$group->id])) {
                                            $cPermitted = $gPermitted;
                                        }
                                        $aPermitted = ((isset($aRos[$group->id]) && $aRos[$group->id]->model == 'Groups' && $aRos[$group->id]->_joinData->_create == 1));
                                        if (!isset($aRos[$group->id])) {
                                            $aPermitted = $cPermitted;
                                        }
                                        ?>
                                        <td>
                                            <span class="aco_alias"><?php echo str_repeat("&nbsp;", 20); ?><?php echo $act->alias; ?></span>
                                            <span class="aco_permission <?php echo $aPermitted ? '' : 'opacity02' ?>" rel="<?php echo $group->id . '_' . $act->id; ?>">&#10004;</span>
                                        </td>
                                    <?php endforeach; ?>
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

<style>
    .group_permissions_table table td span {
        display: inline-block;
    }

    .group_permissions_table table td span.aco_alias {
        width: 90%;
    }

    .group_permissions_table table td span.aco_permission {
        width: 5%;
        cursor: pointer;
    }
</style>

<?php //$this->Html->scriptStart(['block' => true]); ?>

<SCRIPT>
    /* to change individual item permission */
    $(document).on('click', '.group_permissions_table td span.aco_permission', function (evt) {
        evt.preventDefault();
        var _this = $(this);
        var aro_aco = _this.attr('rel');
        var currentlyDenied = _this.hasClass('opacity02') ? 1 : 0;
        console.log(currentlyDenied);
        $.ajax({
            type: "POST",
            url: "<?php echo $this->Url->build(['action' => 'exChangePermission']); ?>",
            data: {aro_aco: aro_aco, currentlyDenied: currentlyDenied}
        }).done(function (msg) {
            console.log(msg);
            if (msg == 'allowed') {
                _this.removeClass('opacity02');
            } else if (msg == 'denied') {
                _this.addClass('opacity02');
            }
        });
    });

</SCRIPT>

<?php //$this->Html->scriptEnd(); ?>