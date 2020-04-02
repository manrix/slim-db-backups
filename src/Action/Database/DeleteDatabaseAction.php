<?php

namespace App\Action\Database;

use Aura\Sql\ExtendedPdo;
use Aura\SqlQuery\QueryFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Interfaces\RouteParserInterface;
use Slim\Views\Twig;

final class DeleteDatabaseAction
{
    /**
     * @var Twig
     */
    private $view;

    /**
     * @var RouteParserInterface
     */
    private $routeParser;

    /**
     * @var ExtendedPdo
     */
    private $pdo;

    /**
     * @var QueryFactory
     */
    private $queryFactory;

    /**
     * DeleteDatabaseAction constructor.
     *
     * @param Twig $view
     * @param ExtendedPdo $pdo
     * @param QueryFactory $queryFactory
     * @param RouteParserInterface $routeParser
     */
    public function __construct(
        Twig $view,
        ExtendedPdo $pdo,
        QueryFactory $queryFactory,
        RouteParserInterface $routeParser
    )
    {
        $this->view = $view;
        $this->routeParser = $routeParser;
        $this->pdo = $pdo;
        $this->queryFactory = $queryFactory;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function __invoke(Request $request, Response $response)
    {
        $delete = $this->queryFactory->newDelete();

        $delete->from('databases')
            ->where('id = :id');

        $this->pdo->perform($delete, [
            'id' => $request->getAttribute('id')
        ]);

        return $response->withRedirect($this->routeParser->urlFor('databases'));
    }
}