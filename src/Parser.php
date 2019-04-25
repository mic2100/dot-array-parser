<?php

namespace Mic2100\DotArrayParser;

use InvalidArgumentException;
use Psr\Log\LoggerInterface;

/**
 * Class Parser
 *
 * @package Mic2100\DotArrayParser
 * @author Mike Bardsley
 * @license MIT
 */
class Parser
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Parser constructor.
     *
     * @param LoggerInterface|null $logger
     */
    public function __construct(LoggerInterface $logger = null)
    {
        $this->logger = $logger;
    }

    /**
     * Parse the syntax and return the value from the array
     *
     * @param string $syntax
     * @param array $array
     * @param null|mixed $default - this value will be returned if this method cannot find a value to return
     *
     * @return mixed
     */
    public function handle(string $syntax, array $array, $default = null)
    {
        if (empty($syntax)) {
            throw new InvalidArgumentException('You need to pass a valid array syntax. e.g. key1.key2.key3');
        }

        try {
            return $this->getValue($this->getKeys($syntax), $array);
        } catch (InvalidArgumentException $exception) {
            if ($this->logger) {
                $this->logger->warning($exception);
            } else {
                error_log($exception);
            }

            return $default;
        }
    }

    /**
     * Get the keys using the dot syntax
     *
     * @param string $syntax
     * @throws InvalidArgumentException - if there are no keys in the syntax
     *
     * @return array
     */
    private function getKeys(string $syntax): array
    {
        return explode('.', $syntax);
    }

    /**
     * Get the value from the array using the keys
     *
     * @param array $keys
     * @param array $array
     * @throws InvalidArgumentException - if the key does not exist
     *
     * @return array|mixed
     */
    private function getValue(array $keys, array $array)
    {
        foreach ($keys as $key) {
            if (!isset($array[$key]) || !array_key_exists($key, $array)) {
                throw new InvalidArgumentException('The key does not exist: ' . $key);
            }

            $array = $array[$key];
        }

        return $array;
    }
}
