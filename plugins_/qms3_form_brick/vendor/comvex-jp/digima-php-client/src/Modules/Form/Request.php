<?php

namespace Digima\Modules\Form;

use Digima\Constants;
use Digima\Http\Requests\RequestFactory;
use Digima\Http\Requests\RequestInterface;

class Request
{
    const MODE_REGISTRATION = 'mode_registration';
    const MODE_VALIDATION = 'mode_validation';

    const PROPERTY_CODE = 'code';
    const PROPERTY_CONTACT = 'contact';
    const PROPERTY_CUSTOM_FIELDS = 'custom_fields';
    const PROPERTY_FORM = 'form';
    const PROPERTY_GROUP_NAMES = 'group_names';
    const PROPERTY_PAGE_TITLE = 'page_title';
    const PROPERTY_PAGE_URL = 'page_url';
    const PROPERTY_PERSON_IN_CHARGE_EMAIL = 'person_in_charge_email';
    const PROPERTY_SUBMITTER_IP_ADDRESS = 'submitter_ip_address';
    const PROPERTY_SUBMITTER_USER_AGENT = 'submitter_user_agent';
    const PROPERTY_VALIDATE_ONLY = 'validate_only';
    const PROPERTY_VISIT_COOKIE = 'visit_cookie';

    /**
     * @var Form|null
     */
    private $form;

    /**
     * @var string
     */
    private $mode = self::MODE_REGISTRATION;

    /**
     * @param Form $form
     * @param string $mode
     * @return void
     */
    public function __construct(Form $form, $mode = self::MODE_REGISTRATION)
    {
        $this->setForm($form);
        $this->setMode($mode);
    }

    /**
     * @return Form
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @return string
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * @return array
     */
    public function makeProperties()
    {
        return array(
            self::PROPERTY_FORM    => $this->makeFormProperties(),
            self::PROPERTY_CONTACT => $this->makeContactProperties(),
        );
    }

    /**
     * @param array $options
     * @return Response
     */
    public function send(array $options = array())
    {
        $httpRequest = RequestFactory::create();

        $url = array_key_exists('request_url', $options)
            ? $options['request_url']
            : Constants::WEB_FORM_SUBMIT_ENDPOINT_URL;

        $httpRequest->setHttpMethod(RequestInterface::HTTP_POST)
                    ->setUrl($url)
                    ->setHeader('Authorization', $this->makeAuthorization())
                    ->setHeader('Accept-Language', 'ja')
                    ->setProperties($this->makeProperties());

        $httpResponse = $httpRequest->send();

        $response = new Response();

        $response->setHttpResponse($httpResponse);

        $response->parse();

        return $response;
    }

    /**
     * @param Form $form
     * @return Request
     */
    public function setForm($form = null)
    {
        $this->form = $form;

        return $this;
    }

    /**
     * @param string $mode
     * @return Request
     */
    public function setMode($mode)
    {
        $this->mode = $mode;

        return $this;
    }

    /**
     * @return string
     */
    private function makeAuthorization()
    {
        $auth = $this->form->getAuthManager();

        return base64_encode($auth->accountCode.'-'.$this->form->getCode());
    }

    /**
     * @return array
     */
    private function makeContactProperties()
    {
        $contact = $this->form->contact();

        $properties = $contact->staticFields()->all();

        if (! $contact->customFields()->isEmpty()) {
            $properties[self::PROPERTY_CUSTOM_FIELDS] = $contact->customFields()->all();
        }

        if (! $contact->groups()->isEmpty()) {
            $properties[self::PROPERTY_GROUP_NAMES] = $contact->groups()->toArray();
        }

        if ($contact->personInChargeEmail()) {
            $properties[self::PROPERTY_PERSON_IN_CHARGE_EMAIL] = $contact->personInChargeEmail();
        }

        return $properties;
    }

    /**
     * @return array
     */
    private function makeFormProperties()
    {
        return array(
            self::PROPERTY_CODE                 => $this->form->getCode(),
            self::PROPERTY_PAGE_TITLE           => $this->form->getPageTitle(),
            self::PROPERTY_PAGE_URL             => $this->form->getPageUrl(),
            self::PROPERTY_SUBMITTER_IP_ADDRESS => $this->form->getSubmitterIpAddress(),
            self::PROPERTY_SUBMITTER_USER_AGENT => $this->form->getSubmitterUserAgent(),
            self::PROPERTY_VALIDATE_ONLY        => $this->mode === self::MODE_VALIDATION,
            self::PROPERTY_VISIT_COOKIE         => $this->form->getCookie(),
        );
    }
}
