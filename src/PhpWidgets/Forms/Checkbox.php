<?php
namespace PhpWidgets\Forms;

use PhpWidgets\Html\Traits\DomOptions;
use PhpWidgets\Html\Traits\DomVariables;
use PhpWidgets\Options;
use PhpWidgets\Widget;

trait CheckboxVariables {
  use DomVariables;

  protected $label;
  protected $name;
  protected $value;
}

class CheckboxOptions extends DomOptions {
  use CheckboxVariables;

  public function setName(string $name): CheckboxOptions  {
    $this->name = $name;
    return $this;
  }

  public function setValue(string $value): CheckboxOptions  {
    $this->value = $value;
    return $this;
  }

  public function setLabel(string $label): CheckboxOptions  {
    $this->label = $label;
    return $this;
  }

}

class Checkbox extends Widget {
  use CheckboxVariables;

  public static function options () {
    return new CheckboxOptions();
  }

  public function __construct(CheckboxOptions $options)
  {
    $this->mapOptionsToVariable($options);
    if ($this->name == null) { die("Checkbox: name is required"); }
  }

  public function render() {
    $otherAttributes = [];
    if ($this->name) {
      array_push($otherAttributes, "name='{$this->name}'");
    }
    if ($this->value) {
      array_push($otherAttributes, "value='{$this->value}'");
    }
    $attr = implode(' ', $otherAttributes);

    return <<<HTML
<label {$this->getAttribute($this->options)}>
    <input type="checkbox" {$attr}>
    {$this->label}
</label>
HTML;
  }

}