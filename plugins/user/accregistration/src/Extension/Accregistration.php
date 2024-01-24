<?php

namespace Joomla\Plugin\User\Accregistration\Extension;

use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Date\Date;
use Joomla\Database\DatabaseAwareTrait;
use Joomla\Component\AccredibleCertificate\Administrator\Helper\AccredibleCertificateHelper;
use Joomla\CMS\Component\ComponentHelper;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects


final class Accregistration extends CMSPlugin
{
	use DatabaseAwareTrait;

    public function onUserAfterSave($data, $isNew, $result, $error): void
    {
        if ($isNew && $result ) {

			$config   = ComponentHelper::getParams('com_accrediblecertificate');
			$default_group_id = $config->get('default_group_id', '546130');
			
			$api = new AccredibleCertificateHelper($config->get('api_key', 'ade377f959a7f522c67a948772f02bc6'), true);
			$new_credential = $api->create_credential($data['username'], $data['email1'], $default_group_id);

			$db = $this->getDatabase();
            $query = $db->getQuery(true)->insert($db->quoteName('#__accrediblecertificate'));

			$query->columns([
						$db->quoteName('user_id'), 
						$db->quoteName('group_id'), 
						$db->quoteName('url_image'),
						$db->quoteName('url_badge'),
						$db->quoteName('created'),
						$db->quoteName('published')
					])
					->values(
						$db->quote($data['id']) . ', ' . 
						$db->quote($default_group_id) . ', ' . 
						$db->quote($new_credential['credential']['seo_image']) . ', ' . 
						$db->quote($new_credential['credential']['badge']['image']['preview']) . ', "' . 
						date('Y-m-d H:i:s'). '", ' . 
						( !empty($new_credential['credential']['seo_image']) ? 1 : 0 )
					);
			
            $db->setQuery($query);
            $db->execute();
        }
    }
}
