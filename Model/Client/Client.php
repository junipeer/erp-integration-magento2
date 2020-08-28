<?php
namespace Junipeer\ErpIntegration\Model\Client;


use GuzzleHttp\RequestOptions;
use Junipeer\ErpIntegration\Model\Client\Request\AbstractRequest;

/**
 * Class Client
 * @package Junipeer\ErpIntegration\Model\Api
 */
abstract class Client
{
    /**
     * @var int
     */
    protected $timeout = 30;

    /** @var Context $apiContext */
    protected $apiContext;

    /** @var string $apiUsername */
    private $apiUsername;

    /** @var string $apiPassword */
    private $apiPassword;

    protected $apiPath = "";

    /** @var \GuzzleHttp\Client $httpClient */
    private $httpClient;

    /**
     * Constructor
     *
     * @param Context $apiContext
     *
     */
    public function __construct(
        Context $apiContext
    ) {
        $this->apiContext = $apiContext;
    }

    /**
     * @param $baseUrl
     */
    private function setGuzzleHttpClient($baseUrl)
    {
        $this->httpClient = new \GuzzleHttp\Client([
            'base_uri' => $baseUrl,
            'verify' => false,
        ]);
    }


    /**
     * @param $endpoint
     * @param array $params
     * @param bool $useTenant
     * @return string
     */
    protected function buildEndpoint($endpoint, $params = []) {
        $endpoint = rtrim($this->apiPath, "/") . "/". ltrim($endpoint, "/");
        $buildEndpoint = ltrim($endpoint, "/");
        if (!empty($params)) {
            $query =  http_build_query($params);
            $buildEndpoint .= "?" . $query;
        }

        return $buildEndpoint;
    }

    protected function shouldInitClient()
    {
        if (!$this->httpClient || !$this->apiUsername || !$this->apiPassword) {
            return true;
        }

        return false;
    }

    /**
     * @param null $store
     * @throws \Exception
     */
    public function initClient($store = null)
    {
        $this->apiUsername = $this->getHelper()->getApiUsername($store);
        $this->apiPassword = $this->getHelper()->getApiPassword($store);

        $url = $this->getHelper()->getApiUrl($store);
        $path = $this->getHelper()->getApiUrlPath($store);
        if (!$path) {
            throw new \Exception("Please add a api path in configuration.");
        }

        if (!$this->apiUsername || !$this->apiPassword) {
            throw new \Exception("Please add api keys in configuration.");
        }

        $this->apiPath = $path;
        $this->setGuzzleHttpClient($url);
    }

    /**
     * @param $endpoint
     * @param AbstractRequest $request
     * @param array $options
     * @return string
     * @throws \Exception
     */
    protected function post($endpoint, AbstractRequest $request, $options = []){

        if ($this->shouldInitClient()) {
            $this->initClient();
        }

        if (!is_array($options)) {
            $options = [];
        }

        $options = array_merge($options, $this->getDefaultOptions());
        $options[RequestOptions::JSON] = $request->toArray();

        // todo catch exceptions or let them be catched by magento?
        try {
            $result = $this->httpClient->post($endpoint, $options);
        } catch (\Exception $e) {
            $this->getLogger()->error("Failed sending request to the integration: POST $endpoint");
            $this->apiContext->getLogger()->error(json_encode($this->removeAuthForLogging($options)));
            $this->getLogger()->error($request->toJSON());
            $this->apiContext->getLogger()->error("Message: " . $e->getMessage());
            throw $e;
        }

        return $result->getBody()->getContents();
    }

    /**
     * @return mixed
     */
    protected function getDefaultOptions()
    {
        $options['auth'] = [
            $this->apiUsername,
            $this->apiPassword
        ];

        return $options;
    }

    private function removeAuthForLogging($options) {
        if (isset($options['auth'])) {
            unset($options['auth']);
        }

        return $options;
    }

    /**
     * @return \Junipeer\ErpIntegration\Logger\Logger
     */
    public function getLogger()
    {
        return $this->apiContext->getLogger();
    }

    /**
     * @return \Junipeer\ErpIntegration\Helper\Data
     */
    public function getHelper()
    {
        return $this->apiContext->getHelper();
    }

}









