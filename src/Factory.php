<?php

namespace TailwindMerge;

use TailwindMerge\Support\Config;

final class Factory
{
    private array $additionalConfiguration = [];

    /**
     * Override the default configuration.
     *
     * @param  mixed[]  $configuration
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
        //        dd($config['conflictingClassGroups']);

        return new TailwindMerge($config);
    }
}
