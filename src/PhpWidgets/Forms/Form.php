<?php
namespace PhpWidgets\Forms;

use PhpWidgets\Html\Traits\DomVariables;
use PhpWidgets\Html\Traits\DomOptions;
use PhpWidgets\Options;
use PhpWidgets\Widget;

trait FormOptionsVariable {
  use DomVariables;

  protected $children;
  protected $action;
  protected $method;
}

class FormOptions extends DomOptions {
  use FormOptionsVariable, DomVariables;

  public function setChildren (array $children) {
    $this->children = $children;
    return $this;
  }
  public function setAction (string $action) {
    $this->action = $action;
    return $this;
  }
  public function setMethod (string $method) {
    $this->method = $method;
    return $this;
  }
}

class Form extends Widget {
  use FormOptionsVariable, DomVariables;

  public static function options () {
    return new FormOptions();
  }

  public function __construct(FormOptions $options)
  {
    $this->mapOptionsToVariable($options);
  }

  public function render() {
    $otherAttributes = [];
    if ($this->action) {
      array_push($otherAttributes, "action='{$this->action}'");
    }
    if ($this->method) {
      array_push($otherAttributes, "method='{$this->method}'");
    }
    $attr = implode(' ', $otherAttributes);

    $html = "<form {$this->getAttribute($this->options)} {$attr}>";
    foreach ($this->children as $children) {
      $html .=  $children->render();
    }
    return $html . '</form>';
  }

  public function getJavascript()
  {
    return implode("", array_map(function(Widget $item) { return  $item->getJavascript(); }, $this->children));
  }

  public function getCSS()
  {
    return implode("", array_map(function(Widget $item) { return  $item->getCSS(); }, $this->children));
  }

}