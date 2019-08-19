<?php
namespace PhpWidgets\Html;

use PhpWidgets\Html\Traits\DomOptions;
use PhpWidgets\Html\Traits\DomVariables;
use PhpWidgets\Widget;

trait HeaderVariables {
  use DomVariables;

  /** @var Widget */
  protected $child;

  /** @var string */
  protected $tagName = "h1";
}

class HeaderOptions extends DomOptions {
  use HeaderVariables;


  public function setChild (Widget $child) {
    $this->child = $child;
    return $this;
  }
  public function setTagName (string $tagName) {
    $this->tagName = $tagName;
    return $this;
  }
}

class Header extends Widget {
  use HeaderVariables;


  public static function options () {
    return new HeaderOptions();
  }

  public function __construct(HeaderOptions $options = null)
  {
    $this->mapOptionsToVariable($options);
  }

  public function render() {
    return "<{$this->tagName}>" . $this->child->render() . "</{$this->tagName}>";
  }

}