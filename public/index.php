<?php
use HouseOfDross\Skippy\Entity\SlackRequest;
use HouseOfDross\Skippy\Entity\SlackResponse;
use HouseOfDross\Skippy\RandomMessageLoader;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

require __DIR__.'/../vendor/autoload.php';

$app = new \Slim\App;
$app->post('/skippy', function (Request $request, Response $response) {

    $requestBody = $request->getParsedBody();
    if (false === is_array($requestBody)) {
        $unreadableRequestResponse = $response->withStatus(400);
        $unreadableRequestResponse->getBody()->write("Unable to parse the body of the message POSTed");
        return $unreadableRequestResponse;
    }

    $slackRequest = new SlackRequest($requestBody);
    if ($slackRequest->isSslCheck()) {
        $sslCheckResponse = $response->withStatus(200);
        $sslCheckResponse->getBody()->write("SSL Check Successful");
        return $sslCheckResponse;
    }

    if ($slackRequest->getCommand() != '/skippy') {
        $invalidCommandResponse = $response->withStatus(400);
        $invalidCommandResponse->getBody()->write(sprintf("Unknown command '%s'", $slackRequest->getCommand()));
        return $invalidCommandResponse;
    }

    $commandPieces = explode(' ', $slackRequest->getCommandText(), 2);
    if (count($commandPieces) != 2 || 0 === preg_match('/@[^@]*/',$commandPieces[0])) {
        $invalidCommandFormatResponse = $response->withStatus(400);
        $invalidCommandFormatResponse->getBody()->write('Invalid skippy command, use \'/skippy @user [message]');
        return $invalidCommandFormatResponse;
    }

    $user = $commandPieces[0];
    $message = $commandPieces[1];

    $fileSystem = new Filesystem(new Local(__DIR__.'/../resources'));
    $messageLoader = new RandomMessageLoader($fileSystem, 'skippy_responses.yaml');
    $responseMessage = str_replace(['{user}', '{message}'], [$user, $message], $messageLoader->getRandomMessage());
    $slackResponse = new SlackResponse($responseMessage, []);

    $validResponse = $response
        ->withStatus(200)
        ->withJson($slackResponse->jsonSerialize());

    return $validResponse;
});
$app->run();
