<?php declare(strict_types=1);

use JWTServer\Utility\Token;

use Slim\Http\Request;
use Slim\Http\Response;

$app->get("/token", function (Request $request, Response $response, array $arguments): Response {

    //TODO: Check Token Request
    $payload = [
      'loggedInAs' => $_SESSION['user_email'],
      'iat' => strtotime('now'),
    ];

    $token = Token::create($payload);

    //Display Token
    return $response->withStatus(200)
        ->withHeader("Content-Type", "application/json")
        ->write(json_encode($token, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
})->setName('token');

$app->get("/decode", function (Request $request, Response $response, array $arguments): Response {

    if (!$request->hasHeader('authorization')) {
        die('token not valid!');
    }

    $authorizationArray = $request->getHeader('authorization');
    //FIXME: Check there is only one
    $authorization = array_pop($authorizationArray);

    list($token) = sscanf($authorization, 'Bearer %s');

    if (!Token::validate($token)) {
        die('token not valid!');
    }

    $token_parts = Token::decode($token);

    return $response->withStatus(200)
        ->withHeader("Content-Type", "text/plain")
        ->write(var_export($token_parts, true));
})->setName('decode');
