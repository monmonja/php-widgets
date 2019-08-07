<?php
namespace PhpWidgets\Forms;

use PhpWidgets\Html\Traits\DomOptions;
use PhpWidgets\Html\Traits\DomVariables;
use PhpWidgets\Options;
use PhpWidgets\Widget;

trait InlineAutoCompleteVariables {
  use DomVariables;

  protected $buttonLabel;
  /** @var InputOptions */
  protected $inputOptions;
  protected $componentWrapperClass;
  protected $formUrl;
}

class InlineAutoCompleteOptions extends DomOptions {
  use InlineAutoCompleteVariables;

  public function setButtonLabel (string $buttonLabel): InlineAutoCompleteOptions {
    $this->buttonLabel = $buttonLabel;
    return $this;
  }

  public function setComponentWrapperClass(string $componentWrapperClass): InlineAutoCompleteOptions  {
    $this->componentWrapperClass = $componentWrapperClass;
    return $this;
  }

  public function setFormUrl(string $formUrl): InlineAutoCompleteOptions  {
    $this->formUrl = $formUrl;
    return $this;
  }


  public function setInputOptions(InputOptions $inputOptions): InlineAutoCompleteOptions  {
    $this->inputOptions = $inputOptions;
    return $this;
  }



}

class InlineAutoComplete extends Widget {
  use InlineAutoCompleteVariables;

  public static function options () {
    return new InlineAutoCompleteOptions();
  }

  public function __construct(InlineAutoCompleteOptions $options)
  {
    $this->mapOptionsToVariable($options);
    if ($this->inputOptions == null) { die("InlineAutoComplete: inputOptions is required"); }
    if ($this->formUrl == null) { die("InlineAutoComplete: formUrl is required"); }
    $this->inputOptions->setAutoComplete('off');
  }

  public function render() {
    $input = new Input($this->inputOptions);
    return <<<HTML
<div class="{$this->className}">
  <div class="{$this->componentWrapperClass}">
        {$input->render()}
        <button>{$this->buttonLabel}</button>        
  </div>
  <div class="{$this->className}-result"></div>
</div>
HTML;
  }

  /**
   * @return string
   */
  public function getJavascript()
  {
    $inputOptionsAr = (function(){
      return get_object_vars($this);
    })->call($this->inputOptions);

    return /** @lang JavaScript */ <<<JS
    $(document).ready(function(){
        $("body").on("click tap", function() {
            if ($(event.target).is("#{$inputOptionsAr['id']}")){
                event.stopPropagation();
                return;
            }
            $("#{$inputOptionsAr['id']}").parents('.{$this->className}').find('.{$this->className}-result').hide();
        });
        $("#{$inputOptionsAr['id']}")
          .on('focus', function(e){
                e.preventDefault();
                $("#{$inputOptionsAr['id']}").parents('.{$this->className}').find('.{$this->className}-result').show();  
          })
          .on('keyup', function(){
              let obj = this;
              if ($(this).val().length > 0) {
                  $("#{$inputOptionsAr['id']}").parents('.{$this->className}').find('.{$this->className}-result').show();
                  let inputVal = $(this).val();
                $.ajax({
                    "url": "{$this->formUrl}?query=" + inputVal,
                    "success": function (result) {
                      let resultsObj = $(obj).parents('.{$this->className}').find('.{$this->className}-result');
                      let outerWidth = $(obj).outerWidth();
                      if (outerWidth > 0 && $("#{$inputOptionsAr['id']}").val().length > 0) {
                        resultsObj.width(outerWidth);
                        resultsObj.html(result);
                        resultsObj.show();
                      } else {
                        resultsObj.hide();  
                      }
                    }
                });
              } else {
                  console.log('hide');
                  $("#{$inputOptionsAr['id']}").parents('.{$this->className}').find('.{$this->className}-result').hide();
              }
          }); 
    });
JS;
  }

}