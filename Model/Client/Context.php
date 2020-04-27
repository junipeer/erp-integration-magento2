<?php

namespace Junipeer\ErpIntegration\Model\Client;


class Context
{
    /**
     * @var \Junipeer\ErpIntegration\Helper\Data
     */
    protected $helper;

    /**
     * @var\Junipeer\ErpIntegration\Logger\Logger
     */
    protected $logger;


   /**
     * Constructor
     *
     * @param \Junipeer\ErpIntegration\Helper\Data $helper
     * @param \Junipeer\ErpIntegration\Logger\Logger $logger
     *
     */
    public function __construct(
        \Junipeer\ErpIntegration\Helper\Data $helper,
        \Junipeer\ErpIntegration\Logger\Logger $logger
    ) {
        $this->helper        = $helper;
        $this->logger = $logger;

    }

    /**
     * @return \Junipeer\ErpIntegration\Helper\Data
     */
    public function getHelper()
    {
        return $this->helper;
    }

    /**
     * @return \Junipeer\ErpIntegration\Logger\Logger
     */
    public function getLogger()
    {
        return $this->logger;
    }

}
