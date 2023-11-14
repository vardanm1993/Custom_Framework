<?php

namespace Core;

class Session
{
    /**
     *  Start the session.
     *
     * @return void
     */
    public static function start(): void
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Set a session variable.
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public static function set(string $key, mixed $value): void
    {
        self::start();
        $_SESSION[$key] = $value;
    }

    /**
     * Get the value of a session variable.
     *
     * @param string $key
     * @param $default
     * @return mixed|null
     */
    public static function get(string $key, $default = null): mixed
    {
        self::start();
        return $_SESSION[$key] ?? $default;
    }

    /**
     *Check if a session variable exists.
     *
     * @param string $key
     * @return bool
     */
    public static function has(string $key): bool
    {
        self::start();
        return isset($_SESSION[$key]);
    }

    /**
     * Remove a session variable.
     *
     * @param string $key
     * @return void
     */
    public static function remove(string $key): void
    {
        if (self::has($key)){
            self::start();
            unset($_SESSION[$key]);
        }
    }

    /**
     * Destroy the session.
     *
     * @return void
     */
    public static function destroy(): void
    {
        if (session_status() == PHP_SESSION_ACTIVE) {
            session_unset();
            session_destroy();
        }
    }


}