<?php

namespace App\Http\Middleware;

use Laminas\Session\SessionManager;
use Psr\Http\Message\ResponseInterface;
use Slim\Csrf\Guard;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

final class SessionMiddleware
{
    /**
     * @var SessionManager
     */
    private $sessionManager;
    /**
     * @var Guard
     */
    private $guard;

    /**
     * SessionMiddleware constructor.
     * @param SessionManager $sessionManager
     * @param Guard $guard
     */
    public function __construct(SessionManager $sessionManager, Guard $guard)
    {
        $this->sessionManager = $sessionManager;
        $this->guard = $guard;
    }

    /**
     * @param  Request  $request PSR-7 request
     * @param  RequestHandler $handler PSR-15 request handler
     *
     * @return ResponseInterface
     */
    public function __invoke(Request $request, RequestHandler $handler): ResponseInterface
    {
        $this->sessionManager->start();

        // overwrite guard storage after session is initialized
        $this->guard->setStorage($_SESSION);

        return $handler->handle($request);
    }
}