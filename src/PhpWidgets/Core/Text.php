<?php
namespace PhpWidgets;


class TextOptions {

}

class Text extends Widget {
  protected $text;

  public static function options () {
    return new TextOptions();
  }

  public function __construct(string $text, TextOptions $options = null)
  {
    $this->text = $text;
//    $optionsArr = (function(){
//      return [
//        'for' => $this->for,
//        'child' => $this->child,
//      ];
//    })->call($options);
//    $this->child = $optionsArr['child'];
//    $this->for = $optionsArr['for'];
  }

  public function render() {
    return $this->text;
  }

}