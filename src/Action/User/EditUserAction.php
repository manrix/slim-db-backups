<?php

namespace App\Action\User;

use App\Domain\Database\DatabaseNotFoundException;
use App\Domain\User\Repository\UserRepositoryInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Interfaces\RouteParserInterface;
use Slim\Views\Twig;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

final class EditUserAction
{
    /**
     * @var Twig
     */
    private $view;

    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @var RouteParserInterface
     */
    private $routeParser;

    /**
     * EditUserAction constructor.
     *
     * @param Twig $view
     * @param UserRepositoryInterface $userRepository
     * @param RouteParserInterface $routeParser
     */
    public function __construct(Twig $view, UserRepositoryInterface $userRepository, RouteParserInterface $routeParser)
    {
        $this->view = $view;
        $this->userRepository = $userRepository;
        $this->routeParser = $routeParser;
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
        try {
            $user = $this->userRepository->findUserById($request->getAttribute('id'));
        } catch (DatabaseNotFoundException $exception) {
            return $response->withRedirect($this->routeParser->urlFor('users'));
        }

        return $this->view->render($response, 'backend/users/edit.html.twig', [
            'user' => $user
        ]);
    }
}