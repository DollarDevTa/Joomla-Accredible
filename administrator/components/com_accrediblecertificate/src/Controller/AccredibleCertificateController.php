<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_accrediblecertificate
 *
 * @copyright   (C) 2024 Tatsiana Dev. All rights reserved. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\Component\AccredibleCertificate\Administrator\Controller;

use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Versioning\VersionableControllerTrait;
use Joomla\Utilities\ArrayHelper;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Controller for a single credential
 *
 * @since  1.6
 */
class AccredibleCertificateController extends FormController
{
    use VersionableControllerTrait;

    /**
     * Method override to check if you can add a new record.
     *
     * @param   array  $data  An array of input data.
     *
     * @return  boolean
     *
     * @since   1.6
     */
    protected function allowAdd($data = [])
    {
        $categoryId = ArrayHelper::getValue($data, 'catid', $this->input->getInt('filter_category_id'), 'int');

        if ($categoryId) {
            // If the category has been passed in the URL check it.
            return $this->app->getIdentity()->authorise('core.create', $this->option . '.category.' . $categoryId);
        }

        // In the absence of better information, revert to the component permissions.
        return parent::allowAdd($data);
    }

    /**
     * Method override to check if you can edit an existing record.
     *
     * @param   array   $data  An array of input data.
     * @param   string  $key   The name of the key for the primary key.
     *
     * @return  boolean
     *
     * @since   1.6
     */
    protected function allowEdit($data = [], $key = 'id')
    {
        $recordId = (int) isset($data[$key]) ? $data[$key] : 0;

        // Since there is no asset tracking, fallback to the component permissions.
       
            return parent::allowEdit($data, $key);
    }

    /**
     * Method to run batch operations.
     *
     * @param   object  $model  The model.
     *
     * @return  boolean   True if successful, false otherwise and internal error is set.
     *
     * @since   2.5
     */
   /* public function batch($model = null)
    {
        $this->checkToken();

        // Set the model
        
        $model = $this->getModel('Contact', 'Administrator', []);

        // Preset the redirect
        $this->setRedirect(Route::_('index.php?option=com_contact&view=contacts' . $this->getRedirectToListAppend(), false));

        return parent::batch($model);
    }*/

    /**
     * Function that allows child controller access to model data
     * after the data has been saved.
     *
     * @param   BaseDatabaseModel  $model      The data model object.
     * @param   array              $validData  The validated data.
     *
     * @return  void
     *
     * @since   4.1.0
     */
    /*protected function postSaveHook(BaseDatabaseModel $model, $validData = [])
    {
        if ($this->getTask() === 'save2menu') {
            $editState = [];

            $id = $model->getState('contact.id');

            $link = 'index.php?option=com_contact&view=contact';
            $type = 'component';

            $editState['id']            = $id;
            $editState['link']          = $link;
            $editState['title']         = $model->getItem($id)->name;
            $editState['type']          = $type;
            $editState['request']['id'] = $id;

            $this->app->setUserState(
                'com_menus.edit.item',
                [
                    'data' => $editState,
                    'type' => $type,
                    'link' => $link,
                ]
            );

            $this->setRedirect(Route::_('index.php?option=com_menus&view=item&client_id=0&menutype=mainmenu&layout=edit', false));
        }
    }*/
}
