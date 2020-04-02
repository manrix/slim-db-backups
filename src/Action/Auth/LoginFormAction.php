<?php

namespace App\Action\Auth;

use Slim\Views\Twig;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

final class LoginFormAction
{
    /**
     * @var Twig
     */
    private $view;

    /**
     * LoginFormAction constructor.
     *
     * @param Twig $view
     */
    public function __construct(Twig $view)
    {
        $this->view = $view;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function __invoke(Request $request, Response $response)
    {
        return $this->view->render($response, 'auth/login.html.twig');
    }
}