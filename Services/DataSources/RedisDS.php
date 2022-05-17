<?php
use \Predis\Client;

class RedisDS implements DataSourceInterface
{
    /**
     * Connection field
     * @var Client
     */
    private Client $conn;

    /**
     * Expire time (in sec)
     * @var int
     */
    private int $ttl = 3600;

    /**
     * Set new value in storage
     *
     * @param string $key
     * @param string $value
     * @return string
     */
    public function set(string $key, string $value): string
    {
        try {
            $this->conn->set($key, $value);
            $this->conn->expire($key, $this->ttl);
        } catch (Exception $e) {
            $this->conn->disconnect();
            return $e->getMessage();
        }
        $this->conn->disconnect();
        return true;
    }

    /**
     * Get value from storage by key
     *
     * @param string $key
     * @return string
     */
    public function get(string $key): string
    {
        try {
            $value = $this->conn->get($key);
        } catch (Exception $e) {
            $this->conn->disconnect();
            return $e->getMessage();
        }
        $this->conn->disconnect();
        return $value;
    }

    /**
     * Delete key from storage
     *
     * @param string $key
     * @return string
     */
    public function delete(string $key): string
    {
        try {
            $deleteStatus = $this->conn->del($key);
        } catch (Exception $e) {
            $this->conn->disconnect();
            return $e->getMessage();
        }
        $this->conn->disconnect();
        return $deleteStatus;
    }

    /**
     * Make connection to data source
     *
     * @param stdClass $config
     */
    public function makeConnection(stdClass $config)
    {
        if(!empty($config->redis->ttl)){
            $this->ttl = $config->redis->ttl;
        }
        $this->conn = new Client([
            'scheme' => 'tcp',
            'host'   => $config->redis->host,
            'port'   => $config->redis->port,
        ]);
        try {
            $this->conn->ping();
        } catch (Exception $e) {
            echo $e->getMessage();die();
        }
    }

    /**
     * Get all keys from storage
     *
     * @return array
     */
    public function getAll(): array
    {
        $allKeys = $this->conn->keys("*");
        $result = [];
        foreach ($allKeys as $oneKey) {
            $value = $this->conn->get($oneKey);
            if(!empty($value)){
                $result[$oneKey] = $value;
            }
        }
        return $result;
    }
}