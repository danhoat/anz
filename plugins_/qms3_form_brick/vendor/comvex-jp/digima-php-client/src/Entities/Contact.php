<?php

namespace Digima\Entities;

use Digima\Support\Collection;
use Digima\Support\Dictionary;

class Contact
{
    /**
     * @var Dictionary
     */
    private $customFields;

    /**
     * @var Collection
     */
    private $groups;

    /**
     * @var string|null
     */
    private $personInChargeEmail;

    /**
     * @var Dictionary
     */
    private $staticFields;

    /**
     * @return void
     */
    public function __construct()
    {
        $this->staticFields = new Dictionary();
        $this->customFields = new Dictionary();
        $this->groups = new Collection();
    }

    /**
     * @return Dictionary
     */
    public function customFields()
    {
        return $this->customFields;
    }

    /**
     * @return Collection
     */
    public function groups()
    {
        return $this->groups;
    }

    /**
     * @return string
     */
    public function personInChargeEmail()
    {
        return $this->personInChargeEmail;
    }

    /**
     * @param string $personInChargeEmail
     * @return Contact
     */
    public function setPersonInChargeEmail($personInChargeEmail = null)
    {
        $this->personInChargeEmail = $personInChargeEmail;

        return $this;
    }

    /**
     * @return Dictionary
     */
    public function staticFields()
    {
        return $this->staticFields;
    }
}
