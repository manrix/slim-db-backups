<?php

namespace App\Support\Twig;

use Slim\Csrf\Guard;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CsrfExtension extends AbstractExtension
{
    /**
     * @var Guard
     */
    protected $csrf;

    /**
     * CsrfExtension constructor.
     *
     * @param Guard $csrf
     */
    public function __construct(Guard $csrf)
    {
        $this->csrf = $csrf;
    }
    
    /**
     * @return string
     */
    public function getName(): string
    {
        return 'csrf';
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('csrf', [$this, 'getCsrfFields'], ['needs_environment' => true, 'is_safe' => ['html']]),
            new TwigFunction('csrf_name', [$this, 'getCsrfName']),
            new TwigFunction('csrf_token', [$this, 'getCsrfToken']),
        ];
    }

    /**
     * @param Environment $environment
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function getCsrfFields(Environment $environment): string
    {
        // CSRF token name and value
        $csrfNameKey = $this->csrf->getTokenNameKey();
        $csrfValueKey = $this->csrf->getTokenValueKey();
        $csrfName = $this->csrf->getTokenName();
        $csrfValue = $this->csrf->getTokenValue();

        return $environment->render('_partials/csrf.html.twig', [
            'csrf'   => [
                'keys' => [
                    'name'  => $csrfNameKey,
                    'value' => $csrfValueKey
                ],
                'name'  => $csrfName,
                'value' => $csrfValue
            ]
        ]);
    }

    /**
     * @return string|null
     */
    public function getCsrfName(): ?string
    {
        return $this->csrf->getTokenName();
    }

    /**
     * @return string|null
     */
    public function getCsrfToken(): ?string
    {
        return $this->csrf->getTokenValue();
    }
}