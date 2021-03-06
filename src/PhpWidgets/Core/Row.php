<?php
namespace PhpWidgets\Core;

use PhpWidgets\Html\Traits\DomOptions;
use PhpWidgets\Html\Traits\DomVariables;
use PhpWidgets\Widget;

trait RowVariables {
  use DomVariables;
  /** @var Widget[] */
  protected $children;
  /** @var DomOptions */
  protected $rowOptions;
}

class RowOptions extends DomOptions {
  use RowVariables;

  public function setChildren (array $children) {
    $this->children = $children;
    return $this;
  }
  public function setRowOptions (DomOptions $rowOptions) {
    $this->rowOptions = $rowOptions;
    return $this;
  }
}

class Row extends Widget {
  use RowVariables;

  public static function options () {
    return new RowOptions();
  }

  public function __construct(RowOptions $options) {
    $this->mapOptionsToVariable($options);
    if ($this->rowOptions == null) {
      $this->rowOptions = new DomOptions();
      $this->rowOptions->setClassName("row-item");
    }
  }

  public function render() {
    $html = "<div {$this->getAttribute($this->options)}>";
    foreach ($this->children as $children) {
      $html .= "<div {$this->getAttribute($this->rowOptions)}>" . $children->render() . "</div>";
    }
    return $html . '</div>';
  }


  public function getCSS() {
    return implode("", array_map(function(Widget $item) { return  $item->getCSS(); }, $this->children));
  }

  public function getJavascript()
  {
    return implode("", array_map(function(Widget $item) { return  $item->getJavascript(); }, $this->children));
  }
}