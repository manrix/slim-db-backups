<?php

namespace App\Action\Backup;

use App\Domain\Backup\Repository\BackupRepositoryInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

final class ListBackupsAction
{
    /**
     * @var Twig
     */
    private $view;

    /**
     * @var BackupRepositoryInterface
     */
    private $backupsRepository;

    /**
     * LoginFormAction constructor.
     *
     * @param Twig $view
     * @param BackupRepositoryInterface $backupsRepository
     */
    public function __construct(Twig $view, BackupRepositoryInterface $backupsRepository)
    {
        $this->view = $view;
        $this->backupsRepository = $backupsRepository;
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
        $backups = $this->backupsRepository->getBackups();

        $fileList = [];
        foreach ($backups as $file) {
            $fileList[filemtime($file->getPath())] = $file;
        }

        ksort($fileList);
        $fileList = array_reverse($fileList);

        return $this->view->render($response, 'backend/backups/index.html.twig', [
            'backups' => $fileList
        ]);
    }
}