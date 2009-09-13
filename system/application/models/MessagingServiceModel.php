<?php
/**
 * Class MessagingServiceModel
 * @package Core
 * @subpackage Models
 */

/** DomainModel */
require_once BASEPATH . 'application/libraries/DomainModel.php';
/** SpeakerProfileModel */
require_once BASEPATH . 'application/models/SpeakerProfileModel.php';
/** MessagingServiceProviderModel */
require_once BASEPATH . 'application/models/MessagingServiceProviderModel.php';

/**
 * An account on a messaging network a speaker can be contacted at.
 * 
 * @author Mattijs Hoitink <mattijshoitink@gmail.com>
 */
class MessagingServiceModel extends DomainModel
{
    
    /**
     * @see DomainModel::$_table
     */
    protected $_table = 'speaker_messaging_services';
    
    /**
     * @see DomainModel::$_belongsTo
     */
    protected $_belongsTo = array (
        'SpeakerProfile' => array (
            'className' => 'SpeakerProfileModel',
            'referenceColumn' => 'speaker_profile_id',
            'foreignColumn' => 'id'
        )
    );
    
    /**
     * @see DomainModel::$_hasOne
     */
    protected $_hasOne = array (
        'Provider' => array (
            'className' => 'MessagingServiceProviderModel',
            'referenceColumn' => 'messaging_service_provider_id',
            'foreignColumn' => 'id',
            'cascadeOnDelete' => false
        )
    );
    
    /** **/
    
    /**
     * Returns the id for the provider.
     * @return int
     */
    public function getProviderId()
    {
        if(null !== $this->getProvider()) {
            return $this->getProvider()->getId();
        }
    }
    
    /**
     * Returns the name of the service provider.
     * @return string
     */
    public function getProviderName()
    {
        if(null !== $this->getProvider()) {
            return $this->getProvider()->getName();
        }
    }
    
    /**
     * Returns the url to the providers website.
     * @return string
     */
    public function getProviderUrl()
    {
        if(null !== $this->getProvider()) {
            return $this->getProvider()->getUrl();
        }
    }
    
    /**
     * Checks if the provider has an url to it's service.
     * @return boolean
     */
    public function providerHasUrl()
    {
        if(null !== $this->getProvider()) {
            $url = $this->getProviderUrl();
            return !empty($url);
        }
    }
}
