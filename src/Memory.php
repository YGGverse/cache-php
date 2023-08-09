<?php

declare(strict_types=1);

namespace YGGverse\Cache;

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

  public function get(string $key, mixed $value = null, int $timeout = null) : mixed
  {
    if (false === $result = $this->_memcached->get($this->_key($key)))
    {
      if (true === $this->set($key, $value, $timeout))
      {
        return $value;
      }
      else
      {
        return false;
      }
    }
    else
    {
      return $result;
    }
  }

  public function set(string $key, mixed $value, int $timeout = null)
  {
    return $this->_memcached->set($this->_key($key), $value, ($timeout ? $timeout : $this->_timeout) + time());
  }

  public function delete(string $key) : bool
  {
    return $this->_memcached->delete($this->_key($key));
  }

  public function flush(int $delay = 60)
  {
    return $this->_memcached->flush();
  }

  private function _key(string $key) : string
  {
    return sprintf('%s.%s', $this->_namespace, $key);
  }
}