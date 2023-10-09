<?php

declare(strict_types=1);

namespace Yggverse\Cache;

class Memory {

  private $_memcached;
  private $_namespace;
  private $_timeout;

  public function __construct(string $host, int $port, string $namespace, int $timeout)
  {
    $this->_memcached = new \Memcached();
    $this->_memcached->addServer($host, $port);

    $this->_namespace = $namespace;
    $this->_timeout   = $timeout;
  }

  public function get(string $key) : mixed
  {
    $key = $this->_setKey(
      [
        $this->_namespace,
        $key
      ]
    );

    return $this->_memcached->get($key);
  }

  public function set(string $key, mixed $value = null, int $timeout = null) : bool
  {
    $key = $this->_setKey(
      [
        $this->_namespace,
        $key
      ]
    );

    return $this->_memcached->set($key, $value, ($timeout ? $timeout : $this->_timeout));
  }

  public function delete(string $key) : bool
  {
    $key = $this->_setKey(
      [
        $this->_namespace,
        $key
      ]
    );

    return $this->_memcached->delete($key);
  }

  public function getByValueCallback(string $key, mixed $value = null, int $timeout = null) : mixed
  {
    $key = $this->_setKey(
      [
        $this->_namespace,
        $key
      ]
    );

    if (false !== $value = $this->_memcached->get($key))
    {
      return $value;
    }
    else
    {
      if (true === $this->_memcached->set($key, $value, ($timeout ? $timeout : $this->_timeout)))
      {
        return $value;
      }
      else
      {
        return false;
      }
    }
  }

  public function getByMethodCallback(object $object, string $method, array $arguments = [], int $timeout = null) : mixed
  {
    $key = $this->_setKey(
      [
        $this->_namespace,
        $object,
        $method,
        $arguments
      ]
    );

    if (false !== $value = $this->_memcached->get($key))
    {
      return $value;
    }
    else
    {
      $value = call_user_func_array(
        [
          $object,
          $method
        ],
        $arguments
      );

      if (true === $this->_memcached->set($key, $value, ($timeout ? $timeout : $this->_timeout)))
      {
        return $value;
      }
      else
      {
        return false;
      }
    }
  }

  public function flush(int $delay = 60) : bool
  {
    return $this->_memcached->flush();
  }

  private function _setKey(mixed $key) : string
  {
    return md5(
      json_encode($key)
    );
  }
}