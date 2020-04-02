<?php

declare(strict_types=1);

use App\Domain\Backup\Repository\BackupRepositoryInterface;
use App\Domain\Backup\Repository\FileBackupRepository;
use App\Domain\Database\Repository\DatabaseRepository;
use App\Domain\Database\Repository\DatabaseRepositoryInterface;
use App\Domain\User\Repository\UserRepository;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Http\Middleware\AuthMiddleware;
use App\Http\Middleware\SessionMiddleware;
use App\Support\Dumper\DumperFactory;
use App\Support\Dumper\DumperService;
use App\Support\Filesystem\LocalFilesystem;
use App\Support\Twig\CsrfExtension;
use Aura\Sql\ExtendedPdo;
use Aura\SqlQuery\QueryFactory;
use Laminas\Authentication\AuthenticationService;
use Laminas\Session\Config\SessionConfig;
use Laminas\Session\Container;
use Laminas\Session\SessionManager;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Selective\BasePath\BasePathDetector;
use Slim\App;
use Slim\Csrf\Guard;
use Slim\Factory\AppFactory;
use Slim\Interfaces\RouteParserInterface;
use Slim\Views\Twig;
use Twig\Extension\DebugExtension;
use Twig\TwigFilter;

return [
    'settings' => static function () {
        return require __DIR__ . '/settings.php';
    },

    App::class => static function (ContainerInterface $container) {
        AppFactory::setContainer($container);
        $app = AppFactory::create();

        $app->addErrorMiddleware($container->get('settings')['displayErrorDetails'], false, false);

        // Set the base path to run the app in a subdirectory.
        // This path is used in urlFor().
        $basePath = (new BasePathDetector($_SERVER))->getBasePath();
        $app->setBasePath($basePath);

        if ($container->get('settings')['routes_cache']) {
            $app->getRouteCollector()->setCacheFile($container->get('settings')['routes_cache']);
        }

        return $app;
    },

    // For the responder
    ResponseFactoryInterface::class => function (ContainerInterface $container) {
        return $container->get(App::class)->getResponseFactory();
    },

    // The Slim RouterParser
    RouteParserInterface::class => function (ContainerInterface $container) {
        return $container->get(App::class)->getRouteCollector()->getRouteParser();
    },

    Twig::class => static function (ContainerInterface $container) {
        $settings = $container->get('settings')['twig'];
        $twig = Twig::create($settings['path'], $settings['options']);
        $twig->getEnvironment()->addExtension(new CsrfExtension($container->get(Guard::class)));
        $twig->getEnvironment()->addExtension(new DebugExtension());

        $filter = new TwigFilter('format_size', function ($size) {
            $units = array('B','kB','MB','GB','TB','PB','EB','ZB','YB');
            $step = 1024;
            $i = 0;
            while (($size / $step) > 0.9) {
                $size = $size / $step;
                $i++;
            }

            return round($size, 2) . ' ' . $units[$i];
        });

        $twig->getEnvironment()->addFilter($filter);

        return $twig;
    },

    Guard::class => static function (ContainerInterface $container) {
        // set an empty array storage because session is not yet initialized
        $storage = [];
        return new Guard(
            $container->get(ResponseFactoryInterface::class),
            'csrf',
            $storage
        );
    },

    ExtendedPdo::class  => static function (ContainerInterface $container) {
        $settings = $container->get('settings')['db'];
        return new ExtendedPdo(
            sprintf('%s:%s', $settings['driver'], $settings['name'])
        );
    },

    QueryFactory::class  => static function (ContainerInterface $container) {
        $settings = $container->get('settings')['db'];
        return new QueryFactory($settings['driver']);
    },

    AuthenticationService::class => static function () {
        return new AuthenticationService();
    },

    SessionManager::class => static function (ContainerInterface $container) {
        $sessionConfig = new SessionConfig();
        $sessionConfig->setOptions($container->get('settings')['session']);
        $sessionManager = new SessionManager($sessionConfig);

        Container::setDefaultManager($sessionManager);

        return $sessionManager;
    },

    AuthMiddleware::class => static function (ContainerInterface $container) {
        return new AuthMiddleware(
            $container->get(AuthenticationService::class),
            $container->get(RouteParserInterface::class),
            $container->get(ResponseFactoryInterface::class)
        );
    },

    SessionMiddleware::class => static function (ContainerInterface $container) {
        return new SessionMiddleware(
            $container->get(SessionManager::class),
            $container->get(Guard::class)
        );
    },

    UserRepositoryInterface::class => static function (ContainerInterface $container) {
        return new UserRepository($container->get(ExtendedPdo::class));
    },

    DatabaseRepositoryInterface::class => static function (ContainerInterface $container) {
        return new DatabaseRepository($container->get(ExtendedPdo::class));
    },

    BackupRepositoryInterface::class => static function (ContainerInterface $container) {
        $filesystem = new LocalFilesystem($container->get('settings')['backups_path']);

        return new FileBackupRepository($filesystem);
    },

    DumperFactory::class => static function (ContainerInterface $container) {
        return new DumperFactory($container->get('settings')['dumper']);
    },

    DumperService::class => static function (ContainerInterface $container) {
        return new DumperService($container->get('settings')['backups_path']);
    },
];
