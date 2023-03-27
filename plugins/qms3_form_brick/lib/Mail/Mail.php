<?php
declare(strict_types=1);

namespace QMS3\Brick\Mail;

use QMS3\Brick\Util\ReadonlyProps;


/**
 * @property-read    string[]    $tos
 * @property-read    string      $from
 * @property-read    string      $from_name
 * @property-read    string      $subject
 * @property-read    string      $body
 */
class Mail
{
    use ReadonlyProps;

    /** @var    string[] */
    private $tos;

    /** @var    string */
    private $from;

    /** @var    string */
    private $from_name;

    /** @var    string */
    private $subject;

    /** @var    string */
    private $body;

    /**
     * @param    string[]    tos
     * @param    string      from
     * @param    string      from_name
     * @param    string      subject
     * @param    string      body
     */
    public function __construct(
        array $tos,
        $from,
        $from_name,
        $subject,
        $body
    )
    {
        $this->tos       = $tos;
        $this->from      = $from;
        $this->from_name = $from_name;
        $this->subject   = $subject;
        $this->body      = $body;
    }

    /**
     * @return    string[]
     */
    protected function _tos()
    {
        return $this->tos;
    }

    /**
     * @return    string
     */
    protected function _from()
    {
        return $this->from;
    }

    /**
     * @return    string
     */
    protected function _from_name()
    {
        return $this->from_name;
    }

    /**
     * @return    string
     */
    protected function _subject()
    {
        return $this->subject;
    }

    /**
     * @return    string
     */
    protected function _body()
    {
        return $this->body;
    }

    /**
     * @return    mixed[]
     */
    public function to_array()
    {
        return [
            "tos"       => $this->tos,
            "from"      => $this->from,
            "from_name" => $this->from_name,
            "subject"   => $this->subject,
            "body"      => $this->body,
        ];
    }
}
