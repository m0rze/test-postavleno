<?php

class MemcachedDS implements DataSourceInterface
{

    public function makeConnection(stdClass $config)
    {
        // TODO: Implement makeConnection() method.
    }

    public function set(string $key, string $value): string
    {
        return '{}';
    }

    public function get(string $key): string
    {
        return '{}';
    }

    public function delete(string $key): string
    {
        return '{}';
    }

    public function getAll(): array
    {
        return [];
    }
}