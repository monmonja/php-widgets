<?php
namespace PhpWidgets\Admin;

use PhpWidgets\Forms\Input;
use PhpWidgets\Forms\InputOptions;
use PhpWidgets\Html\Traits\DomOptions;
use PhpWidgets\Html\Traits\DomVariables;
use PhpWidgets\Options;
use PhpWidgets\Widget;

trait FormSlugVariables {
  use DomVariables;
  protected $inputOptions;
  protected $label;
  protected $inputId;
  protected $className;
}

class FormSlugOptions extends DomOptions {
  use FormSlugVariables;

  public function setInputOptions(InputOptions $inputOptions): FormSlugOptions  {
    $this->inputOptions = $inputOptions;
    return $this;
  }

  public function setLabel(string $label): FormSlugOptions  {
    $this->label = $label;
    return $this;
  }
  public function setInputId(string $inputId): FormSlugOptions  {
    $this->inputId = $inputId;
    return $this;
  }
}

class FormSlug extends Widget {
  use FormSlugVariables;

  public static function options () {
    return new FormSlugOptions();
  }

  public function getJavascript()
  {
    $optionsArr = (function(){
      return get_object_vars($this);
    })->call($this->inputOptions);

    return <<< JS
$("#{$this->inputId}").on('keyup', function() {
    $("#{$optionsArr['id']}").val($(this).val().replace(/[\W_]+/g, '-'));
});
  
JS;

  }

  public function __construct(FormSlugOptions $options)
  {
    $this->mapOptionsToVariable($options);
    if ($this->label === null) { die("FormSlug: name is required"); }
    if ($this->inputOptions === null) { die("FormSlug: inputOptions is required"); }
    if ($this->inputId === null) { die("FormSlug: inputId is required"); }
  }

  public function render() {
    $input = new Input($this->inputOptions);

    return <<<HTML
    <div id='{$this->id}' class='{$this->className}' >
          <label>{$this->label}</label>
          <div class="form-component">
              {$input->render()}
          </div>
      </div>
HTML;
  }

}