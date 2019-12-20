<?php

namespace Brajola\ForceLogin\Observer;

use Magento\Backend\Model\Auth\Session as AuthSession;
use Magento\Customer\Model\Session;
use Magento\Framework\App\ResponseFactory;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Json\Helper\Data as Json;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\UrlInterface;
use Brajola\ForceLogin\Helper\Data;
use Psr\Log\LoggerInterface;

/**
 * Class ForceLogin
 * @package Brajola\ForceLogin\Observer
 */
class ForceLogin implements ObserverInterface
{
    /**
     * @var Data
     */
    protected $_helper;

    /**
     * @var Session
     */
    protected $_customerSession;

    /**
     * @var AuthSession
     */
    protected $_adminSession;

    /**
     * @var ManagerInterface
     */
    protected $_messageManager;

    /**
     * @var UrlInterface
     */
    protected $_url;

    /**
     * @var ResponseFactory
     */
    protected $_responseFactory;

    /**
     * @var LoggerInterface
     */
    protected $_logger;

    /**
     * @var Json
     */
    protected $_json;

    /**
     * @var array
     */
    protected $_internal_whitelist;

    /**
     * @var Array
     */
    protected $_whitelist;

    public function __construct(
        Data $helper,
        ManagerInterface $messageManager,
        UrlInterface $url,
        ResponseFactory $responseFactory,
        Session $customerSession,
        AuthSession $adminSession,
        LoggerInterface $logger,
        Json $json
    )
    {
        $this->_helper = $helper;
        $this->_messageManager = $messageManager;
        $this->_url = $url;
        $this->_responseFactory = $responseFactory;
        $this->_customerSession = $customerSession;
        $this->_adminSession = $adminSession;
        $this->_logger = $logger;
        $this->_json = $json;

        $this->_internal_whitelist = [
            'customer_account_create',
            'customer_account_login',
            'customer_account_createpost',
            'customer_account_loginPost',
            'customer_section_load'
        ];

        $this->_whitelist = $this->_json->jsonDecode($this->_helper->getWhiteList());
    }

    /**
     * @param Observer $observer
     * @return $this|void
     */
    public function execute(Observer $observer)
    {
        if (!$this->_helper->isEnabled()) {
            return $this;
        }

        $actionName = $observer->getEvent()->getRequest()->getFullActionName();

        if ($this->isAdmin($actionName)) {
            return $this;
        }

        if (!$this->_customerSession->isLoggedIn()) {
            $this->_logger->notice('Occtoplus_ForceLogin::' . $actionName);

            if (in_array($actionName, $this->_internal_whitelist)) {
                return $this;
            } else {
                foreach ($this->_whitelist as $url) {
                    if ($actionName == $url['url']) {
                        return $this;
                    }
                }

                $this->_messageManager->addSuccessMessage(__('A login and a password are required.'));

                $redirectionUrl = $this->_url->getUrl('customer/account/login');
                $this->_responseFactory->create()->setRedirect($redirectionUrl)->sendResponse();

                exit;
            }
        }

        return $this;
    }

    /**
     * @param $actionName
     * @return bool
     */
    private function isAdmin($actionName)
    {
        if ($this->_adminSession->isLoggedIn() || $actionName == 'adminhtml_auth_login') {
            return true;
        }

        return false;
    }
}
