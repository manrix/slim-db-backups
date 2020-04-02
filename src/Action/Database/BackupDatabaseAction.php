<?php

namespace App\Action\Database;

use App\Domain\Database\DatabaseNotFoundException;
use App\Domain\Database\Repository\DatabaseRepositoryInterface;
use App\Support\Dumper\DumperFactory;
use App\Support\Dumper\DumperService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Interfaces\RouteParserInterface;
use Slim\Views\Twig;

final class BackupDatabaseAction
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
     * @var DumperFactory
     */
    private $dumperFactory;

    /**
     * @var DumperService
     */
    private $dumperService;

    /**
     * EditDatabaseAction constructor.
     *
     * @param Twig $view
     * @param DatabaseRepositoryInterface $databaseRepository
     * @param RouteParserInterface $routeParser
     * @param DumperFactory $dumperFactory
     * @param DumperService $dumperService
     */
    public function __construct(
        Twig $view,
        DatabaseRepositoryInterface $databaseRepository,
        RouteParserInterface $routeParser,
        DumperFactory $dumperFactory,
        DumperService $dumperService
    )
    {
        $this->view = $view;
        $this->databaseRepository = $databaseRepository;
        $this->routeParser = $routeParser;
        $this->dumperFactory = $dumperFactory;
        $this->dumperService = $dumperService;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function __invoke(Request $request, Response $response)
    {
        try {
            $database = $this->databaseRepository->findDatabaseById($request->getAttribute('id'));
        } catch (DatabaseNotFoundException $exception) {
            return $response->withRedirect($this->routeParser->urlFor('databases'));
        }

        $dumper = $this->dumperFactory->create($database->getDriver())
            ->setDbName($database->getName())
            ->setUserName($database->getUser())
            ->setPassword($database->getPassword());

        $this->dumperService->dump($dumper, $database->getBackupFileName());

        return $response->withRedirect($this->routeParser->urlFor('backups'));
    }
}