<?php
namespace PhpWidgets\Core;

use PhpWidgets\Html\Traits\DomOptions;
use PhpWidgets\Html\Traits\DomVariables;
use PhpWidgets\Widget;

trait StackVariables {
  use DomVariables;
  /** @var Widget[] */
  protected $children;
  /** @var string */
  protected $rowClass;
}

class StackOptions extends DomOptions {
  use StackVariables;

  public function setChildren (array $children) {
    $this->children = $children;
    return $this;
  }
  public function setRowClass (string $rowClass) {
    $this->rowClass = $rowClass;
    return $this;
  }
}

class Stack extends Widget {
  use StackVariables;

  public static function options () {
    return new StackOptions();
  }

  public function __construct(StackOptions $options) {
    $this->mapOptionsToVariable($options);
  }

  public function render() {
    $html = "";
    /** @var Widget $children */
    foreach ($this->children as $children) {
      if (!empty($this->rowClass)) {
        $children->options->addClass($this->rowClass);
      }
      $html .= $children->render();
    }
    return $html;
  }

  public function getCSS() {
    return implode("", array_map(function(Widget $item) { return  $item->getCSS(); }, $this->children));
  }
  
  public function getJavascript()
  {
    return implode("", array_map(function(Widget $item) { return  $item->getJavascript(); }, $this->children));
  }

}