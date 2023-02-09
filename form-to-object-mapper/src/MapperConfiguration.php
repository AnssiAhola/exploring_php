<?php

class MapperConfiguration
{
    private bool $basicSanitizationEnabled = true;

    public static function makeDefault(): self
    {
        $config = new self();
        return $config;
    }

    public function enableBasicSanitization(): void
    {
        $this->basicSanitizationEnabled = true;
    }

    public function disableBasicSanitization(): void
    {
        $this->basicSanitizationEnabled = false;
    }

    public function basicSanitizationEnabled(): bool
    {
        return $this->basicSanitizationEnabled;
    }
}
