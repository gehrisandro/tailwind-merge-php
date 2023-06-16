<?php

namespace TailwindMerge;

use TailwindMerge\Support\Config;

final class Factory
{
    /**
     * @var array<string, mixed>
     */
    private array $additionalConfiguration = [];

    /**
     * Override the default configuration.
     *
     * @param  array<string, mixed>  $configuration
     */
    public function withConfiguration(array $configuration): self
    {
        $this->additionalConfiguration = $configuration;

        return $this;
    }

    /**
     * Creates a new TailwindMerge instance.
     */
    public function make(): TailwindMerge
    {
        Config::setAdditionalConfig($this->additionalConfiguration);
        $config = Config::getMergedConfig();

        return new TailwindMerge($config);
    }
}
