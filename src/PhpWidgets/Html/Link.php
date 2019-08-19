<?php
namespace PhpWidgets\Html;

use PhpWidgets\ContainerVariables;
use PhpWidgets\Html\Traits\DomOptions;
use PhpWidgets\Html\Traits\DomVariables;
use PhpWidgets\Widget;


trait LinkVariables {
  use DomVariables;
  /** @var Widget */
  protected $child;
  /** @var string */
  protected $href;
}

class LinkOptions extends DomOptions {
  use LinkVariables;
  protected $child;
  protected $href;

  public function setChild (Widget $child) {
    $this->child = $child;
    return $this;
  }
  public function setHref ($href) {
    $this->href = $href;
    return $this;
  }
}

class Link extends Widget {
  use LinkVariables;
  protected $for;
  /** @var Widget */
  protected $child;

  public static function options () {
    return new LinkOptions();
  }

  public function __construct(LinkOptions $options = null)
  {
    $this->mapOptionsToVariable($options);
  }

  public function render() {
    return "<a href='{$this->href}'>" . $this->child->render() . "</a>";
  }

}