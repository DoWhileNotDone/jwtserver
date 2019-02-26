<?php declare(strict_types=1);

use JWTServer\Models\User;
use Slim\Http\Request;
use Slim\Http\Response;

// Define named route
$app->get('/', function (Request $request, Response $response, array $arguments): Response {
    if (isset($_SESSION['user_email'])) {
        $arguments['user'] = User::where('email', $_SESSION['user_email'])->first();
        return $this->view->render($response, 'index.html', $arguments);
    } else {
        $location = "/login";
        //Redirect to location
        return $response->withStatus(302)->withHeader('Location', $location);
    }
});
