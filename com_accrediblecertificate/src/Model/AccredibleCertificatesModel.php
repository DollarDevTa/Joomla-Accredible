<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_accrediblecertificate
 *
 * @copyright   (C) 2024 Tatsiana Dev. All rights reserved. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\Component\Accrediblecertificate\Administrator\Model;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\Table\Table;
use Joomla\Database\ParameterType;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Methods supporting a list of accrediblecertificate records.
 *
 * @since  1.6
 */
class AccrediblecertificatesModel extends ListModel
{
    /**
     * Constructor.
     *
     * @param   array  $config  An optional associative array of configuration settings.
     *
     * @since   1.6
     */
    public function __construct($config = [])
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = [
                'id', 'a.id',
                'user_id', 'a.user_id',
                'group_id', 'a.group_id',
                'url_image', 'a.url_image',
                'url_badge', 'a.url_badge',
				'created',
                'published'				
            ];
        }
        parent::__construct($config);
    }

    /**
     * Build an SQL query to load the list data.
     *
     * @return  \Joomla\Database\DatabaseQuery
     *
     * @since   1.6
     */
    protected function getListQuery()
    {
        $db    = $this->getDatabase();
        $query = $db->getQuery(true);

       // Select the required fields from the table.
        $query->select(
            $db->quoteName(
                explode(
                    ', ',
                    $this->getState(
                        'list.select',
                        'a.id, a.user_id, a.group_id, a.url_image, a.url_badge, a.created, a.published' 
                    )
                )
            )
        );
        $query->from($db->quoteName('#__accrediblecertificate', 'a'));
        return $query;
    }

    /**
     * Returns a reference to the a Table object, always creating it.
     *
     * @param   string  $type    The table type to instantiate
     * @param   string  $prefix  A prefix for the table class name. Optional.
     * @param   array   $config  Configuration array for model. Optional.
     *
     * @return  Table  A Table object
     *
     * @since   1.6
     */
    public function getTable($type = 'Accrediblecertificate', $prefix = 'Administrator', $config = [])
    {
        return parent::getTable($type, $prefix, $config);
    }
}
