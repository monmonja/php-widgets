<?php
namespace PhpWidgets;

class ScaffoldOptions {
  protected $overlays;
  protected $body;

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
  protected $overlays;
  protected $body;

  public static function options () {
    return new ScaffoldOptions();
  }

  public function __construct(ScaffoldOptions $options) {
    $optionsArr = (function(){
      return [
        'overlays' => $this->overlays,
        'body' => $this->body,
      ];
    })->call($options);
    $this->overlays = $optionsArr['overlays'];
    $this->body = $optionsArr['body'];
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