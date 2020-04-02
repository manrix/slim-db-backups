<?php

namespace App\Action\User;

use App\Domain\User\Repository\UserRepositoryInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

final class ListUsersAction
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
     * LoginFormAction constructor.
     *
     * @param Twig $view
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(Twig $view, UserRepositoryInterface $userRepository)
    {
        $this->view = $view;
        $this->userRepository = $userRepository;
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
        return $this->view->render($response, 'backend/users/index.html.twig', [
            'users' => $this->userRepository->getUsers()
        ]);
    }
}