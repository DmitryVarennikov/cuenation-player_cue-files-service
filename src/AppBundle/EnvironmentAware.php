<?php

namespace AppBundle;

trait EnvironmentAware
{

    private $environment;

    /**
     * @return string|null
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    public function setEnvironment(string $environment)
    {
        $this->environment = $environment;
    }

    public function isTestEnvironment(): bool
    {
        return 'test' === $this->getEnvironment();
    }

}