<?php
namespace PhpWidgets\Admin;

use PhpWidgets\Html\Traits\DomOptions;
use PhpWidgets\Html\Traits\DomVariables;
use PhpWidgets\Widget;

class IndexTableFields {
  public $name;
  public $label;
  public $replaceAr;
  public $renderer;

  public static function make($name, $label, $replaceAr = null, $renderer = null) : IndexTableFields{
    $field = new IndexTableFields();
    $field->name = $name;
    $field->label = $label;
    $field->replaceAr = $replaceAr;
    $field->renderer = $renderer;
    return $field;
  }
}
trait IndexTableVariables {
  use DomVariables;
  /** @var DomOptions */
  protected $rowOptions;
  /** @var IndexTableFields[] */
  protected $fields = [];
  protected $data = [];
  protected $viewRoutes;
  protected $editRoutes;
  protected $deleteRoutes;
}

class IndexTableOptions extends DomOptions {
  use IndexTableVariables;

  public function setFields (array $fields) {
    $this->fields = $fields;
    return $this;
  }

  public function setData (array $data) {
    $this->data = $data;
    return $this;
  }

  public function setIndexTable (DomOptions $rowOptions) {
    $this->rowOptions = $rowOptions;
    return $this;
  }
  public function setViewRoute (string $viewRoutes) {
    $this->viewRoutes = $viewRoutes;
    return $this;
  }
  public function setEditRoute (string $editRoutes) {
    $this->editRoutes = $editRoutes;
    return $this;
  }
  public function setDeleteRoute (string $deleteRoutes) {
    $this->deleteRoutes = $deleteRoutes;
    return $this;
  }
}

class IndexTable extends Widget {
  use IndexTableVariables;

  public static function options () {
    return new IndexTableOptions();
  }

  public function __construct(IndexTableOptions $options) {
    $this->mapOptionsToVariable($options);
    if ($this->rowOptions == null) {
      $this->rowOptions = new DomOptions();
      $this->rowOptions->setClassName("row-item");
    }
  }

  public function render() {
    $html = "<div class='admin-table-wrapper'>";
    $html .= "<table {$this->getAttribute($this->options)}>";
    $html .= "<tr class='admin-head'>";
    foreach ($this->fields as $field) {
      $html .= "<td class='{$field->name}'>" . $field->label . "</td>";
    }
    $html .= "<td class='action'>Actions</td>";
    $html .= "</tr>";

    foreach ($this->data as $key => $row) {
      $html .= "<tr>";
      foreach ($this->fields as $field) {
        if ($field->renderer != null) {
          $html .= "<td class='{$field->name}'>" . call_user_func_array($field->renderer, [$row]) . "</td>";
        } else {
          $originalValue = $row[$field->name];

          if ($field->replaceAr != null) {
            $value = isset($field->replaceAr[$originalValue]) ?
              $field->replaceAr[$originalValue] : $originalValue;
            $html .= "<td class='{$field->name}'>" . $value . "</td>";
          } else {
            $html .= "<td class='{$field->name}'>" . $originalValue . "</td>";
          }
        }
      }
      $html .= "<td class='actions'>";
      $htmlRoutes = [];
      if (!empty($this->viewRoutes)) {
        array_push($htmlRoutes, "<a href=\"" . route($this->viewRoutes, ['uuid' => $row->id ?? $row['id']]) . "\">View</a>");
      }

      if (!empty($this->editRoutes)) {
        array_push($htmlRoutes,"<a  href=\"" . route($this->editRoutes, ['uuid' => $row->id ?? $row['id']]) . "\">Edit</a>");
      }
      if (!empty($this->deleteRoutes)) {
        array_push($htmlRoutes, "<a class=' delete-btn' href=\"" . route($this->deleteRoutes, ['uuid' => $row->id ?? $row['id']]) . "\">Delete</a>");
      }
      $html .= implode(" &bull; ", $htmlRoutes);
      $html .= "</td>";

      $html .= "</tr>";
    }


    return $html . '</table></div>';
  }

}