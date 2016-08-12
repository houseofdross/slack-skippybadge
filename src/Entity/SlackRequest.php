<?php
namespace HouseOfDross\Skippy\Entity;

/**
 * Class SlackRequest
 *
 * Represents a command request that has come in from Slack
 *
 * @package HouseOfDross\Skippy\Entity
 */
class SlackRequest
{
    use PrintableEntity;

    private $token;
    private $teamId;
    private $teamDomain;
    private $channelId;
    private $channelName;
    private $userId;
    private $userName;
    private $command;
    private $commandText;
    private $responseUrl;
    private $isSslCheck;

    public function __construct(array $requestValues)
    {
        $this->token = $requestValues['token'] ?? '';
        $this->teamId = $requestValues['team_id'] ?? '';
        $this->teamDomain = $requestValues['team_domain'] ?? '';
        $this->channelId = $requestValues['channel_id'] ?? '';
        $this->channelName = $requestValues['channel_name'] ?? '';
        $this->userId = $requestValues['user_id'] ?? '';
        $this->userName = $requestValues['user_name'] ?? '';
        $this->command = $requestValues['command'] ?? '';
        $this->commandText = $requestValues['text'] ?? '';
        $this->responseUrl = $requestValues['response_url'] ?? '';

        $this->isSslCheck = false;
        if (isset($requestValues['ssl_check']) && $requestValues['ssl_check'] == '1') {
            $this->isSslCheck = true;
        }
    }

    public function getToken() :string {
        return $this->token;
    }

    public function getTeamId() :string {
        return $this->teamId;
    }

    public function getTeamDomain() :string {
        return $this->teamDomain;
    }

    public function getChannelId() :string {
        return $this->channelId;
    }

    public function getChannelName() :string {
        return $this->channelName;
    }

    public function getUserId() :string {
        return $this->userId;
    }

    public function getUserName() :string {
        return $this->userName;
    }

    public function getCommand() :string {
        return $this->command;
    }

    public function getCommandText() :string {
        return $this->commandText;
    }

    public function getResponseUrl() :string {
        return $this->responseUrl;
    }

    public function isSslCheck() :bool {
        return $this->isSslCheck;
    }
}
