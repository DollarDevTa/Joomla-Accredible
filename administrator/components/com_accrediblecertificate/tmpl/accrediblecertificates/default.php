<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_accrediblecertificate
 *
 * @copyright   (C) 2024 Tatsiana Dev. All rights reserved. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Session\Session;

/** @var \Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $this->document->getWebAssetManager();
$wa->useScript('table.columns')
    ->useScript('multiselect');

$user      = $this->getCurrentUser();
$userId    = $user->get('id');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
$saveOrder = $listOrder == 'a.ordering';

if ($saveOrder && !empty($this->items)) {
    $saveOrderingUrl = 'index.php?option=com_accrediblecertificate&task=accrediblecertificates.saveOrderAjax&tmpl=component&' . Session::getFormToken() . '=1';
    HTMLHelper::_('draggablelist.draggable');
}
?>
<form action="<?php echo Route::_('index.php?option=com_accrediblecertificate&view=accrediblecertificates'); ?>" method="post" name="adminForm" id="adminForm">
    <div class="row">
        <div class="col-md-12">
            <div id="j-main-container" class="j-main-container">
                <?php if (empty($this->items)) : ?>
                    <div class="alert alert-info">
                        <span class="icon-info-circle" aria-hidden="true"></span><span class="visually-hidden"><?php echo Text::_('INFO'); ?></span>
                        <?php echo Text::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
                    </div>
                <?php else : ?>
                    <table class="table" id="bannerList">
                        <caption class="visually-hidden">
                            <?php echo Text::_('COM_ACCREDIBLECERTIFICATE_MANAGER_CREDENTIALS'); ?>,
                            <span id="orderedBy"><?php echo Text::_('JGLOBAL_SORTED_BY'); ?> </span>,
                            <span id="filteredBy"><?php echo Text::_('JGLOBAL_FILTERED_BY'); ?></span>
                        </caption>
                        <thead>
                            <tr>
							<td class="w-1 text-center">
                                    <?php echo HTMLHelper::_('grid.checkall'); ?>
                                </td>
                                <th scope="col" style="min-width:85px" class="w-5 text-center">
									<?php echo HTMLHelper::_('searchtools.sort', 'JSTATUS', 'a.published', $listDirn, $listOrder); ?>
								</th>
								<th scope="col" class="w-10">
									<?php echo HTMLHelper::_('searchtools.sort', 'COM_ACCREDIBLECERTIFICATE_LABEL_USER', 'a.user_id', $listDirn, $listOrder); ?>
								</th>
								<th scope="col" class="w-10">
									<?php echo HTMLHelper::_('searchtools.sort', 'COM_ACCREDIBLECERTIFICATE_LABEL_GROUP_ID', 'a.group_id', $listDirn, $listOrder); ?>
								</th>
								<th scope="col" class="w-20">
									<?php echo HTMLHelper::_('searchtools.sort', 'COM_ACCREDIBLECERTIFICATE_LABEL_URL_IMAGE', 'a.url_image', $listDirn, $listOrder); ?>
								</th>
								<th scope="col" class="w-20">
									<?php echo HTMLHelper::_('searchtools.sort', 'COM_ACCREDIBLECERTIFICATE_LABEL_URL_BADGE', 'a.url_badge', $listDirn, $listOrder); ?>
								</th>
								<th scope="col" class="w-10">
									<?php echo HTMLHelper::_('searchtools.sort', 'COM_ACCREDIBLECERTIFICATE_LABEL_CREATED', 'a.created', $listDirn, $listOrder); ?>
								</th>
								<th scope="col" class="w-5 d-none d-md-table-cell">
									<?php echo HTMLHelper::_('searchtools.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
								</th>
                            </tr>
                        </thead>
                        <tbody <?php if ($saveOrder) :
                            ?> class="js-draggable" data-url="<?php echo $saveOrderingUrl; ?>" data-direction="<?php echo strtolower($listDirn); ?>" data-nested="true"<?php
                               endif; ?>>
                            <?php foreach ($this->items as $i => $item) :
                                $ordering  = ($listOrder == 'ordering');
                                ?>
                                <tr class="row<?php echo $i % 2; ?>" data-draggable-group="<?php echo $item->catid; ?>">
                                    <td class="text-center">
                                        <?php echo HTMLHelper::_('grid.id', $i, $item->id, false, 'cid', 'cb', $item->id); ?>
                                    </td>
                                    <td class="text-center">
										<?php echo HTMLHelper::_('jgrid.published', $item->published, $i, 'accrediblecertificates.', true, 'cb'); ?>
									</td>
									<th scope="row" class="has-context">
										<a class="hasTooltip" href="<?php echo Route::_('index.php?option=com_accrediblecertificate&task=accrediblecertificate.edit&id=' . $item->id); ?>">
											<?php echo $this->escape($item->user_id); ?>
										</a>
									</th>
									<td class="">
										<?php echo $item->group_id; ?>
									</td>
									<td class="">
										<?php echo $item->url_image; ?>
									</td>
									<td class="">
										<?php echo $item->url_badge; ?>
									</td>
									<td class="">
										<?php echo $item->created; ?>
									</td>
									<td class="d-none d-md-table-cell">
										<?php echo $item->id; ?>
									</td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php // Load the pagination. ?>
                    <?php echo $this->pagination->getListFooter(); ?>
                    <?php // Load the batch processing form. ?>
                <?php endif; ?>
                <input type="hidden" name="task" value="">
                <input type="hidden" name="boxchecked" value="0">
                <?php echo HTMLHelper::_('form.token'); ?>
            </div>
        </div>
    </div>
</form>
