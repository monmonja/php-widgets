<?php
namespace PhpWidgets\Admin;

use PhpWidgets\Forms\DateTime;
use PhpWidgets\Forms\InputOptions;
use PhpWidgets\Forms\Input;
use PhpWidgets\Options;
use PhpWidgets\Widget;

trait FormDateTimeVariables {
  /** @var InputOptions */
  protected $dateOptions;
  /** @var InputOptions */
  protected $timeOptions;
  protected $label;
}

class FormDateTimeOptions extends Options {
  use FormDateTimeVariables;

  public function setDateOptions(InputOptions $dateOptions): FormDateTimeOptions  {
    $this->dateOptions = $dateOptions;
    return $this;
  }

  public function setTimeOptions(InputOptions $timeOptions): FormDateTimeOptions  {
    $this->timeOptions = $timeOptions;
    return $this;
  }

  public function setLabel(string $label): FormDateTimeOptions  {
    $this->label = $label;
    return $this;
  }
}

class FormDateTime extends Widget {
  use FormDateTimeVariables;

  public static function options () {
    return new FormDateTimeOptions();
  }

  public function __construct(FormDateTimeOptions $options)
  {
    $this->mapOptionsToVariable($options);
    if ($this->label == null) { die("FormDateTime: label is required"); }
    if ($this->timeOptions == null) { die("FormDateTime: timeOptions is required"); }
    if ($this->dateOptions == null) { die("FormDateTime: dateOptions is required"); }
  }

  public function render() {
    $this->dateOptions->addClass('date');
    $this->dateOptions->setAutoComplete('off');
    $this->timeOptions->addClass('timepicker');
    $this->timeOptions->setAutoComplete('off');
    $date = new Input($this->dateOptions);
    $time = new Input($this->timeOptions);

    return <<<HTML
    <div class="form-row ">
          <label>{$this->label}</label>
          <div class="form-component">
          <table class="form-component">
    <tr>
    <td>{$date->render()}</td>
    <td>{$time->render()}</td>
</tr>
</table>
          </div>
      </div>
HTML;
  }

}