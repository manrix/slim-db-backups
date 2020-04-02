<?php

namespace App\Action\Backup;

use App\Domain\Backup\Repository\BackupRepositoryInterface;
use App\Domain\Backup\BackupNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Interfaces\RouteParserInterface;

final class DownloadBackupAction
{
    /**
     * @var BackupRepositoryInterface
     */
    private $backupsRepository;

    /**
     * @var RouteParserInterface
     */
    private $routeParser;

    /**
     * LoginFormAction constructor.
     *
     * @param BackupRepositoryInterface $backupsRepository
     * @param RouteParserInterface $routeParser
     */
    public function __construct(BackupRepositoryInterface $backupsRepository, RouteParserInterface $routeParser)
    {
        $this->backupsRepository = $backupsRepository;
        $this->routeParser = $routeParser;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function __invoke(Request $request, Response $response, array $args)
    {
        try {
            $backup = $this->backupsRepository->findBackupByName($args['name']);
        } catch (BackupNotFoundException $exception) {
            return $response->withRedirect($this->routeParser->urlFor('backups'));
        }

        return $response->withFileDownload($backup->getPath(), $backup->getName());
    }
}