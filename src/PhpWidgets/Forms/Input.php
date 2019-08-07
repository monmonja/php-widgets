<?php
namespace PhpWidgets\Forms;

use PhpWidgets\Html\Traits\DomOptions;
use PhpWidgets\Html\Traits\DomVariables;
use PhpWidgets\Options;
use PhpWidgets\Widget;

trait InputVariables {
  use DomVariables;

  protected $placeholder;
  protected $autoComplete;
  protected $name;
  protected $value;
  protected $type = 'text';
}

class InputOptions extends DomOptions {
  use InputVariables;

  public function setPlaceholder(string $placeholder): InputOptions  {
    $this->placeholder = $placeholder;
    return $this;
  }
  public function setName(string $name): InputOptions  {
    $this->name = $name;
    return $this;
  }
  public function setValue(string $value): InputOptions  {
    $this->value = $value;
    return $this;
  }
  public function setType(string $type): InputOptions  {
    $this->type = $type;
    return $this;
  }

  public function setAutoComplete(string $autoComplete): InputOptions  {
    $this->autoComplete = $autoComplete;
    return $this;
  }

}

class Input extends Widget {
  use InputVariables;

  public static function options () {
    return new InputOptions();
  }

  public function __construct(InputOptions $options)
  {
    $this->mapOptionsToVariable($options);
    if ($this->name == null) { die("Input: name is required"); }
  }

  public function render() {
    $otherAttributes = [];
    if ($this->name) {
      array_push($otherAttributes, "name='{$this->name}'");
    }

    if ($this->placeholder) {
      array_push($otherAttributes, "placeholder='{$this->placeholder}'");
    }
    if ($this->autoComplete) {
      array_push($otherAttributes, "autocomplete='{$this->autoComplete}'");
    }
    if ($this->type) {
      array_push($otherAttributes, "type='{$this->type}'");
    }

    if ($this->value) {
      array_push($otherAttributes, "value='{$this->value}'");
    }
    $attr = implode(' ', $otherAttributes);

    return <<<HTML
<input {$this->getAttribute($this->options)} {$attr}>
HTML;
  }

}