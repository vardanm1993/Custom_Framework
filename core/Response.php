<?php

namespace Core;

use Core\Exception\NotFoundException;

class Response
{
    protected string $content;
    protected int $statusCode;
    protected array $headers;

    public function __construct(string $content = '', int $statusCode = 200, array $headers = [])
    {
        $this->content = $content;
        $this->statusCode = $statusCode;
        $this->headers = $headers;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
    }

    public function setHeaders($headers)
    {
        $this->headers = $headers;
    }

    /**
     * @return void
     * @throws NotFoundException
     */
    public function send(): void
    {
        if ($this->content === ''){
            throw new NotFoundException('404');
        }

        foreach ($this->headers as $header => $value) {
            header("$header: $value");
        }

        http_response_code($this->statusCode);

        echo $this->content;
    }
}