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

    $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJsb2dnZWRJbkFzIjoiZGF2ZWd0aGVtaWdodHlAaG90bWFpbC5jb20iLCJpYXQiOjE1NTExODUwMjJ9.Mzg3ZjJlYjE2OTYyMzI0YTE0OGQyY2M2MGRjMTRjYzkzNjA2ZjBlZTI1YWQzMDVkMDQ1ODk0N2Q2YTU3ZGYwYQ";

    $token_parts = Token::decode($token);

    //TODO: Verify that a token is valid

    return $response->withStatus(200)
        ->withHeader("Content-Type", "text/plain")
        ->write(var_export($token_parts, true));
})->setName('decode');
