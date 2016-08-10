<?php
namespace HouseOfDross\Skippy\Entity;

use HouseOfDross\Skippy\Entity\SlackResponse\Attachment;

/**
 * Class SlackResponse
 *
 * A response object which can be sent back to Slack after receiving a command
 *
 * @see https://api.slack.com/docs/message-formatting
 * @see https://api.slack.com/docs/message-attachments
 * @see https://api.slack.com/docs/messages/builder
 *
 * @package HouseOfDross\Skippy\Entity
 */
class SlackResponse implements \JsonSerializable
{
    use PrintableEntity;

    private $inChannelResponse;
    private $responseText;
    private $linkNames;
    private $attachments;

    /**
     * @param string $responseText
     * @param SlackResponseAttachment[] $attachments
     * @param bool $inChannelResponse
     * @param bool $linkNames
     */
    public function __construct(
        string $responseText, array $attachments, bool $inChannelResponse = true, bool $linkNames = true
    )
    {
        $this->responseText = $responseText ?? '';
        $this->inChannelResponse = $inChannelResponse;
        $this->linkNames = $linkNames;
        $this->attachments = $this->filterAttachments($attachments);
    }

    public function jsonSerialize() :array
    {
        $response = [
            'text' => $this->getResponseText(),
            'response_type' => ($this->isInChannelResponse()) ? 'in_channel' : 'ephemeral',
            'link_names' => ($this->isLinkNames()) ? '1' : '0',
            'username' => 'SkippyBadgeBot',
        ];

        if (count($this->getAttachments())) {
            $response['attachments'] = $this->serializeAttachments();
        }

        return $response;
    }

    private function serializeAttachments() :array
    {
        $serializedAttachments = [];
        foreach ($this->attachments as $attachment) {
            $serializedAttachments[] = $attachment->jsonSerialize();
        }
        return $serializedAttachments;
    }

    /**
     * @param Attachment[] $attachments
     * @return Attachment[]
     */
    private function filterAttachments(array $attachments) :array
    {
        $filteredAttachments = [];

        foreach ($attachments as $attachment) {
            if (false === $attachment instanceof Attachment) {
                throw new \InvalidArgumentException("Attachments must be of type SlackResponse\\Attachment");
            }
            $filteredAttachments[] = $attachment;
        }

        return $filteredAttachments;
    }

    public function isInChannelResponse() :bool
    {
        return $this->inChannelResponse;
    }

    public function getResponseText() :string
    {
        return $this->responseText;
    }

    public function isLinkNames() :bool
    {
        return $this->linkNames;
    }

    /**
     * Return a NEW immutable response object with the additional attachments
     *
     * @param array $attachments
     * @return SlackResponse
     */
    public function withAttachments(array $attachments) :SlackResponse
    {
        $newAttachments = $this->filterAttachments($attachments);
        $combinedAttachments = array_merge($this->getAttachments(), $newAttachments);
        return new SlackResponse($this->responseText, $combinedAttachments, $this->inChannelResponse, $this->linkNames);
    }

    /**
     * @return Attachment[]
     */
    public function getAttachments() :array
    {
        return $this->attachments;
    }

}
