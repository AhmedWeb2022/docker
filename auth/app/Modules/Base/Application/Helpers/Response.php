<?php

use App\Modules\Base\Application\Response\DataFailed;
use App\Modules\Base\Application\Response\DataSuccess;

function DataSuccess($status, $message, $data = null, $resourceData = null)
{
    return new DataSuccess(
        status: $status,
        message: $message,
        data: $data,
        resourceData: $resourceData
    );
}

function DataFailed($status, $message, $statusCode = 400)
{
    return new DataFailed(
        status: $status,
        message: $message,
        statusCode: $statusCode
    );
}
