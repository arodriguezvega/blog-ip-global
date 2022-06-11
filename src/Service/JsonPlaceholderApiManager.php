<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class JsonPlaceholderApiManager
{
    private HttpClientInterface $client;
    private array $options;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
        $this->options = [
            'headers' => [
                'Accept'       => 'application/json',
                'Content-Type' => 'application/json'
            ]
        ];
    }

    public function postList(): JsonResponse
    {
        try {
            $response = $this->client->request(
                'GET',
                'https://jsonplaceholder.typicode.com/posts/',
                $this->options
            );

            return new JsonResponse(
                $response->toArray(),
                $response->getStatusCode()
            );

        } catch (TransportExceptionInterface|ClientExceptionInterface|RedirectionExceptionInterface|ServerExceptionInterface|DecodingExceptionInterface $e) {
            return new JsonResponse(
                $e->getTrace(),
                $e->getCode()
            );
        }
    }

    public function postInfo($id): JsonResponse
    {
        try {
            $response = $this->client->request(
                'GET',
                'https://jsonplaceholder.typicode.com/posts/'.$id,
                $this->options
            );

            return new JsonResponse(
                $response->toArray(),
                $response->getStatusCode()
            );
        } catch (TransportExceptionInterface|ClientExceptionInterface|RedirectionExceptionInterface|ServerExceptionInterface|DecodingExceptionInterface $e) {
            return new JsonResponse(
                $e->getTrace(),
                $e->getCode()
            );
        }
    }

    public function userInfo($userId) : JsonResponse
    {
        try {
            $response = $this->client->request(
                'GET',
                'https://jsonplaceholder.typicode.com/users/'.$userId,
                $this->options
            );

            return new JsonResponse(
                $response->toArray(),
                $response->getStatusCode()
            );

        } catch (TransportExceptionInterface|ClientExceptionInterface|RedirectionExceptionInterface|ServerExceptionInterface|DecodingExceptionInterface $e) {
            return new JsonResponse(
                $e->getTrace(),
                $e->getCode()
            );
        }
    }

    public function createPost($params) : JsonResponse
    {
        try {
            $this->options['body'] = json_encode([
                'userId' => $params['userId'],
                'id'     => $params['id'],
                'title'  => $params['title'],
                'body'   => $params['body']
            ]);

            $response = $this->client->request(
                'POST',
                'https://jsonplaceholder.typicode.com/posts',
                $this->options
            );

            return new JsonResponse(
                $response->getContent(),
                $response->getStatusCode()
            );

        } catch (TransportExceptionInterface|ClientExceptionInterface|RedirectionExceptionInterface|ServerExceptionInterface $e) {
            return new JsonResponse(
                $e->getTrace(),
                $e->getCode()
            );
        }
    }
}