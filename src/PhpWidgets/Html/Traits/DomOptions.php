<?php
namespace PhpWidgets\Html\Traits;

use PhpWidgets\Options;

class DomOptions extends Options {
  use DomVariables;

  public function setId (string $id)  {
    $this->id = $id;
    return $this;
  }

  public function setClassName(string $className)  {
    $this->className = $className;
    return $this;
  }

  public function addClass(string $className)  {
    if (empty($this->className)) {
      $this->className = $className;
    } else {
      $this->className .= ' ' . $className;
    }
    return $this;
  }
}
