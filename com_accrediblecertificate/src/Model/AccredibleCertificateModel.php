<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_accrediblecertificate
 *
 * @copyright   (C) 2024 Tatsiana Dev. All rights reserved. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\Component\Accrediblecertificate\Administrator\Model;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\CMS\Table\Table;
use Joomla\CMS\Table\TableInterface;
use Joomla\CMS\Versioning\VersionableModelTrait;
use Joomla\Database\ParameterType;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Accrediblecertificate model.
 *
 * @since  1.6
 */
class AccrediblecertificateModel extends AdminModel
{
    use VersionableModelTrait;

    /**
     * The prefix to use with controller messages.
     *
     * @var    string
     * @since  1.6
     */
    protected $text_prefix = 'COM_ACCREDIBLECERTIFICATE';

    /**
     * The type alias for this content type.
     *
     * @var    string
     * @since  3.2
     */
    public $typeAlias = 'com_accrediblecertificate.accrediblecertificate';

    /**
     * Method to get the record form.
     *
     * @param   array    $data      Data for the form. [optional]
     * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not. [optional]
     *
     * @return  Form|boolean  A Form object on success, false on failure
     *
     * @since   1.6
     */
    public function getForm($data = [], $loadData = true)
    {
        // Get the form.
        $form = $this->loadForm('com_accrediblecertificate.accrediblecertificate', 'accrediblecertificate', ['control' => 'jform', 'load_data' => $loadData]);

        if (empty($form)) {
            return false;
        }

        return $form;
    }

    /**
     * Method to get the data that should be injected in the form.
     *
     * @return  mixed  The data for the form.
     *
     * @since   1.6
     */
    protected function loadFormData()
    {
        // Check the session for previously entered form data.
        $app  = Factory::getApplication();
        $data = $app->getUserState('com_accrediblecertificate.edit.accrediblecertificate.data', []);

        if (empty($data)) {
            $data = $this->getItem();

            // Prime some default values.
            if ($this->getState('accrediblecertificate.id') == 0) {
                $filters     = (array) $app->getUserState('com_accrediblecertificate.accrediblecertificates.filter');
                $filterCatId = $filters['category_id'] ?? null;

                $data->set('catid', $app->getInput()->getInt('catid', $filterCatId));
            }
        }

        $this->preprocessData('com_accrediblecertificate.accrediblecertificate', $data);

        return $data;
    }

    /**
     * Allows preprocessing of the Form object.
     *
     * @param   Form    $form   The form object
     * @param   array   $data   The data to be merged into the form object
     * @param   string  $group  The plugin group to be executed
     *
     * @return  void
     *
     * @since    3.6.1
     */
    protected function preprocessForm(Form $form, $data, $group = 'content')
    {
        parent::preprocessForm($form, $data, $group);
    }

}
