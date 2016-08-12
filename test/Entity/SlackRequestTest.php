<?php
namespace HouseOfDross\Skippy\Test\Entity;

use Concise\Core\TestCase;
use HouseOfDross\Skippy\Entity\SlackRequest;

class SlackRequestTest extends TestCase
{

    public function testConstructorPopulatesValues()
    {

        $testRequest = [
            'token' => 'gIkuvaNzQIHg97ATvDxqgjtO',
            'team_id' => 'T0001',
            'team_domain' => 'example',
            'channel_id' => 'C2147483705',
            'channel_name' => 'test',
            'user_id' => 'U2147483697',
            'user_name' => 'Steve',
            'command' => '/skippy',
            'text' => '@afoozle has a nice face',
            'response_url' => 'https://hooks.slack.com/commands/1234/5678',
        ];

        $slackRequest = new SlackRequest($testRequest);

        $this->verify($slackRequest->getToken())->equals('gIkuvaNzQIHg97ATvDxqgjtO');
        $this->verify($slackRequest->getTeamId())->equals('T0001');
        $this->verify($slackRequest->getTeamDomain())->equals('example');
        $this->verify($slackRequest->getChannelId())->equals('C2147483705');
        $this->verify($slackRequest->getChannelName())->equals('test');
        $this->verify($slackRequest->getUserId())->equals('U2147483697');
        $this->verify($slackRequest->getUserName())->equals('Steve');
        $this->verify($slackRequest->getCommand())->equals('/skippy');
        $this->verify($slackRequest->getCommandText())->equals('@afoozle has a nice face');
        $this->verify($slackRequest->getResponseUrl())->equals('https://hooks.slack.com/commands/1234/5678');
    }

    public function testSslCheckWorks()
    {
        $slackRequest = new SlackRequest(['ssl_check' => 1]);
        $this->verify($slackRequest->isSslCheck())->equals(true);
    }

    public function testEntityIsImmutable()
    {
        $attachment = new SlackRequest([]);
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
