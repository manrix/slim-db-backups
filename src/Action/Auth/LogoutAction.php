<?php

namespace App\Action\Auth;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Interfaces\RouteParserInterface;
use Zend\Authentication\AuthenticationService;

final class LogoutAction
{
    /**
     * @var AuthenticationService
     */
    private $authenticationService;

    /**
     * @var RouteParserInterface
     */
    private $routeParser;

    /**
     * LogoutAction constructor.
     *
     * @param AuthenticationService $authenticationService
     * @param RouteParserInterface $routeParser
     */
    public function __construct(
        AuthenticationService $authenticationService,
        RouteParserInterface $routeParser
    )
    {
        $this->authenticationService = $authenticationService;
        $this->routeParser = $routeParser;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function __invoke(Request $request, Response $response)
    {
        $this->authenticationService->clearIdentity();

        return $response->withRedirect($this->routeParser->urlFor('login'));
    }
}