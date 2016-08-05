<?php
namespace HouseOfDross\Skippy\Entity\SlackResponse;
use HouseOfDross\Skippy\Entity\PrintableEntity;

/**
 * Class SlackResponseAttachment
 *
 * Entity to represent an attachment on a response
 *
 * @see https://api.slack.com/docs/message-attachments
 * @package HouseOfDross\Skippy\Entity
 */
class Attachment implements \JsonSerializable
{
    use PrintableEntity;

    private $properties;

    public function __construct(array $properties)
    {
        $this->properties = $properties;
    }

    function jsonSerialize() :array
    {
        return $this->properties;
    }

    /**
     * @return array
     */
    public function getProperties()
    {
        return $this->properties;
    }
}
