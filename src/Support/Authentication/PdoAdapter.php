<?php

namespace App\Support\Authentication;

use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\User\UserNotFoundException;
use Laminas\Authentication\Adapter\AdapterInterface;
use Laminas\Authentication\Result;

class PdoAdapter implements AdapterInterface
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * Sets username and password for authentication
     *
     * @param UserRepositoryInterface $userRepository
     * @param string $username
     * @param string $password
     */
    public function __construct(UserRepositoryInterface $userRepository, string $username, string $password)
    {
        $this->userRepository = $userRepository;
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * @inheritDoc
     */
    public function authenticate()
    {
        try {
            $user = $this->userRepository->findUserByName($this->username);
        } catch (UserNotFoundException $exception) {
            return new Result(Result::FAILURE_IDENTITY_NOT_FOUND, null);
        }

        if (!password_verify($this->password, $user->getPassword())) {
            return new Result(Result::FAILURE, null);
        }

        return new Result(Result::SUCCESS, $user);
    }
}