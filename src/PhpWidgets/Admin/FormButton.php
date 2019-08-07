<?php
namespace PhpWidgets\Admin;

use PhpWidgets\Options;
use PhpWidgets\Widget;

trait FormButtonVariables {
  protected $label;
}

class FormButtonOptions extends Options {
  use FormButtonVariables;

  public function setLabel(string $label): FormButtonOptions  {
    $this->label = $label;
    return $this;
  }
}

class FormButton extends Widget {
  use FormButtonVariables;

  public static function options () {
    return new FormButtonOptions();
  }

  public function __construct(FormButtonOptions $options)
  {
    $this->mapOptionsToVariable($options);
  }

  public function render() {
    $label = $this->label ?? 'Submit';
    return <<<HTML
    <div class="clearfix form-submit text-center controls">
        <button type="submit">{$label}</button>
    </div>
HTML;
  }

}