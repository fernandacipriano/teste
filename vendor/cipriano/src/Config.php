<?php

namespace Cipriano;

class Config
{

  private $configs = [];

  public function __construct(array $configs) {
    foreach ($configs as $name => $config) {
      $this->set($name, $config);
    }
  }

  public static function load($configs) {
    // exit($configs);
    if (is_string($configs) && is_file($configs)) {
      if (!file_exists($configs)) {
        throw new \InvalidArgumentException(sprintf(
          'Ops! File of configurations not exists in "%s', $configs
        ));
      }

      $ext = pathinfo($configs, PATHINFO_EXTENSION);

      switch ($ext) {
        case 'env':
        $configs = parse_ini_file($configs, true);
        break;
        case 'php':
        $configs = include $configs;
        break;
        default:
        throw new \InvalidArgumentException(sprintf(
          'Extension "%s" not supported', $ext
        ));
        break;
      }
    } elseif (!is_array($configs)) {
      throw new \InvalidArgumentException(sprintf(
        'Type "%s" not supported for configurations', gettype($configs)
      ));
    }

    return new self($configs);
  }

  public function set($name, $value) {
    if (is_array($value)) {
      $value = new self($value);
    }

    $this->configs[$name] = $value;
  }

  public function get($name) {
    if (!isset($this->configs[$name])) {
      throw new \InvalidArgumentException(sprintf(
        'Configuration not found for "%s"', $name
      ));
    }

    return $this->configs[$name];
  }

  public function __set($name, $value) {
    $this->set($name, $value);
  }

  public function __get($name) {
    return $this->get($name);
  }
}
