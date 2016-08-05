<?php
use HouseOfDross\Skippy\Entity\SlackRequest;
use HouseOfDross\Skippy\Entity\SlackResponse;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require __DIR__.'/../vendor/autoload.php';

$app = new \Slim\App;
$app->post('/skippy', function (Request $request, Response $response) {

    $slackRequest = new SlackRequest($request->getParsedBody());

    if ($slackRequest->isSslCheck()) {
        $response = $response->withStatus(200);
        $response->getBody()->write("SSL Check Successful");
        return $response;
    }

    if ($slackRequest->getCommand() != '/skippy') {
        $response = $response->withStatus(400);
        $response->getBody()->write(sprintf("Unknown command '%s'", $slackRequest->getCommand()));
        return $response;
    }

    $responseMessage = "Fair dinkum @matthew.wheeler, Someone reckons you have done a top job, great work mate!";
    $slackResponse = new SlackResponse($responseMessage, []);

    $response = $response->withStatus(200);
    $response = $response->withJson($slackResponse->jsonSerialize());

    return $response;
});
$app->run();
