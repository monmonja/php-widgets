<?php
namespace PhpWidgets\Html;

use PhpWidgets\Widget;

trait ScaffoldVariables {
  protected $overlays;
  protected $body;
}

class ScaffoldOptions {
  use ScaffoldVariables;
  public function setBody (Widget $body) {
    $this->body = $body;
    return $this;
  }

  public function setOverlays (array $overlays) {
    $this->overlays = $overlays;
    return $this;
  }
}

class Scaffold extends Widget {
  use ScaffoldVariables;

  public static function options () {
    return new ScaffoldOptions();
  }

  public function __construct(LinkOptions $options = null)
  {
    $this->mapOptionsToVariable($options);
  }

  public function render() {
    return <<<EOF
<!doctype html>
<html>
<head>

</head>
<div>
    {$this->renderBody()}
    {$this->renderOverlay()}
</div>
</html>
EOF;
  }

  private function renderBody () {
    if ($this->body != null) {
      return $this->body->render();
    }
  }

  private function renderOverlay () {
    $html = "";
    foreach ($this->overlays as $overlay) {
      $html .= $overlay->render();
    }
    return $html;
  }

}