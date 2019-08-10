<?php
namespace PhpWidgets\Admin;

use PhpWidgets\Forms\Select;
use PhpWidgets\Forms\SelectOptions;
use PhpWidgets\Options;
use PhpWidgets\Widget;

trait FormSelectVariables {
  protected $selectOptions;
  protected $label;
}

class FormSelectOptions extends Options {
  use FormSelectVariables;

  public function setSelectOptions(SelectOptions $selectOptions): FormSelectOptions  {
    $this->selectOptions = $selectOptions;
    return $this;
  }

  public function setLabel(string $label): FormSelectOptions  {
    $this->label = $label;
    return $this;
  }
}

class FormSelect extends Widget {
  use FormSelectVariables;

  public static function options () {
    return new FormSelectOptions();
  }

  public function __construct(FormSelectOptions $options)
  {
    $this->mapOptionsToVariable($options);
    if ($this->label == null) { die("FormSelect: label is required"); }
    if ($this->selectOptions == null) { die("FormSelect: selectOptions is required"); }
  }

  public function render() {
    $select = new Select($this->selectOptions);

    return <<<HTML
    <div class="form-row ">
          <label>{$this->label}</label>
          <div class="form-component">
              {$select->render()}
          </div>
      </div>
HTML;
  }

}