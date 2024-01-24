<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_jlms_coursequicksearch
 *
 * @copyright   (C) 2008 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\Component\AccredibleCertificate\Administrator\Model;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Helper\TagsHelper;
use Joomla\CMS\Language\Associations;
use Joomla\CMS\Language\LanguageHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\CMS\String\PunycodeHelper;
use Joomla\CMS\Versioning\VersionableModelTrait;
use Joomla\Component\Categories\Administrator\Helper\CategoriesHelper;
use Joomla\Database\ParameterType;
use Joomla\Registry\Registry;
use Joomla\Utilities\ArrayHelper;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Item Model for a Subject.
 *
 * @since  1.6
 */
class AccredibleCertificateModel extends AdminModel
{
    use VersionableModelTrait;

    /**
     * The type alias for this content type.
     *
     * @var    string
     * @since  3.2
     */
    public $typeAlias = 'com_accrediblecertificate.accrediblecertificate';

    /**
     * The context used for the associations table
     *
     * @var    string
     * @since  3.4.4
     */
    protected $associationsContext = 'com_accrediblecertificate.item';

    /**
     * Name of the form
     *
     * @var string
     * @since  4.0.0
     */
    protected $formName = 'accrediblecertificate';

    protected function canDelete($record)
    {
        if (empty($record->id) || $record->published != -2) {
            return false;
        }

        return $this->getCurrentUser()->authorise('core.delete', 'com_accrediblecertificate.accrediblecertificate.' . (int) $record->id);
    }

    protected function canEditState($record)
    {
        // Default to component settings if category not known.
        return parent::canEditState($record);
    }

    /**
     * Method to get the row form.
     * @param   array    $data      Data for the form.
     * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
     * @return  Form|boolean  A Form object on success, false on failure
     * @since   1.6
     */
    public function getForm($data = [], $loadData = true)
    {
        Form::addFieldPath(JPATH_ADMINISTRATOR . '/components/com_accrediblecertificate/models/fields');

        // Get the form.
        $form = $this->loadForm('com_accrediblecertificate.' . $this->formName, $this->formName, ['control' => 'jform', 'load_data' => $loadData]);

        if (empty($form)) {
            return false;
        }
        return $form;
    }

    /**
     * Method to get a single record.
     *
     * @param   integer  $pk  The id of the primary key.
     *
     * @return  mixed  Object on success, false on failure.
     *
     * @since   1.6
     */
    public function getItem($pk = null)
    {
		
        $result = parent::getItem($pk);
		/*if ($result) {
			$db      = $this->getDatabase();
            $query   = $db->getQuery(true);
            $fieldId = (int) $result->id;
            $query->select($db->quoteName('id'))
                ->from($db->quoteName('#__accrediblecertificate'))
                ->where($db->quoteName('subject_id') . ' = :fieldid')
                ->bind(':fieldid', $fieldId, ParameterType::INTEGER);

            $db->setQuery($query);
            $result->assigned_states_ids = $db->loadColumn() ?: [0];
		
		}*/
        return $result;
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
        $app = Factory::getApplication();

        // Check the session for previously entered form data.
        $data = $app->getUserState('com_accrediblecertificate.edit.accrediblecertificate.data', []);

        if (empty($data)) {
            $data = $this->getItem();
        }

        $this->preprocessData('com_accrediblecertificate.accrediblecertificate', $data);

        return $data;
    }

    /**
     * Method to save the form data.
     *
     * @param   array  $data  The form data.
     *
     * @return  boolean  True on success.
     *
     * @since   3.0
     */
    public function save($data)
    {
		$input = Factory::getApplication()->getInput();
       
        if (!parent::save($data)) {
            return false;
        }

       
		$db = $this->getDatabase();
		$id = (int) $this->getState('accrediblecertificate.id');
        // First delete all assigned states
        $query = $db->getQuery(true);
        $query->delete('#__accrediblecertificate')
            ->where($db->quoteName('id') . ' = :fieldid')
            ->bind(':fieldid', $id, ParameterType::INTEGER);

        $db->setQuery($query);
        $db->execute();

     
		//FieldsHelper::clearFieldsCache();

        return true;
    }

    /**
     * Preprocess the form.
     *
     * @param   Form    $form   Form object.
     * @param   object  $data   Data object.
     * @param   string  $group  Group name.
     *
     * @return  void
     *
     * @since   3.0.3
     */
    protected function preprocessForm(Form $form, $data, $group = 'content')
    {
        parent::preprocessForm($form, $data, $group);
    }

 
}
