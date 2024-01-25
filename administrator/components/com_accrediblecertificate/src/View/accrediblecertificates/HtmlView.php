<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_accrediblecertificate
 *
 * @copyright   (C) 2024 Tatsiana Dev. All rights reserved. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\Component\Accrediblecertificate\Administrator\View\Accrediblecertificates;

use Joomla\CMS\Form\Form;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Pagination\Pagination;
use Joomla\CMS\Toolbar\Button\DropdownButton;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\Component\Accrediblecertificate\Administrator\Model\AccrediblecertificatesModel;
use Joomla\Registry\Registry;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * View class for a list of accrediblecertificates.
 *
 * @since  1.6
 */
class HtmlView extends BaseHtmlView
{
    /**
     * The search tools form
     *
     * @var    Form
     * @since  1.6
     */
    public $filterForm;

    /**
     * The active search filters
     *
     * @var    array
     * @since  1.6
     */
    public $activeFilters = [];

    /**
     * Category data
     *
     * @var    array
     * @since  1.6
     */
    protected $categories = [];

    /**
     * An array of items
     *
     * @var    array
     * @since  1.6
     */
    protected $items = [];

    /**
     * The pagination object
     *
     * @var    Pagination
     * @since  1.6
     */
    protected $pagination;

    /**
     * The model state
     *
     * @var    Registry
     * @since  1.6
     */
    protected $state;

    /**
     * Is this view an Empty State
     *
     * @var  boolean
     * @since 4.0.0
     */
    private $isEmptyState = false;

    /**
     * Method to display the view.
     *
     * @param   string  $tpl  A template file to load. [optional]
     *
     * @return  void
     *
     * @since   1.6
     * @throws  \Exception
     */
    public function display($tpl = null): void
    {
       
        $model               = $this->getModel();
        $this->items         = $model->getItems();
        $this->pagination    = $model->getPagination();
        $this->state         = $model->getState();
        $this->filterForm    = $model->getFilterForm();
        $this->activeFilters = $model->getActiveFilters();

        $this->addToolbar();

        // We do not need to filter by language when multilingual is disabled
        if (!Multilanguage::isEnabled()) {
            unset($this->activeFilters['language']);
            //$this->filterForm->removeField('language', 'filter');
        }

        parent::display($tpl);
    }

    /**
     * Add the page title and toolbar.
     *
     * @return  void
     *
     * @since   1.6
     */
    protected function addToolbar(): void
    {
        $canDo   = ContentHelper::getActions('com_accrediblecertificate', 'category', $this->state->get('filter.category_id'));
        $user    = $this->getCurrentUser();
        $toolbar = Toolbar::getInstance();

        ToolbarHelper::title(Text::_('COM_ACCREDIBLECERTIFICATE_MANAGER_CREDENTIALS'), 'bookmark accrediblecertificates');

        if ($canDo->get('core.create') || \count($user->getAuthorisedCategories('com_accrediblecertificate', 'core.create')) > 0) {
            $toolbar->addNew('accrediblecertificate.add');
        }

        if (!$this->isEmptyState && ($canDo->get('core.edit.state') || ($this->state->get('filter.published') == -2 && $canDo->get('core.delete')))) {
            /** @var  DropdownButton $dropdown */
            $dropdown = $toolbar->dropdownButton('status-group', 'JTOOLBAR_CHANGE_STATUS')
                ->toggleSplit(false)
                ->icon('icon-ellipsis-h')
                ->buttonClass('btn btn-action')
                ->listCheck(true);

            $childBar = $dropdown->getChildToolbar();

            if ($canDo->get('core.edit.state')) {
                if ($this->state->get('filter.published') != 2) {
                    $childBar->publish('accrediblecertificates.publish')->listCheck(true);

                    $childBar->unpublish('accrediblecertificates.unpublish')->listCheck(true);
                }
            }
          
        }
        if ($user->authorise('core.admin', 'com_accrediblecertificate') || $user->authorise('core.options', 'com_accrediblecertificate')) {
            $toolbar->preferences('com_accrediblecertificate');
        }
    }
}
