<?php
namespace PhpWidgets\Admin;

use PhpWidgets\Forms\Input;
use PhpWidgets\Forms\InputOptions;
use PhpWidgets\Options;
use PhpWidgets\Widget;

trait FormInputVariables {
  protected $inputOptions;
  protected $label;
}

class FormInputOptions extends Options {
  use FormInputVariables;

  public function setInputOptions(InputOptions $inputOptions): FormInputOptions  {
    $this->inputOptions = $inputOptions;
    return $this;
  }

  public function setLabel(string $label): FormInputOptions  {
    $this->label = $label;
    return $this;
  }
}

class FormInput extends Widget {
  use FormInputVariables;

  public static function options () {
    return new FormInputOptions();
  }

  public function __construct(FormInputOptions $options)
  {
    $this->mapOptionsToVariable($options);
    if ($this->label == null) { die("FormInput: name is required"); }
    if ($this->inputOptions == null) { die("FormInput: name is required"); }
  }

  public function render() {
    $input = new Input($this->inputOptions);

    return <<<HTML
    <div class="form-row ">
          <label>{$this->label}</label>
          <div class="form-component">
              {$input->render()}
          </div>
      </div>
HTML;
  }

}