<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_accrediblecertificate
 *
 * @copyright   (C) 2024 Tatsiana Dev. All rights reserved. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\Component\Accrediblecertificate\Administrator\View\Accrediblecertificate;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\Component\Accrediblecertificate\Administrator\Model\AccrediblecertificateModel;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * View to edit a accrediblecertificate.
 *
 * @since  1.5
 */
class HtmlView extends BaseHtmlView
{
    /**
     * The Form object
     *
     * @var    Form
     * @since  1.5
     */
    protected $form;

    /**
     * The active item
     *
     * @var    object
     * @since  1.5
     */
    protected $item;

    /**
     * The model state
     *
     * @var    object
     * @since  1.5
     */
    protected $state;

    /**
     * Display the view
     *
     * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
     *
     * @return  void
     *
     * @since   1.5
     *
     * @throws  \Exception
     */
    public function display($tpl = null): void
    {
        /** @var BannerModel $model */
        $model       = $this->getModel();
        $this->form  = $model->getForm();
        $this->item  = $model->getItem();
        $this->state = $model->getState();

        // Check for errors.
        if (\count($errors = $this->get('Errors'))) {
            throw new GenericDataException(implode("\n", $errors), 500);
        }

        $this->addToolbar();

        parent::display($tpl);
    }

    /**
     * Add the page title and toolbar.
     *
     * @return  void
     *
     * @since   1.6
     * @throws  \Exception
     */
    protected function addToolbar(): void
    {
        Factory::getApplication()->getInput()->set('hidemainmenu', true);

        $user       = $this->getCurrentUser();
        $userId     = $user->id;
        $isNew      = ($this->item->id == 0);
        $toolbar    = Toolbar::getInstance();

        // Since we don't track these assets at the item level, use the category id.
        $canDo = ContentHelper::getActions('com_accrediblecertificate', 'accrediblecertificate', $this->item->id);

        ToolbarHelper::title($isNew ? Text::_('COM_ACCREDIBLECERTIFICATE_ADD') : Text::_('COM_ACCREDIBLECERTIFICATE_EDIT'), 'bookmark banners');

        // If not checked out, can save the item.
        if ( ($canDo->get('core.edit') || \count($user->getAuthorisedCategories('com_accrediblecertificate', 'core.create')) > 0)) {
            $toolbar->apply('accrediblecertificate.apply');
        }

        $saveGroup = $toolbar->dropdownButton('save-group');

        $saveGroup->configure(
            function (Toolbar $childBar) use ( $canDo, $user, $isNew) {
                // If not checked out, can save the item.
                if  ($canDo->get('core.edit') || \count($user->getAuthorisedCategories('com_accrediblecertificate', 'core.create')) > 0) {
                    $childBar->save('accrediblecertificate.save');

                    if ($canDo->get('core.create')) {
                        $childBar->save2new('accrediblecertificate.save2new');
                    }
                }

                // If an existing item, can save to a copy.
                if (!$isNew && $canDo->get('core.create')) {
                    $childBar->save2copy('accrediblecertificate.save2copy');
                }
            }
        );

        if (empty($this->item->id)) {
            $toolbar->cancel('accrediblecertificate.cancel', 'JTOOLBAR_CANCEL');
        } else {
            $toolbar->cancel('accrediblecertificate.cancel');

            if (ComponentHelper::isEnabled('com_contenthistory') && $this->state->params->get('save_history', 0) && $canDo->get('core.edit')) {
                $toolbar->versions('com_accrediblecertificate.accrediblecertificate', $this->item->id);
            }
        }

        $toolbar->divider();
    }
}
