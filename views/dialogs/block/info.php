<?php
defined('C5_EXECUTE') or die('Access Denied.');

/** @var \Concrete\Core\Block\Block $b */
$app = \Concrete\Core\Support\Facade\Facade::getFacadeApplication();
/** @var \Concrete\Core\User\UserInfoRepository $repository */
$repository = $app->make(\Concrete\Core\User\UserInfoRepository::class);
/** @var \Concrete\Core\Utility\Service\Text $th */
$th = $app->make(\Concrete\Core\Utility\Service\Text::class);
/** @var \Concrete\Core\Validation\CSRF\Token $token */
$token = $app->make(\Concrete\Core\Validation\CSRF\Token::class);
/** @var \Concrete\Core\Form\Service\Form $form */
$form = $app->make(\Concrete\Core\Form\Service\Form::class);

if (isset($b) && is_object($b)) {
    ?>
    <table class="table table-striped">
        <tbody>
        <tr>
            <th><?= t('Block ID'); ?></th>
            <td><?= h($b->getBlockID()); ?></td>
        </tr>
        <tr>
            <th><?= t('Block Name'); ?></th>
            <td><?= h($b->getBlockName()); ?></td>
        </tr>
        <tr>
            <th><?= t('Block Type Handle'); ?></th>
            <td><?= h($b->getBlockTypeHandle()); ?></td>
        </tr>
        <tr>
            <th><?= t('Package Handle'); ?></th>
            <td><?= h($b->getPackageHandle()); ?></td>
        </tr>
        <tr>
            <th><?= t('User who created this block'); ?></th>
            <td><?php
                $uID = $b->getBlockUserID();
    $ui = $repository->getByID($uID);
    if (is_object($ui)) {
        echo $ui->getUserName();
    } else {
        echo t('(Deleted User)');
    } ?></td>
        </tr>
        <tr>
            <th><?= t('Date Added'); ?></th>
            <td><?= h($b->getBlockDateAdded()); ?></td>
        </tr>
        <tr>
            <th><?= t('Date Last Modified'); ?></th>
            <td><?= h($b->getBlockDateLastModified()); ?></td>
        </tr>
        <tr>
            <th><?= t('Custom Template'); ?></th>
            <td><?= h($b->getBlockFilename()); ?></td>
        </tr>
        <tr>
            <th><?= t('Grid container enabled?'); ?></th>
            <td><?= ($b->enableBlockContainer()) ? t('Yes') : t('No'); ?></td>
        </tr>
        <tr>
            <th><?= t('Has custom style?'); ?></th>
            <td><?= ($b->getCustomStyle()) ? t('Yes') : t('No'); ?></td>
        </tr>
        <tr>
            <th><?= t('Is alias?'); ?></th>
            <td><?= ($b->isAlias()) ? t('Yes') : t('No'); ?></td>
        </tr>
        <tr>
            <th><?= t('Is alias from master collection?'); ?></th>
            <td><?= ($b->isAliasOfMasterCollection()) ? t('Yes') : t('No'); ?></td>
        </tr>
        <tr>
            <th><?= t('Cache block output?'); ?></th>
            <td><?= ($b->cacheBlockOutput()) ? t('Yes') : t('No'); ?></td>
        </tr>
        <tr>
            <th><?= t('Cache block output on post?'); ?></th>
            <td><?= ($b->cacheBlockOutputOnPost()) ? t('Yes') : t('No'); ?></td>
        </tr>
        <tr>
            <th><?= t('Cache block output for registered users?'); ?></th>
            <td><?= ($b->cacheBlockOutputForRegisteredUsers()) ? t('Yes') : t('No'); ?></td>
        </tr>
        <tr>
            <th><?= t('Lifetime of the block output cache'); ?></th>
            <td><?= \Punic\Unit::format($b->getBlockOutputCacheLifetime(), 'second'); ?></td>
        </tr>
        <?php if ($b->getBlockAreaObject()) {
        ?>
            <tr>
                <th><?= t('Block output cache exists?'); ?></th>
                <td><?php
                    if ($b->getBlockCachedOutput($b->getBlockAreaObject())) {
                        echo sprintf('<a class="ccm-show-block-cached-output" href="#">%s</a>', t('Yes'));
                    } else {
                        echo t('No');
                    } ?></td>
            </tr>
        <?php
    } ?>
        <tr>
            <th><?= t('Block record cache exists?'); ?></th>
            <td><?= ($b->getBlockCachedRecord()) ? t('Yes') : t('No'); ?></td>
        </tr>
        </tbody>
    </table>
    <div class="dialog-buttons">
        <button class="btn btn-default pull-left" data-dialog-action="cancel"><?= t('Cancel'); ?></button>
    </div>
    <?php if ($b->getBlockAreaObject()) {
        ?>
        <div class="hidden ccm-block-cached-output-container">
            <pre><?= $th->entities($b->getBlockCachedOutput($b->getBlockAreaObject())); ?></pre>
            <div class="dialog-buttons">
                <button class="btn btn-default pull-left" data-dialog-action="cancel"><?= t('Cancel'); ?></button>
                <button class="btn btn-danger pull-right" data-dialog-action="ajax"><?= t('Clear'); ?></button>

            </div>
        </div>
        <script>
            var $dialog = $(".ccm-block-cached-output-container");
            $('.ccm-show-block-cached-output').on('click', function (e) {
                e.preventDefault();
                $.fn.dialog.open({
                    modal: true,
                    width: 640,
                    element: $dialog.removeClass('hidden'),
                    title: <?= \json_encode(t('Cached Output')); ?>,
                    height: 'auto'
                });
            });
            $('[data-dialog-action=ajax]').on('click', function (e) {
                $.ajax({
                    url: '<?= $view->action('clear-cache'); ?>',
                    type: 'POST',
                    data: {
                        'bID': <?= \json_encode($b->getBlockID()); ?>,
                        'arHandle': <?= \json_encode($b->getAreaHandle()); ?>,
                        'cID': <?= \json_encode($b->getBlockCollectionID()); ?>,
                        'ccm_token': '<?= $token->generate('block-cache-clear'); ?>'
                    },
                    dataType: 'json',
                    beforeSubmit: function() {
                        $.fn.dialog.showLoader();
                    },
                    success: function(r) {
                        if (r.error) {
                            ConcreteAlert.dialog(<?= t('Error'); ?>, '<div class="alert alert-danger">' + r.errors.join("<br>") + '</div>');
                        } else {
                            ConcreteAlert.notify({
                                'message': r.message,
                                'title': r.title
                            });
                            $.fn.dialog.closeTop();
                        }
                    },
                    complete: function() {
                        $.fn.dialog.hideLoader();
                    }
                });
            });
        </script>
    <?php
    } ?>
<?php
}
