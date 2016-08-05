<?php
namespace HouseOfDross\Skippy\Test\Entity\SlackResponse;

use Concise\Core\TestCase;
use HouseOfDross\Skippy\Entity\SlackResponse\Attachment;

class AttachmentTest extends TestCase
{
    public function testConstructorSetsProperties()
    {
        $attachment = new Attachment([
            'property1' => 'one',
            'property2' => 'two'
        ]);

        $expectedProperties = [
            'property1' => 'one',
            'property2' => 'two'
        ];

        $this->verify($attachment->getProperties())->equals($expectedProperties);
    }

    public function testEntityIsImmutable()
    {
        $attachment = new Attachment([]);
        $reflect = new \ReflectionClass($attachment);

        $publicProperties = $reflect->getProperties(\ReflectionProperty::IS_PUBLIC);
        $this->verifyArray($publicProperties)->isEmpty;

        $publicMethods = $reflect->getMethods(\ReflectionProperty::IS_PUBLIC);
        $setterMethods = array_filter($publicMethods, function($item) {
            return substr($item, 0, 3) == 'set';
        });

        $this->verifyArray($setterMethods)->isEmpty;
    }
}
