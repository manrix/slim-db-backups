<?php

namespace App\Action\Auth;

use App\Domain\User\Entity\User;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Support\Authentication\PdoAdapter;
use Aura\Sql\ExtendedPdo;
use Aura\SqlQuery\QueryFactory;
use Laminas\Authentication\AuthenticationService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Interfaces\RouteParserInterface;

final class LoginAction
{
    /**
     * @var AuthenticationService
     */
    private $authenticationService;

    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

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
     * LoginAction constructor.
     *
     * @param AuthenticationService $authenticationService
     * @param UserRepositoryInterface $userRepository
     * @param RouteParserInterface $routeParser
     * @param ExtendedPdo $pdo
     * @param QueryFactory $queryFactory
     */
    public function __construct(
        AuthenticationService $authenticationService,
        UserRepositoryInterface $userRepository,
        RouteParserInterface $routeParser,
        ExtendedPdo $pdo,
        QueryFactory $queryFactory
    )
    {
        $this->authenticationService = $authenticationService;
        $this->userRepository = $userRepository;
        $this->routeParser = $routeParser;
        $this->pdo = $pdo;
        $this->queryFactory = $queryFactory;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function __invoke(Request $request, Response $response)
    {
        $authAdapter = new PdoAdapter(
            $this->userRepository,
            $request->getParam('username'),
            $request->getParam('password')
        );

        $result = $this->authenticationService->authenticate($authAdapter);

        if ($result->isValid()) {
            $this->recordLogin($result->getIdentity());

            return $response->withRedirect($this->routeParser->urlFor('backups'));
        }

        return $response->withRedirect($this->routeParser->urlFor('login'));
    }

    /**
     * @param User $user
     * @return \PDOStatement
     */
    protected function recordLogin(User $user)
    {
        $update = $this->queryFactory->newUpdate();

        $update->table('users')
            ->cols(['last_login'])
            ->where('id = :id');

        $bind = [
            'id' => $user->getId(),
            'last_login' => date('U'),
        ];

        return $this->pdo->perform($update, $bind);
    }
}