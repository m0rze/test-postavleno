<?php


interface DataSourceInterface {
    public function makeConnection(stdClass $config);
    public function set(string $key, string $value): string;
    public function get(string $key): string;
    public function getAll(): array;
    public function delete(string $key): string;
}