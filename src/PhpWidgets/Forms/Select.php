<?php
namespace PhpWidgets\Forms;

use PhpWidgets\Html\Traits\DomOptions;
use PhpWidgets\Html\Traits\DomVariables;
use PhpWidgets\Options;
use PhpWidgets\Widget;


class SelectFormOptions {
  public $value;
  public $label;

  public static function make($value, $label) : SelectFormOptions{
    $field = new SelectFormOptions();
    $field->value = $value;
    $field->label = $label;
    return $field;
  }
}
trait SelectVariables {
  use DomVariables;
  /** @var SelectFormOptions[] */
  protected $formOptions;
  protected $value;
  protected $name;
}

class SelectOptions extends DomOptions {
  use SelectVariables;


  public function setFormOptions(array $formOptions): SelectOptions  {
    $this->formOptions = $formOptions;
    return $this;
  }

  public function setValue(string $value): SelectOptions  {
    $this->value = $value;
    return $this;
  }

  public function setName(string $name): SelectOptions  {
    $this->name = $name;
    return $this;
  }

}

class Select extends Widget {
  use SelectVariables;

  public static function options () {
    return new SelectOptions();
  }

  public function __construct(SelectOptions $options)
  {
    $this->mapOptionsToVariable($options);
    if ($this->formOptions == null) { die("Select: form options is required"); }
  }

  public function render() {
    $optionStr = "";
    foreach($this->formOptions as $options) {
      $selected = $options->value == $this->value ? 'selected="selected"': '';
      $optionStr .= "<option value='{$options->value}' $selected>{$options->label}</option>";
    }
    return <<<HTML
<select {$this->getAttribute($this->options)}>{$optionStr}</select>
HTML;
  }

}