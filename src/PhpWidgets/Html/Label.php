<?php
namespace PhpWidgets\Html;

use PhpWidgets\Widget;

class LabelOptions {
  protected $child;
  protected $for;

  public function setChild (Widget $child) {
    $this->child = $child;
    return $this;
  }
  public function setFor ($for) {
    $this->for = $for;
    return $this;
  }
}

class Label extends Widget {
  protected $for;
  /** @var Widget */
  protected $child;

  public static function options () {
    return new LabelOptions();
  }

  public function __construct(LabelOptions $options = null)
  {
    $optionsArr = (function(){
      return [
        'for' => $this->for,
        'child' => $this->child,
      ];
    })->call($options);
    $this->child = $optionsArr['child'];
    $this->for = $optionsArr['for'];
  }

  public function render() {
    return "<label>" . $this->child->render() . "</label>";
  }

}