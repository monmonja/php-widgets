<?php
namespace PhpWidgets\Html;

use PhpWidgets\Html\Traits\DomOptions;
use PhpWidgets\Html\Traits\DomVariables;
use PhpWidgets\Widget;

trait UlVariables {
  use DomVariables;
  /** @var Widget[] */
  protected $children;
  /** @var DomOptions */
  protected $rowOptions;
}

class UlOptions extends DomOptions {
  use UlVariables;

  public function setChildren (array $children) {
    $this->children = $children;
    return $this;
  }

  public function setUlOptions (DomOptions $rowOptions) {
    $this->rowOptions = $rowOptions;
    return $this;
  }
}

class Ul extends Widget {
  use UlVariables;

  public static function options () {
    return new UlOptions();
  }

  public function __construct(UlOptions $options) {
    $this->mapOptionsToVariable($options);
    if ($this->rowOptions == null) {
      $this->rowOptions = new DomOptions();
      $this->rowOptions->setClassName("row-item");
    }
  }

  public function render() {
    $html = "<ul {$this->getAttribute($this->options)}>";
    foreach ($this->children as $children) {
      $html .= "<li {$this->getAttribute($this->rowOptions)}>" . $children->render() . "</li>";
    }
    return $html . '</ul>';
  }

}