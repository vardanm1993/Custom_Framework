<?php

namespace Core;

class Request
{

    /**
     * @param array $server
     * @param array $getParams
     * @param array $postParams
     * @param array $cookie
     * @param array $files
     */
    public function __construct(
        public array $server,
        public array $getParams,
        public array $postParams,
        public array $cookie,
        public array $files
    )
    {
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return strtolower($this->server['REQUEST_METHOD']);
    }

    /**
     * @return string
     */
    public function getUri(): string
    {
        return strtolower($this->server['REQUEST_URI']);
    }

}