<?php

namespace Mic2100\DotArrayParser;

use InvalidArgumentException;

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
     * Parse the syntax and return the value from the array
     *
     * @param string $syntax
     * @param array $array
     * @throws InvalidArgumentException - if the syntax is empty
     *
     * @return mixed
     */
    public function handle(string $syntax, array $array)
    {
        if (empty($syntax)) {
            throw new InvalidArgumentException('You need to pass some array syntax');
        }

        return $this->getValue($this->getKeys($syntax), $array);
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
        $keys = [];
        foreach (explode('.', $syntax) as $key) {
            $keys[] = $key;
        }

        if (empty($keys)) {
            throw new InvalidArgumentException('There are no keys passed in the syntax: ' . var_export($syntax, true));
        }

        return $keys;
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
        $value = $array;
        foreach ($keys as $key) {
            if (!isset($value[$key]) || !array_key_exists($key, $value)) {
                throw new InvalidArgumentException('The key does not exist: ' . $key);
            }

            $value = $value[$key];
        }

        return $value;
    }
}
