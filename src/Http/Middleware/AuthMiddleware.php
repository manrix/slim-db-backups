<?php

namespace App\Http\Middleware;

use Laminas\Authentication\AuthenticationService;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Interfaces\RouteParserInterface;

final class AuthMiddleware
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
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    public function __construct(
        AuthenticationService $authenticationService,
        RouteParserInterface $routeParser,
        ResponseFactoryInterface $responseFactory
    )
    {
        $this->authenticationService = $authenticationService;
        $this->routeParser = $routeParser;
        $this->responseFactory = $responseFactory;
    }

    /**
     * @param  Request  $request PSR-7 request
     * @param  RequestHandler $handler PSR-15 request handler
     *
     * @return ResponseInterface
     */
    public function __invoke(Request $request, RequestHandler $handler): ResponseInterface
    {
        if (!$this->authenticationService->hasIdentity()) {
            return $this->responseFactory->createResponse()
                ->withRedirect($this->routeParser->urlFor('login'));
        }

        return $handler->handle($request);
    }
}