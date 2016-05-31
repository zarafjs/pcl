<fieldset>
    <legend class="s32">Manage Group Permissions</legend>
</fieldset>


<div class="row group_permissions_table">
    <div class="col-md-12">
        <div class="panel panel-default">
            <!-- Start .panel -->
            <div class="panel-heading">
                <h4 class="panel-title"><i class="fa fa-user"></i>Set Permissions</h4>
            </div>
            <div class="panel-body">
                <table class="table table-hover table-bordered">
                    <thead>
                    <tr>
                        <?php foreach ($groups as $group): ?>
                            <th class="per25"><?php echo $group->name; ?></th>
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
                                    <div class="">All Controllers</div>
                                    <div class="per10 text-right"><i rel="<?php echo $group->id . '_' . $aco->id; ?>" class="icomoon-icon-checkmark <?php echo $gPermitted ? '' : 'opacity02' ?>"></i></div>
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
                                        <div class=""><?php echo str_repeat("&nbsp;", 5); ?><strong><?php echo $cont->alias; ?></strong></div>
                                        <div class="per10 text-right"><i rel="<?php echo $group->id . '_' . $cont->id; ?>" class="icomoon-icon-checkmark <?php echo $cPermitted ? '' : 'opacity02'; ?>"></i></div>
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
                                            <div class=""><?php echo str_repeat("&nbsp;", 20); ?><?php echo $act->alias; ?></div>
                                            <div class="per10 text-right"><i rel="<?php echo $group->id . '_' . $act->id; ?>" class="icomoon-icon-checkmark <?php echo $aPermitted ? '' : 'opacity02' ?>"></i></div>
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

<?php $this->Html->scriptStart(['block' => true]); ?>

<SCRIPT>
    /* to change individual item permission */
    $(document).on('click', '.group_permissions_table td i.icomoon-icon-checkmark', function (evt) {
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

<?php $this->Html->scriptEnd(); ?>