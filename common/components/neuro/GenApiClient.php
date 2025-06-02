<?php

namespace common\components\neuro;

use GenAPI\Client;
use GenAPI\Enums\Http\EndpointsEnum;
use GenAPI\Enums\Http\HttpMethods;
use GenAPI\Exceptions\BadRequestException;
use GenAPI\Exceptions\BaseException;
use GenAPI\Exceptions\InternalServerError;
use GenAPI\Exceptions\NotFoundException;
use GenAPI\Exceptions\TooManyRequestsException;
use GenAPI\Exceptions\UnauthorizedException;

class GenApiClient extends Client
{
    /**
     * Creating a network task.
     *
     * @param string $networkId - unique model identifier.
     * @param array $parameters - request parameters.
     * @return array|null
     * @throws BadRequestException
     * @throws BaseException
     * @throws InternalServerError
     * @throws JsonException
     * @throws NotFoundException
     * @throws TooManyRequestsException
     * @throws UnauthorizedException
     */
    public function createNetworkTask(string $networkId, array $parameters): ?array
    {
        $body = $this->encodeData($parameters);

        $response = $this->execute(EndpointsEnum::CREATE_NETWORK_TASK_PATH . '/' . $networkId, HttpMethods::POST, [], $body);

        return $response->isOk()
            ? $this->decodeData($response)
            : $this->handleError($response);
    }
}