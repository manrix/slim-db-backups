<?php

namespace App\Action\Database;

use App\Domain\Database\Repository\DatabaseRepositoryInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

final class ListDatabasesAction
{
    /**
     * @var Twig
     */
    private $view;

    /**
     * @var DatabaseRepositoryInterface
     */
    private $databaseRepository;

    /**
     * ListDatabasesAction constructor.
     *
     * @param Twig $view
     * @param DatabaseRepositoryInterface $databaseRepository
     */
    public function __construct(Twig $view, DatabaseRepositoryInterface $databaseRepository)
    {
        $this->view = $view;
        $this->databaseRepository = $databaseRepository;
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
        return $this->view->render($response, 'backend/databases/index.html.twig', [
            'databases' => $this->databaseRepository->getDatabases()
        ]);
    }
}