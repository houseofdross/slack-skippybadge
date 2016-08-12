<?php
namespace HouseOfDross\Skippy\Test\Entity;

use Concise\Core\TestCase;
use HouseOfDross\Skippy\Entity\SlackResponse;

class SlackResponseTest extends TestCase
{
    public function testConstructorSetsProperties()
    {
        $response = new SlackResponse('responseText', [new SlackResponse\Attachment(['one'=> '1'])], false, false);
        $this->verify($response->getResponseText())->equals('responseText');
        $this->verify($response->isInChannelResponse())->equals(false);
        $this->verify($response->isLinkNames())->equals(false);
        $this->verifyArray($response->getAttachments())->countIs(1);
    }

    public function testEntityIsImmutable()
    {
        $response = new SlackResponse("responseText", []);
        $reflect = new \ReflectionClass($response);

        $publicProperties = $reflect->getProperties(\ReflectionProperty::IS_PUBLIC);
        $this->verifyArray($publicProperties)->isEmpty;

        $publicMethods = $reflect->getMethods(\ReflectionProperty::IS_PUBLIC);
        $setterMethods = array_filter($publicMethods, function ($item) {
            return substr($item, 0, 3) == 'set';
        });

        $this->verifyArray($setterMethods)->isEmpty;
    }

    public function testWithAttachmentsReturnsNewObject()
    {
        $response = new SlackResponse('responseText', []);
        $newResponse = $response->withAttachments([]);

        $this->assertFalse($response === $newResponse);
    }

    public function testWithAttachmentsFailsWhenGivenIncorrectTypes()
    {
        $response = new SlackResponse('responseText', [
            new SlackResponse\Attachment(['property1' => 'objectOne']),
            new SlackResponse\Attachment(['property2' => 'objectOne'])
        ]);

        try {
            $invalidResponse = $response->withAttachments([
                'nope',
                new \StdClass(),
                false,
                ['why', 'would', 'this', 'work']
            ]);
            $this->fail("An expected exception was not thrown");
        } catch (\InvalidArgumentException $ex) {
            // success!
        }
    }

    public function testSerializeWorksCorrectlyWithDefaults()
    {
        $newResponse = new SlackResponse("response message", []);
        $expectedValue = [
            'text' => 'response message',
            'response_type' => 'in_channel',
            'link_names' => '1',
            'username' => 'SkippyBadgeBot'
        ];
        $this->verify($newResponse->jsonSerialize())->equals($expectedValue);
    }

    public function testSerializeWorksCorrectlyWithNonDefaults()
    {
        $newResponse = new SlackResponse("response message", [], false, false);
        $expectedValue = [
            'text' => 'response message',
            'response_type' => 'ephemeral',
            'link_names' => '0',
            'username' => 'SkippyBadgeBot'
        ];
        $this->verify($newResponse->jsonSerialize())->equals($expectedValue);
    }

    public function testSerializeWorksCorrectlyWithAttachments()
    {
        $attachment1 = new SlackResponse\Attachment([
            'property1' => 'value1',
            'property2' => 'value2'
        ]);

        $attachment2 = new SlackResponse\Attachment([
            'property3' => 'value3',
            'property4' => 'value4'
        ]);

        $newResponse = new SlackResponse("response message", [$attachment1, $attachment2]);
        $expectedValue = [
            'text' => 'response message',
            'response_type' => 'in_channel',
            'link_names' => '1',
            'username' => 'SkippyBadgeBot',
            'attachments' => [
                ['property1' => 'value1', 'property2' => 'value2'],
                ['property3' => 'value3', 'property4' => 'value4']
            ]
        ];
        $this->verify($newResponse->jsonSerialize())->equals($expectedValue);
    }
}
