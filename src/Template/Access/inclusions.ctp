<fieldset>
    <legend class="s32">ACL Inclusions</legend>
</fieldset>

<div class="row group_permissions_table">
    <div class="col-md-12">
        <div class="panel panel-default">
            <!-- Start .panel -->
            <div class="panel-heading">
                <h4 class="panel-title">Set Inclusions [<?php echo $this->Form->postLink('acoSync', ['controller' => 'Access', 'action' => 'sync'], ['title' => 'Click to sync new Controllers ans Actions', 'class' => 'color-blue']); ?>]</h4>
            </div>
            <div class="panel-body">
                <table class="table table-hover table-bordered">
                    <thead>
                    <tr>
                        <th>Controllers / Actions</th>
                        <th class="width10">Included?</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (isset($acos) && $acos): ?>
                        <tr>
                            <td>All Controllers</td>
                            <td class="width10">
                                <span class="aco_displayed <?php echo $acos->included ? '' : 'opacity02' ?>" rel="<?php echo $acos->id; ?>">&#10004;</span>
                            </td>
                        </tr>
                        <?php foreach ($acos->children as $cont): ?>
                            <tr>
                                <td><?php echo str_repeat("&nbsp;", 5); ?><strong><?php echo $cont->alias; ?></strong></td>
                                <td class="width10">
                                    <span class="aco_displayed <?php echo $acos->included && $cont->included ? '' : 'opacity02' ?>" rel="<?php echo $cont->id; ?>">&#10004;</span>
                                </td>
                            </tr>
                            <?php foreach ($cont->children as $act): ?>
                                <tr>
                                    <td><?php echo str_repeat("&nbsp;", 20); ?><?php echo $act->alias; ?></td>
                                    <td class="width10">
                                        <span class="aco_displayed <?php echo $cont->included && $act->included ? '' : 'opacity02' ?>" rel="<?php echo $act->id; ?>">&#10004;</span>
                                    </td>
                                </tr>
                                <?php foreach ($act->children as $item): ?>
                                    <tr>
                                        <td><?php echo str_repeat("&nbsp;", 30); ?><?php echo $item->alias; ?></td>
                                        <td class="width10">
                                            <span class="aco_displayed <?php echo $cont->included && $act->included && $item->id ? '' : 'opacity02' ?>" rel="<?php echo $item->id; ?>">&#10004;</span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php echo $this->Html->css('Pcl.style.css'); ?>

<?php $this->Html->scriptStart(['block' => true]); ?>
<?php ob_start(); ?>
<SCRIPT>
    /* to change individual item permission */
    $(document).on('click', '.group_permissions_table td span.aco_displayed', function (evt) {
        evt.preventDefault();
        var _this = $(this);
        var acoId = _this.attr('rel');
        var currentlyExcluded = _this.hasClass('opacity02') ? 1 : 0;
        console.log(acoId);
        $.ajax({
            type: "POST",
            url: "<?php echo $this->Url->build(['action' => 'changeInclusion']); ?>",
            data: {acoId: acoId, currentlyExcluded: currentlyExcluded}
        }).done(function (msg) {
            console.log(msg);
            if (msg == 'included') {
                _this.removeClass('opacity02');
            } else if (msg == 'excluded') {
                _this.addClass('opacity02');
            }
        });
    });
</SCRIPT>
<?php echo str_replace(['<SCRIPT>', '</SCRIPT>'], '', ob_get_clean()); ?>
<?php $this->Html->scriptEnd(); ?>