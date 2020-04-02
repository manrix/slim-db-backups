<?php

namespace App\Action\Database;

use Aura\Sql\ExtendedPdo;
use Aura\SqlQuery\QueryFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Interfaces\RouteParserInterface;
use Slim\Views\Twig;

final class StoreDatabaseAction
{
    /**
     * @var Twig
     */
    private $view;

    /**
     * @var ExtendedPdo
     */
    private $pdo;

    /**
     * @var RouteParserInterface
     */
    private $routeParser;

    /**
     * @var QueryFactory
     */
    private $queryFactory;

    /**
     * StoreDatabaseAction constructor.
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
        $this->pdo = $pdo;
        $this->routeParser = $routeParser;
        $this->queryFactory = $queryFactory;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function __invoke(Request $request, Response $response)
    {
        $insert = $this->queryFactory->newInsert();

        $insert->into('databases')
            ->cols(['name', 'driver', 'user', 'password', 'created_at']);

        $bind = [
            'name' => $request->getParam('name'),
            'driver' => $request->getParam('driver'),
            'user' => $request->getParam('user'),
            'password' => $request->getParam('password'),
            'created_at' => date('U'),
        ];

        $this->pdo->perform($insert, $bind);

        return $response->withRedirect($this->routeParser->urlFor('databases'));
    }
}