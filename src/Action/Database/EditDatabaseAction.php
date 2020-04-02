<?php

namespace App\Action\Database;

use App\Domain\Database\DatabaseNotFoundException;
use App\Domain\Database\Repository\DatabaseRepositoryInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Interfaces\RouteParserInterface;
use Slim\Views\Twig;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

final class EditDatabaseAction
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
     * @var RouteParserInterface
     */
    private $routeParser;

    /**
     * EditDatabaseAction constructor.
     *
     * @param Twig $view
     * @param DatabaseRepositoryInterface $databaseRepository
     * @param RouteParserInterface $routeParser
     */
    public function __construct(
        Twig $view,
        DatabaseRepositoryInterface $databaseRepository,
        RouteParserInterface $routeParser
    )
    {
        $this->view = $view;
        $this->databaseRepository = $databaseRepository;
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
            $database = $this->databaseRepository->findDatabaseById($request->getAttribute('id'));
        } catch (DatabaseNotFoundException $exception) {
            return $response->withRedirect($this->routeParser->urlFor('databases'));
        }

        return $this->view->render($response, 'backend/databases/edit.html.twig', [
            'database' => $database
        ]);
    }
}