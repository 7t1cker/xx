<?php

use Psr\Log\LoggerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
class LoggingMiddleware
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function __invoke(Request $request, $handler)
    {
        $response = $handler->handle($request);
        $this->logger->info('Response status: ' . $response->getStatusCode());

        return $response;
    }
}