<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_accrediblecertificate
 *
 * @copyright   (C) 2024 Tatsiana Dev. All rights reserved. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\Component\Accrediblecertificate\Administrator\Helper;

use Joomla\CMS\Helper\ContentHelper;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Accredible Certificate component helper.
 *
 * @since  1.6
 */
class AccrediblecertificateHelper extends ContentHelper
{

	private $api_key;

	private $api_endpoint = "https://api.accredible.com/v1/";

	/**
	 * Set API Key
	 * @param String $key
	 * @return null
	 */
	public function setAPIKey($key) {
        $this->api_key = $key;
    }

    /**
     * Get API Key
     * @return String
     */
    public function getAPIKey() {
        return $this->api_key;
    }

    /**
     * Contruct API instance
     * @param String $api_key
     * @param boolean|null $test
     * @return null
     */
    public function __construct($api_key, $test = null){
        $this->setAPIKey($api_key);

        if (null !== $test) {
    	    //$this->api_endpoint = "https://staging.accredible.com/v1/";
    	}
    }

	/**
	 * Creates a Credential given an existing Group
	 * @param String $recipient_name
	 * @param String $recipient_email
	 * @param String $course_id
	 * @param Date|null $issued_on
	 * @param Date|null $expired_on
	 * @param stdObject|null $custom_attributes
	 * @return stdObject
	 */
	public function create_credential($recipient_name, $recipient_email, $course_id, $issued_on = null, $expired_on = null, $custom_attributes = null){

		$data = array(
		    "credential" => array(
		    	"group_id" => $course_id,
		        "recipient" => array(
		            "name" => $recipient_name,
		            "email" => $recipient_email
		        ),
		        "issued_on" => $issued_on,
		        "expired_on" => $expired_on,
		        "custom_attributes" => $custom_attributes
		    )
		);

		return json_decode($this->execAccredible($this->getAPIKey(), 'credentials', json_encode($data)), true);
	}
	
	public function execAccredible($key, $url, $params=array()){
		
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $this->api_endpoint.$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);

		if(!empty($params)){
			curl_setopt($ch, CURLOPT_POST, TRUE);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		}

		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		  "Content-Type: application/json",
		  "Authorization: Token token=".$key
		));
		$response = curl_exec($ch);
		curl_close($ch);

		return $response;
	}
}
