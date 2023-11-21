<?php

namespace Core;

use Core\Exception\NotFoundException;

class Response
{
    protected int $statusCode = 200;
    protected array $headers = [];
    protected string $view;

    /**
     * @param int $code
     * @return void
     */
    public function setStatusCode(int $code): void
    {
        $this->statusCode = $code;
    }

    /**
     * @param string $name
     * @param string $value
     * @return void
     */
    public function setHeader(string $name, string $value): void
    {
        $this->headers[$name] = $value;
    }


    /**
     * @param string $view
     * @return void
     * @throws NotFoundException
     */
    public function send(string $view): void
    {
        http_response_code($this->statusCode);

        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }

        if (!$view){
            throw new NotFoundException('404');
        }

        echo $view;
    }
}