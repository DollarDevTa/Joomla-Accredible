<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_accrediblecertificate
 *
 * @copyright   (C) 2024 Tatsiana Dev. All rights reserved. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $this->document->getWebAssetManager();
$wa->useScript('keepalive')
    ->useScript('form.validate');

$app = Factory::getApplication();
$input = $app->getInput();

$this->useCoreUI = true;

?>

<form action="<?php echo Route::_('index.php?option=com_accrediblecertificate&layout=edit&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="accrediblecertificate-form" aria-label="<?php echo Text::_('COM_ACCREDIBLECERTIFICATE_' . ((int) $this->item->id === 0 ? 'NEW' : 'EDIT'), true); ?>" class="form-validate">
<?php echo LayoutHelper::render('joomla.edit.title_alias', $this); ?>
<div class="main-card">
        <?php echo HTMLHelper::_('uitab.startTabSet', 'myTab', ['active' => 'general', 'recall' => true, 'breakpoint' => 768]); ?>
        <?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'general', Text::_('COM_ACCREDIBLECERTIFICATE_FIELDSET_GENERAL', true)); ?>
        <div class="row">
            <div class="col-lg-6">
                <?php echo $this->form->renderField('user_id'); ?>
                <?php echo $this->form->renderField('group_id'); ?>
                <?php echo $this->form->renderField('url_image'); ?>
                <?php echo $this->form->renderField('url_badge'); ?>
				<?php echo $this->form->renderField('created'); ?>
                <?php echo $this->form->renderField('published'); ?>
                <?php $this->set('fields', null); ?>
            </div>
            
        </div>
        <?php echo HTMLHelper::_('uitab.endTab'); ?>
    </div>
    <?php echo HTMLHelper::_('uitab.endTabSet'); ?>
    <?php echo $this->form->getInput('context'); ?>
    <input type="hidden" name="task" value="">
    <?php echo HTMLHelper::_('form.token'); ?>
</form>
