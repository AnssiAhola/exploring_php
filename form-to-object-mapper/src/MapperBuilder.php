<?php

class MapperBuilder
{
    public function __construct(
        private ?MapperConfiguration $config = null
    ) {
    }

    public function configureWith(MapperConfiguration $config): self
    {
        $this->config = $config;
        return $this;
    }

    public function build(): Mapper {
        return new Mapper($this->config ?? MapperConfiguration::makeDefault());
    }
}
