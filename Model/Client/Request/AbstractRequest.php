<?php
namespace Junipeer\ErpIntegration\Model\Client\Request;


abstract class AbstractRequest
{
    // do stuff

    public abstract function toJSON();
    public abstract function toArray();
}
