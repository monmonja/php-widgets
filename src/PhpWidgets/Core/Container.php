<?php
namespace PhpWidgets\Core;

use PhpWidgets\Html\Traits\DomOptions;
use PhpWidgets\Html\Traits\DomVariables;
use PhpWidgets\Widget;

trait ContainerVariables {
  use DomVariables;
  /** @var Widget */
  protected $child;
}

class ContainerOptions extends DomOptions {
  use ContainerVariables;

  public function setChild ($child) {
    $this->child = $child;
    return $this;
  }
}

class Container extends Widget {
  use ContainerVariables;

  public static function options () {
    return new ContainerOptions();
  }

  public function __construct(ContainerOptions $options = null)
  {
    $this->mapOptionsToVariable($options);
  }

  public function render() {
    $html = $this->child != null ? $this->child->render(): "";
    return "<div id='{$this->id}' class='{$this->className}'>{$html}</div>";
  }

}