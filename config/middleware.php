<?php declare(strict_types=1);

use Slim\Http\Request;
use Slim\Http\Response;

// Application middleware
// e.g: $app->add(new \Slim\Csrf\Guard);

// Check the user is logged in when necessary.
$loggedInMiddleware = function ($request, $response, $next) {
    $route = $request->getAttribute('route');
    $routeName = $route->getName();

    # Define routes that user does not have to be logged in with. All other routes, the user
    # needs to be logged in with.
    $publicRoutesArray = array(
        'login',
        'logout',
    );

    if (!isset($_SESSION['user_email']) && !in_array($routeName, $publicRoutesArray)) {
        // redirect the user to the login page and do not proceed.
        $response = $response->withRedirect('/login');
    } else {
        // Proceed as normal...
        $response = $next($request, $response);
    }

    return $response;
};

// Apply the middleware to every request.
$app->add($loggedInMiddleware);
