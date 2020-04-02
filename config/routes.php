<?php

declare(strict_types=1);

use App\Action\Auth\LoginAction;
use App\Action\Auth\LoginFormAction;
use App\Action\Auth\LogoutAction;
use App\Action\Backup\DeleteBackupAction;
use App\Action\Backup\DownloadBackupAction;
use App\Action\Backup\ListBackupsAction;
use App\Action\Database\BackupDatabaseAction;
use App\Action\Database\CreateDatabaseAction;
use App\Action\Database\DeleteDatabaseAction;
use App\Action\Database\EditDatabaseAction;
use App\Action\Database\ListDatabasesAction;
use App\Action\Database\StoreDatabaseAction;
use App\Action\Database\UpdateDatabaseAction;
use App\Action\User\CreateUserAction;
use App\Action\User\DeleteUserAction;
use App\Action\User\EditUserAction;
use App\Action\User\ListUsersAction;
use App\Action\User\StoreUserAction;
use App\Action\User\UpdatePasswordAction;
use App\Action\User\UpdateUserAction;
use App\Http\Middleware\AuthMiddleware;
use App\Http\Middleware\SessionMiddleware;
use Slim\App;
use Slim\Csrf\Guard;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

return function (App $app) {
    $container = $app->getContainer();

    $app->group('', function (Group $group) use ($container) {
        $group->get('/login', LoginFormAction::class)->setName('login');
        $group->post('/login', LoginAction::class);

        $group->group('', function (Group $group) {
            $group->post('/logout', LogoutAction::class)->setName('logout');
            $group->get('/[backups]', ListBackupsAction::class)->setName('backups');
            $group->post('/backups/{name}/delete', DeleteBackupAction::class)->setName('backup.delete');
            $group->get('/backups/{name}/download', DownloadBackupAction::class)->setName('backup.download');
            $group->get('/databases', ListDatabasesAction::class)->setName('databases');
            $group->get('/databases/create', CreateDatabaseAction::class)->setName('database.create');
            $group->post('/databases/store', StoreDatabaseAction::class)->setName('database.store');
            $group->get('/databases/{id}/edit', EditDatabaseAction::class)->setName('database.edit');
            $group->post('/databases/{id}/update', UpdateDatabaseAction::class)->setName('database.update');
            $group->post('/databases/{id}/delete', DeleteDatabaseAction::class)->setName('database.delete');
            $group->post('/databases/{id}/backup', BackupDatabaseAction::class)->setName('database.backup');
            $group->get('/users', ListUsersAction::class)->setName('users');
            $group->get('/users/create', CreateUserAction::class)->setName('user.create');
            $group->post('/users/store', StoreUserAction::class)->setName('user.store');
            $group->get('/users/{id}/edit', EditUserAction::class)->setName('user.edit');
            $group->post('/users/{id}/update', UpdateUserAction::class)->setName('user.update');
            $group->post('/users/{id}/password', UpdatePasswordAction::class)->setName('user.password');
            $group->post('/users/{id}/delete', DeleteUserAction::class)->setName('user.delete');
        })->add($container->get(AuthMiddleware::class));

    })->add(TwigMiddleware::createFromContainer($app, Twig::class))
        ->add($container->get(Guard::class))
        ->add($container->get(SessionMiddleware::class));
};
