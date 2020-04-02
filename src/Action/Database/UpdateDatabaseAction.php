<?php

namespace App\Action\Database;

use App\Domain\Database\Repository\DatabaseRepositoryInterface;
use Aura\Sql\ExtendedPdo;
use Aura\SqlQuery\QueryFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Interfaces\RouteParserInterface;
use Slim\Views\Twig;

final class UpdateDatabaseAction
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
     * @var DatabaseRepositoryInterface
     */
    private $databaseRepository;

    /**
     * UpdateDatabaseAction constructor.
     *
     * @param Twig $view
     * @param DatabaseRepositoryInterface $databaseRepository
     * @param ExtendedPdo $pdo
     * @param QueryFactory $queryFactory
     * @param RouteParserInterface $routeParser
     */
    public function __construct(
        Twig $view,
        DatabaseRepositoryInterface $databaseRepository,
        ExtendedPdo $pdo,
        QueryFactory $queryFactory,
        RouteParserInterface $routeParser
    )
    {
        $this->view = $view;
        $this->routeParser = $routeParser;
        $this->pdo = $pdo;
        $this->queryFactory = $queryFactory;
        $this->databaseRepository = $databaseRepository;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function __invoke(Request $request, Response $response)
    {
        $update = $this->queryFactory->newUpdate();

        $update->table('databases')
            ->cols(['name', 'driver', 'user', 'password', 'updated_at'])
            ->where('id = :id');

        $bind = [
            'id' => $request->getAttribute('id'),
            'name' => $request->getParam('name'),
            'driver' => $request->getParam('driver'),
            'user' => $request->getParam('user'),
            'password' => $request->getParam('password'),
            'updated_at' => date('U'),
        ];

        $this->pdo->perform($update, $bind);

        return $response->withRedirect($this->routeParser->urlFor('databases'));
    }
}