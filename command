<?php
require_once "autoload.php";

class Command
{
    /**
     * Allowed actions with data storage
     * @var array|string[]
     */
    private array $allowedActions = [
        "set",
        "get",
        "delete"
    ];
    /**
     * Data source object
     * @var DataSourceInterface
     */
    private DataSourceInterface $dataSource;
    /**
     * Data source type
     * @var string
     */
    private string $dsType;
    /**
     * Current action with storage
     * @var string
     */
    private string $action;
    /**
     * Current key
     * @var string
     */
    private string $key;
    /**
     * Current value
     * @var string
     */
    private string $value = "";

    /**
     * @param $argv
     * @param $config
     */
    public function __construct($argv, $config)
    {
        $this->getParameters($argv);
        try {
            $this->setDataSource(new $this->dsType);
            $this->dataSource->makeConnection($config->datasource);
        } catch (Exception $e) {
            die($e->getMessage());
        }
        $this->makeAction();
    }

    /**
     * Set data source object
     * @param DataSourceInterface $dataSource
     */
    private function setDataSource(DataSourceInterface $dataSource)
    {
        $this->dataSource = $dataSource;
    }

    /**
     * Get parameters from arguments
     * @param $argv
     */
    private function getParameters($argv)
    {
        if (!empty($argv[1])) {
            $this->dsType = ucfirst($argv[1]) . "DS";
        }
        if (!empty($argv[2])) {
            $this->action = $argv[2];
        }
        if (!in_array($this->action, $this->allowedActions)) {
            die("Bad action call - " . $this->action . PHP_EOL);
        }
        if (!empty($argv[3])) {
            $this->key = $argv[3];
        }
        if (!empty($argv[4])) {
            $this->value = $argv[4];
        }
    }

    /**
     * Make action with storage
     */
    private function makeAction()
    {
        $action = $this->action;
        $result = $this->dataSource->$action($this->key, $this->value);
        echo $result;
    }

}

new Command($argv, $config);