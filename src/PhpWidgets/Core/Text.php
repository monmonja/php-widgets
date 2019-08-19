<?php
namespace PhpWidgets;



class Text extends Widget {
  protected $text;


  public function __construct(string $text)
  {
    $this->text = $text;
  }

  public function render() {
    return $this->text;
  }

}