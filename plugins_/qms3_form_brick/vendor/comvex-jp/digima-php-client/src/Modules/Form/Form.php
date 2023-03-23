<?php

namespace Digima\Modules\Form;

use Digima\AuthManager;
use Digima\Constants;
use Digima\Entities\Contact;
use Digima\Modules\AbstractModule;
use Digima\Support\Request as RequestHelper;

class Form extends AbstractModule
{
    /**
     * @var string|null
     */
    private $code;

    /**
     * @var Contact
     */
    private $contact;

    /**
     * @var string|null
     */
    private $cookie;

    /**
     * @var string|null
     */
    private $pageTitle;

    /**
     * @var string|null
     */
    private $pageUrl;

    /**
     * @var Response
     */
    private $response;

    /**
     * @var string
     */
    private $submitterIpAddress;

    /**
     * @var string
     */
    private $submitterUserAgent;

    /**
     * @param AuthManager $authManager
     * @param string $code The Web Form code
     * @return void
     */
    public function __construct(AuthManager $authManager, $code)
    {
        parent::__construct($authManager);

        $this->reset();

        $this->code = $code;
    }

    /**
     * @return Contact
     */
    public function contact()
    {
        if (! $this->contact) {
            $this->contact = new Contact();
        }

        return $this->contact;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getCookie()
    {
        return $this->cookie;
    }

    /**
     * @param string $fieldName
     * @return array
     */
    public function getCustomFieldErrors($fieldName)
    {
        if (! $this->response) {
            return array();
        }

        return $this->response->getCustomFieldErrors($fieldName);
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        if (! $this->response) {
            return array();
        }

        return $this->response->getErrors();
    }

    /**
     * @return string
     */
    public function getPageTitle()
    {
        return $this->pageTitle;
    }

    /**
     * @return string
     */
    public function getPageUrl()
    {
        return $this->pageUrl;
    }

    /**
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param string $fieldName
     * @return array
     */
    public function getStaticFieldErrors($fieldName)
    {
        if (! $this->response) {
            return array();
        }

        return $this->response->getStaticFieldErrors($fieldName);
    }

    /**
     * @return string
     */
    public function getSubmitterIpAddress()
    {
        return $this->submitterIpAddress;
    }

    /**
     * @return string
     */
    public function getSubmitterUserAgent()
    {
        return $this->submitterUserAgent;
    }

    /**
     * @param string $fieldName
     * @return bool
     */
    public function hasCustomFieldError($fieldName)
    {
        if (! $this->response) {
            return false;
        }

        return $this->response->hasCustomFieldErrors($fieldName);
    }

    /**
     * @return bool
     */
    public function hasError()
    {
        if (! $this->response) {
            return false;
        }

        return $this->response->hasError();
    }

    /**
     * @param string $fieldName
     * @return bool
     */
    public function hasStaticFieldError($fieldName)
    {
        if (! $this->response) {
            return false;
        }

        return $this->response->hasStaticFieldErrors($fieldName);
    }

    /**
     * @return Form
     */
    public function reset()
    {
        $this->pageTitle = null;
        $this->pageUrl = RequestHelper::getPageUrl();
        $this->cookie = null;
        $this->contact = null;
        $this->cookie = $this->retrieveCookie();
        $this->response = null;
        $this->submitterIpAddress = RequestHelper::getRemoteIpAddress();
        $this->submitterUserAgent = null;

        return $this;
    }

    /**
     * @param string $code
     * @return Form
     */
    public function setCode($code = null)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @param string $cookie
     * @return Form
     */
    public function setCookie($cookie = null)
    {
        $this->cookie = $cookie;

        return $this;
    }

    /**
     * @param string $pageTitle
     * @return Form
     */
    public function setPageTitle($pageTitle = null)
    {
        $this->pageTitle = $pageTitle;

        return $this;
    }

    /**
     * @param string $pageUrl
     * @return Form
     */
    public function setPageUrl($pageUrl = null)
    {
        $this->pageUrl = $pageUrl;

        return $this;
    }

    /**
     * @param string $submitterIpAddress
     * @return Form
     */
    public function setSubmitterIpAddress($submitterIpAddress = null)
    {
        $this->submitterIpAddress = $submitterIpAddress;

        return $this;
    }

    /**
     * @param string $submitterUserAgent
     * @return Form
     */
    public function setSubmitterUserAgent($submitterUserAgent = null)
    {
        $this->submitterUserAgent = $submitterUserAgent;

        return $this;
    }

    /**
     * @param array $options
     * @return Response
     */
    public function submit(array $options = array())
    {
        return $this->sendRequest(false, $options);
    }

    /**
     * @param array $options
     * @return Response
     */
    public function validate(array $options = array())
    {
        return $this->sendRequest(true, $options);
    }

    /**
     * @return string|null
     */
    private function retrieveCookie()
    {
        $cookieName = Constants::WEB_TRACKING_COOKIE_NAME;

        if (! isset($_COOKIE[$cookieName])) {
            return null;
        }

        return $_COOKIE[$cookieName];
    }

    /**
     * @param bool $validateOnly
     * @param array $options
     * @return Response
     */
    private function sendRequest($validateOnly = false, array $options = array())
    {
        $requestMode = $validateOnly ? Request::MODE_VALIDATION : Request::MODE_REGISTRATION;

        $request = new Request($this, $requestMode);

        $this->response = $request->send($options);

        return $this->response;
    }
}
