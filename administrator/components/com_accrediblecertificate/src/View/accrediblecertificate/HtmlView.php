<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_accrediblecertificate
 *
 * @copyright   (C) 2024 Tatsiana Dev. All rights reserved. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\Component\AccredibleCertificate\Administrator\View\AccredibleCertificate;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Language\Associations;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * View to edit a contact.
 *
 * @since  1.6
 */
class HtmlView extends BaseHtmlView
{
    /**
     * The Form object
     *
     * @var  \Joomla\CMS\Form\Form
     */
    protected $form;

    /**
     * The active item
     *
     * @var  object
     */
    protected $item;

    /**
     * The model state
     *
     * @var  \Joomla\Registry\Registry
     */
    protected $state;

    /**
     * Display the view.
     *
     * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
     *
     * @return  void
     */
    public function display($tpl = null)
    {
        // Initialise variables.
        $this->form  = $this->get('Form');
        $this->item  = $this->get('Item');
        $this->state = $this->get('State');

        // Check for errors.
        if (\count($errors = $this->get('Errors'))) {
            throw new GenericDataException(implode("\n", $errors), 500);
        }
   

        if ($this->getLayout() !== 'modal') {
            $this->addToolbar();
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
    protected function addToolbar()
    {
        Factory::getApplication()->getInput()->set('hidemainmenu', true);

        $user       = $this->getCurrentUser();
        $userId     = $user->id;
        $isNew      = ($this->item->id == 0);
        $toolbar    = Toolbar::getInstance();

        // Since we don't track these assets at the item level, use the category id.
        $canDo = true;

        ToolbarHelper::title($isNew ? Text::_('COM_ACCREDIBLECERTIFICATE_ADD') : Text::_('COM_ACCREDIBLECERTIFICATE_EDIT'), 'address-book contact');


        // Since it's an existing record, check the edit permission, or fall back to edit own if the owner.
        $itemEditable = true;//$canDo->get('core.edit') || ($canDo->get('core.edit.own') && $this->item->created_by == $userId);

        // Can't save the record if it's checked out and editable
        if ( $itemEditable) {
            $toolbar->apply('accrediblecertificate.apply');
        }

        $saveGroup = $toolbar->dropdownButton('save-group');

        $saveGroup->configure(
            function (Toolbar $childBar) use ($itemEditable, $canDo, $user) {
                // Can't save the record if it's checked out and editable
                if ( $itemEditable) {
                    $childBar->save('accrediblecertificate.save');
                    // We can save this record, but check the create permission to see if we can return to make a new one.
                }

                // If checked out, we can still save2menu
                if ($user->authorise('core.create', 'com_menus.menu')) {
                    $childBar->save('accrediblecertificate.save2menu', 'JTOOLBAR_SAVE_TO_MENU');
                }
            }
        );

        $toolbar->cancel('accrediblecertificate.cancel');

        if (ComponentHelper::isEnabled('com_contenthistory') && $this->state->params->get('save_history', 0) && $itemEditable) {
            $toolbar->versions('com_accrediblecertificate.accrediblecertificate', $this->item->id);
        }

        if (Associations::isEnabled() && ComponentHelper::isEnabled('com_associations')) {
            $toolbar->standardButton('accrediblecertificate', 'JTOOLBAR_ASSOCIATIONS', 'accrediblecertificate.editAssociations')
                ->icon('icon-contract')
                ->listCheck(false);
        }
        $toolbar->divider();
    }
}
