<?php

namespace WpToolKit\Controller;

use RuntimeException;

class HttpClient
{
    /**
     * @param array<string, mixed> $args
     */
    public function get(string $url, array $args = []): mixed
    {
        return wp_remote_get($url, $args);
    }

    /**
     * @param array<string, mixed> $args
     */
    public function post(string $url, array $args = []): mixed
    {
        return wp_remote_post($url, $args);
    }

    /**
     * @param array<string, mixed> $args
     */
    public function request(string $url, array $args = []): mixed
    {
        return wp_remote_request($url, $args);
    }

    public function getBody(mixed $response): string
    {
        if (is_wp_error($response)) {
            throw new RuntimeException($response->get_error_message());
        }

        return (string) wp_remote_retrieve_body($response);
    }

    /**
     * @return array<string, mixed>
     */
    public function getJson(string $url, array $args = []): array
    {
        $body = $this->getBody($this->get($url, $args));
        $decoded = json_decode($body, true);

        if (!is_array($decoded)) {
            throw new RuntimeException('HTTP response JSON could not be decoded into an array.');
        }

        return $decoded;
    }
}
