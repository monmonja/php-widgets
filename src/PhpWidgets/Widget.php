<?php
namespace PhpWidgets;


use PhpWidgets\Html\Traits\DomOptions;

abstract class Widget  {
  protected $options;
  abstract public function render();

  protected function mapOptionsToVariable(Options $options) {
    $this->options = $options;
    $optionsArr = (function(){
      return get_object_vars($this);
    })->call($options);
    foreach ($optionsArr as $key => $value) {
      $this->{$key} = $value;
    }
  }

  public function getWidget() {
    return '';
  }
  
  public function getJavascript() {
    return '';
  }

  public function getCSS() {
    return '';
  }

  public function getAttribute(DomOptions $options = null) {
    $attributes = [];

    if ($options) {
      $optionsArr = (function(){
        return get_object_vars($this);
      })->call($options);
      if (!empty($optionsArr['className'])) {
        array_push($attributes, "class='{$optionsArr['className']}'");
      }
      if (!empty($optionsArr['id'])) {
        array_push($attributes, "id='{$optionsArr['id']}'");
      }
      if (!empty($optionsArr['name'])) {
        array_push($attributes, "name='{$optionsArr['name']}'");
      }

    }
    return implode(' ', $attributes);
  }

}