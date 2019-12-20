<?php

namespace Brajola\ForceLogin\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

/**
 * Class Data
 * @package Brajola\ForceLogin\Helper
 */
class Data extends AbstractHelper
{
    const XML_SETTINGS = 'brajola_forcelogin/';

    /**
     * @param $config_path
     * @return mixed
     */
    protected function getConfig($config_path)
    {
        return $this->scopeConfig->getValue(
            $config_path,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return (bool)$this->getConfig(self::XML_SETTINGS . 'general/enabled');
    }

    /**
     * @return mixed
     */
    public function getWhiteList()
    {
        return $this->getConfig(self::XML_SETTINGS . 'whitelist/urls');
    }
}
