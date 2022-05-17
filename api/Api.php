<?php
require_once "../autoload.php";
class Api
{
    /**
     * Type of data storage
     * @var string
     */
    private string $dsType;
    /**
     * Current key
     * @var string
     */
    private string $key = "";
    /**
     * Data source object
     * @var DataSourceInterface
     */
    private DataSourceInterface $dataSource;
    /**
     * HTTP request method
     * @var string
     */
    private string $requestMethod;

    /**
     * @param $config
     */
    public function __construct($config)
    {
        $this->getParameters();
        try {
            $this->setDataSource(new $this->dsType);
            $this->dataSource->makeConnection($config->datasource);
        } catch (Exception $e) {
            die($e->getMessage());
        }
        $this->showResult();
    }

    /**
     * Get parameters from URL
     */
    private function getParameters()
    {
        $request = trim($_SERVER["REQUEST_URI"], "/");
        $request = explode("/", $request);
        $this->requestMethod = $_SERVER["REQUEST_METHOD"];
        $this->dsType = ucfirst($request[1]) . "DS";
        if(!empty($request[2])){
            $this->key = $request[2];
        }
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
     * Make actions and show result by request
     */
    private function showResult()
    {
        header('Content-Type: application/json; charset=utf-8');

        if(!empty($this->key) && $this->requestMethod === "GET"){
            echo $this->deleteItem();
            die();
        } elseif (empty($this->key) && $this->requestMethod === "GET") {
            echo $this->getFullList();
            die();
        }
    }

    /**
     * Delete items from data source by key
     * @return false|string
     */
    private function deleteItem() {
        $result = [
            "status" => true,
            "code" => 500,
            "data" => ""
        ];
        $response = $this->dataSource->delete($this->key);
        if ($response === "1" || $response === "0") {
            http_response_code(200);
            $result["code"] = 200;
        } else {
            http_response_code(500);
            $result["data"] = ["message" => $response];
        }
        return json_encode($result);
    }

    /**
     * Get JSON of items from data source
     * @return false|string
     */
    private function getFullList()
    {
        $allKeys = $this->dataSource->getAll();
        if(empty($allKeys)){
            http_response_code(204);
            $result = [
                "status" => true,
                "code" => 204,
                "data" => ""
            ];
            return json_encode($result);
        }
        http_response_code(200);
        $result = [
            "status" => true,
            "code" => 200
        ];
        $result["data"] = $allKeys;
        return json_encode($result);
    }
}

new Api($config);