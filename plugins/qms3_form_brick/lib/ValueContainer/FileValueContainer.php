<?php
declare(strict_types=1);

namespace QMS3\Brick\ValueContainer;

use ArrayIterator;
use QMS3\Brick\Enum\UploadStatus;
use QMS3\Brick\Util\ReadonlyProps;
use QMS3\Brick\ValueContainer\ValueContainerInterface;
use QMS3\Brick\ValueItem\FileValueItem;


/**
 * @property-read    string          $value
 * @property-read    bool            $is_default
 * @property-read    string          filename
 * @property-read    int             filesize
 * @property-read    string          mime_type
 * @property-read    int             error_no
 * @property-read    UploadStatus    status
 * @property-read    string          url
 */
class FileValueContainer implements ValueContainerInterface
{
    use ReadonlyProps;

    /** @var    FileValueItem */
    private $item;

    /**
     * @param    FileValueItem    $item
     */
    public function __construct(FileValueItem $item)
    {
        $this->item  = $item;
    }

    /**
     * @return    string
     */
    public function __toString()
    {
        return (string) $this->item;
    }

    // ====================================================================== //

    /**
     * @return    FileValueItem
     */
    protected function _value()
    {
        return $this->item;
    }

    /**
     * @return    bool
     */
    protected function _is_default()
    {
        return false;
    }

    // ====================================================================== //

    /**
     * @return    string
     */
    protected function _filename()
    {
        return $this->item->filename;
    }

    /**
     * @return    int
     */
    protected function _filesize()
    {
        return $this->item->filesize;
    }

    /**
     * @return    string
     */
    protected function _mime_type()
    {
        return $this->item->mime_type;
    }

    /**
     * @return    int
     */
    protected function _error_no()
    {
        return $this->item->error_no;
    }

    /**
     * @return    UploadStatus
     */
    protected function _status()
    {
        return $this->item->status;
    }

    /**
     * @return    string
     */
    protected function _url()
    {
        return $this->item->url;
    }

    // ====================================================================== //

    /**
     * @return     ArrayIterator<FileValueItem>
     */
    #[\ReturnTypeWillChange]
    public function getIterator()
    {
        return new ArrayIterator([$this]);
    }
}
