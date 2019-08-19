<?php
namespace PhpWidgets\Core;

use PhpWidgets\Html\Traits\DomOptions;
use PhpWidgets\Html\Traits\DomVariables;
use PhpWidgets\Widget;

trait ColumnVariables {
  use DomVariables;
  /** @var Widget[] */
  protected $children;
  /** @var DomOptions */
  protected $rowOptions;
}

class ColumnOptions extends DomOptions {
  use ColumnVariables;

  public function setChildren (array $children) {
    $this->children = $children;
    return $this;
  }
  public function setColumnOptions (DomOptions $rowOptions) {
    $this->rowOptions = $rowOptions;
    return $this;
  }
}

class Column extends Widget {
  use ColumnVariables;

  public static function options () {
    return new ColumnOptions();
  }

  public function __construct(ColumnOptions $options) {
    $this->mapOptionsToVariable($options);
    if ($this->rowOptions == null) {
      $this->rowOptions = new DomOptions();
      $this->rowOptions->setClassName("column-item");
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