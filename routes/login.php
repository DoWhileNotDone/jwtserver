<?php declare(strict_types=1);

use JWTServer\Models\User;
use Slim\Http\Request;
use Slim\Http\Response;

$app->get("/login", function (Request $request, Response $response, array $arguments): Response {
    $response = $this->view->render($response, 'login/form.html', $arguments);
    return $response;
})->setName('login');

$app->post("/login", function (Request $request, Response $response, array $arguments): Response {

    $parsedBody = $request->getParsedBody();
    if (isset($parsedBody['g-recaptcha-response']) && !empty($parsedBody['g-recaptcha-response'])) {
        $captchaResponse = $parsedBody['g-recaptcha-response'];
        $secret = getenv('GOOGLE_RECAPTCHA');

        $verify_url = sprintf(
            'https://www.google.com/recaptcha/api/siteverify?secret=%s&response=%s',
            $secret,
            $captchaResponse
        );
        $verifyResponse = file_get_contents($verify_url);
        $responseData = json_decode($verifyResponse);

        if (!$responseData->success) {
            echo 'Invalid Captcha';
            return $response->withStatus(403);
        }
    }

    $user = User::where('email', $parsedBody['email'])->first();
    if (!$user) {
        echo 'Invalid User';
        return $response->withStatus(404);
    }

    //FIXME: Verify Password...
    if (password_verify($parsedBody['password'], $user->password)) {
        $_SESSION['user_email'] = $user->email;
    } else {
        echo 'Invalid password.';
        die();
    }

    $location = "/";

    //Redirect to location
    return $response->withStatus(302)->withHeader('Location', $location);
})->setName('login');
