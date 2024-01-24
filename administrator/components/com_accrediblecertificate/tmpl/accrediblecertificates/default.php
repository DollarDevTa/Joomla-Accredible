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
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
?>
<form action="<?php echo Route::_('index.php?option=com_accrediblecertificate'); ?>" method="post" name="adminForm" id="adminForm">
	<?php //echo LayoutHelper::render('joomla.searchtools.default', array('view' => $this)); ?>
	<?php if (empty($this->items)) : ?>
		<div class="alert alert-info">
			<span class="fa fa-info-circle" aria-hidden="true"></span><span class="sr-only"><?php echo Text::_('INFO'); ?></span>
			<?php echo Text::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
		</div>
	<?php else : ?>
		<table class="table" id="mywalksList">
			<thead>
				<tr>
					
					<th scope="col" style="min-width:85px" class="w-5 text-center">
						<?php echo HTMLHelper::_('searchtools.sort', 'JSTATUS', 'a.published', $listDirn, $listOrder); ?>
					</th>
					<th scope="col" class="w-20">
						<?php echo HTMLHelper::_('searchtools.sort', 'COM_ACCREDIBLECERTIFICATE_LABEL_USER', 'a.user_id', $listDirn, $listOrder); ?>
					</th>
					<th scope="col" class="w-25">
						<?php echo HTMLHelper::_('searchtools.sort', 'COM_ACCREDIBLECERTIFICATE_LABEL_GROUP_ID', 'a.group_id', $listDirn, $listOrder); ?>
					</th>
					<th scope="col" class="w-10">
						<?php echo HTMLHelper::_('searchtools.sort', 'COM_ACCREDIBLECERTIFICATE_LABEL_URL_IMAGE', 'a.url_image', $listDirn, $listOrder); ?>
					</th>
					<th scope="col" class="w-10">
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
			<tbody>
			<?php
			$n = count($this->items);
			foreach ($this->items as $i => $item) :
				?>
				<tr class="row<?php echo $i % 2; ?>">
					
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

		<?php // load the pagination. ?>
		<?php echo $this->pagination->getListFooter(); ?>

	<?php endif; ?>
	<input type="hidden" name="task" value="">
	<input type="hidden" name="boxchecked" value="0">
	<?php echo HTMLHelper::_('form.token'); ?>
</form>
