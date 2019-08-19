<?php
namespace PhpWidgets\Forms;

use PhpWidgets\Html\Traits\DomOptions;
use PhpWidgets\Html\Traits\DomVariables;
use PhpWidgets\Options;
use PhpWidgets\Widget;

trait ButtonVariables {
  use DomVariables;
  protected $text;
}

class ButtonOptions extends DomOptions {
  use ButtonVariables;

  public function setText(string $text): ButtonOptions  {
    $this->text = $text;
    return $this;
  }
}

class Button extends Widget {
  use ButtonVariables;

  public static function options () {
    return new ButtonOptions();
  }

  public function __construct(ButtonOptions $options)
  {
    $this->mapOptionsToVariable($options);
    if ($this->text == null) { die("Button: text is required"); }
  }

  public function render() {
    return <<<HTML
<button {$this->getAttribute($this->options)} >{$this->text}</button>
HTML;
  }

}