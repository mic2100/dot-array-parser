<?php

namespace Mic2100\DotArrayParser;

class Parser
{
    public function handle(string $syntax, array $array)
    {
        if (empty($syntax)) {
            throw new \InvalidArgumentException('You need to pass some array syntax');
        }

        return $this->getValue($this->getKeys($syntax), $array);
    }

    private function getKeys(string $syntax): array
    {
        $keys = [];
        foreach (explode('.', $syntax) as $key) {
            $keys[] = $key;
        }

        if (empty($keys)) {
            throw new \InvalidArgumentException('There are no keys passed in the syntax: ' . var_export($syntax, true));
        }

        return $keys;
    }

    private function getValue(array $keys, array $array)
    {
        $value = $array;
        foreach ($keys as $key) {
            if (!isset($value[$key]) || !array_key_exists($key, $value)) {
                throw new \InvalidArgumentException('The key does not exist: ' . $key);
            }

            $value = $value[$key];
        }

        return $value;
    }
}