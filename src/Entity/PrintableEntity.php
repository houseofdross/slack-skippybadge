<?php
namespace HouseOfDross\Skippy\Entity;

/**
 * Trait PrintableEntity
 *
 * Provides a standard __toString method for entities
 *
 * @package HouseOfDross\Skippy\Entity
 */
trait PrintableEntity
{
    public function __toString() :string
    {
        return get_class($this) .':'. json_encode(get_object_vars($this));
    }
}
