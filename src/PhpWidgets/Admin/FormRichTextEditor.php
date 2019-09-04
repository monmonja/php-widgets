<?php
namespace PhpWidgets\Admin;

use PhpWidgets\Forms\Input;
use PhpWidgets\Forms\InputOptions;
use PhpWidgets\Options;
use PhpWidgets\Widget;

trait FormRichTextEditorVariables {
  protected $editorId;
  protected $label;
  protected $value;
  protected $name = 'content';
  protected $uploadImagePath;
}

class FormRichTextEditorOptions extends Options {
  use FormRichTextEditorVariables;

  public function setEditorId(string $editorId): FormRichTextEditorOptions  {
    $this->editorId = $editorId;
    return $this;
  }

  public function setLabel(string $label): FormRichTextEditorOptions  {
    $this->label = $label;
    return $this;
  }

  public function setValue(string $value): FormRichTextEditorOptions  {
    $this->value = $value;
    return $this;
  }

  public function setName(string $name): FormRichTextEditorOptions  {
    $this->name = $name;
    return $this;
  }

  public function setUploadImagePath(string $uploadImagePath): FormRichTextEditorOptions  {
    $this->uploadImagePath = $uploadImagePath;
    return $this;
  }
}

class FormRichTextEditor extends Widget {
  use FormRichTextEditorVariables;

  public static function options () {
    return new FormRichTextEditorOptions();
  }

  public function __construct(FormRichTextEditorOptions $options)
  {
    $this->mapOptionsToVariable($options);
    if ($this->label === null) { die("FormRichTextEditor: label is required"); }
    if ($this->value === null) { die("FormRichTextEditor: value is required"); }
    if ($this->editorId === null) { die("FormRichTextEditor: editorId is required"); }
  }

  public function render() {
    $rteHtml = $this->getRteContent($this->editorId, $this->value);
    return <<<HTML
    <div class="form-row ">
          <label>{$this->label}</label>
          <div class="form-component">
              <div id="{$this->editorId}"  class="rte-field" ></div>
              <input type="hidden" id="{$this->editorId}-hidden" class="rte-field-hidden-element" name="{$this->name}" />
          </div>
      </div>
     $rteHtml 
HTML;
  }

  public function getRteContent ($elementId, $value) {
    $uploadPath = $this->uploadImagePath;
    $basePath = env('ADMIN_URL_PREFIX') ?: 'admin';
    return <<< HTML
<link href="https://cdnjs.cloudflare.com/ajax/libs/quill/1.3.6/quill.snow.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<style>
    .ql-container {
        outline: none;
    }
    .material-icons {
        font-size: 110%;
    }
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/quill/1.3.6/quill.min.js"></script>
<script>
    function initRTE(containerEl, editorContent) {
        console.log(containerEl);
        if ($(containerEl).length === 0) {
            console.log("element not found");
            return;
        }
        var Link = Quill.import('formats/link');
        let BlockEmbed = Quill.import('blots/block/embed');

        class MyLink extends Link {
            static create(value) {
                let node = Link.create(value);
                value = Link.sanitize(value);
                node.setAttribute('href', value);
                if (value.startsWith("/")) {
                    node.removeAttribute('target');
                } else {
                    node.setAttribute("target", "_blank");
                }
                return node;
            }

            format(name, value) {
                super.format(name, value);

                if (name !== this.statics.blotName || !value) {
                    return;
                }

                if (value.startsWith("/")) {
                    this.domNode.removeAttribute("target");
                } else {
                    this.domNode.setAttribute("target", "_blank");
                }
            }
        }

        Quill.register(MyLink);


        class ItemModule extends BlockEmbed {

            static create(object) {
                let node = super.create();
                let shadow = node.attachShadow({mode: 'closed'});

                let setDataSet = function (object) {
                    for (var i in object) {
                        if (i === 'data-source') {
                            for (var model in object['data-source']) {
                                node.dataset.mapTo = model;
                                var source = object['data-source'][model].split('.');
                                node.dataset.model = source[0] ;
                                node.dataset.method = source[1] ;
                            }
                        } else if (i === 'data-source-data') {
                            node.dataset.limit = object['data-source-data'].limit;
                            node.dataset.data = object['data-source-data'].data;
                        } else {
                            node.dataset[i] = object[i];
                        }
                    }
                    var element = jQuery(`<div style='border:1px solid #CCC;padding: 12px'>\${object.type}<br />\${JSON.stringify(object)}</div>`);
                    shadow.innerHTML = "";
                    shadow.appendChild(element[0]);
                };
                setDataSet(object);


                node.addEventListener('click', function(ev) {
                    ev.preventDefault();
                    openModuleSelector("", node.dataset, function (object) {
                        console.log('update', object);
                        setDataSet(object);
                    });

                }, false);

                return node;
            }


            static value(domNode) {
                return $(domNode).data();
            }
        }
        ItemModule.blotName = 'item-module';
        ItemModule.tagName = 'item-module';
        Quill.register(ItemModule);

        var toolbarOptions = [
            // [{ 'placeholder': ['[GuestName]', '[HotelName]'] }],
            [{ 'header': [1, 2, 3, 4, false] }],
            // ['blockquote', 'code-block'],
            ['bold', 'italic', 'underline'],       
            [ 'link', 'image2' ],       
            [{ 'list': 'ordered'}, { 'list': 'bullet' }],

            ['showHtml'],
        ];
        var icons = Quill.import('ui/icons');
        icons['image2'] = '<i class="material-icons">add_photo_alternate</i>';
        icons['bold'] = '<i class="material-icons">format_bold</i>';
        icons['italic'] = '<i class="material-icons">format_italic</i>';
        icons['underline'] = '<i class="material-icons">format_underlined</i>';
        icons['strike'] = '<i class="material-icons">strikethrough_s</i>';
        icons['link'] = '<i class="material-icons">insert_link</i>';
        icons['color'] = '<i class="material-icons">format_color_text</i>';
        icons['background'] = '<i class="material-icons">format_color_fill</i>';
        icons['list']['bullet'] = '<i class="material-icons">format_list_bulleted</i>';
        icons['list']['ordered'] = '<i class="material-icons">format_list_numbered</i>';
        icons['align'][''] = '<i class="material-icons">format_align_left</i>';
        icons['align']['right'] = '<i class="material-icons">format_align_right</i>';
        icons['align']['center'] = '<i class="material-icons">format_align_center</i>';
        icons['align']['justify'] = '<i class="material-icons">format_align_justify</i>';
        icons['showHtml'] = '<i class="material-icons">code</i>';
        icons['item-module'] = '<i class="material-icons">view_module</i>';

        var quill = new Quill(containerEl, {
            modules: {
                toolbar: {
                    container: toolbarOptions,
                    handlers: {
                        "image2": function (value) {
                            var currentQuill = this.quill;
                            const range = currentQuill.getSelection();
                            openFileManager("$basePath", "$uploadPath", function(url) {
                                quill.insertEmbed(range.index, 'image', `\${url}`);
                            });

                        },
                        showHtml: function (value) {
                            if (txtArea.style.display === '') {
                                var html = txtArea.value;
                                quill.pasteHTML(html);
                            } else {
                                setTimeout(function(){
                                    $(txtArea).focus();
                                }, 200);
                            }
                            txtArea.style.display = txtArea.style.display === 'none' ? '' : 'none';
                        },
                        'item-module': function (value) {
                            const range = quill.getSelection();
                            openModuleSelector("{{env('ADMIN_URL_PREFIX') ?: 'admin'}}", null, function (object) {
                                quill.insertEmbed(range.index, 'item-module', object, Quill.sources.USER);
                                quill.setSelection(range.index + 2, Quill.sources.SILENT);
                            });
                        }
                    }

                }
            },
            bounds: document.body,
            theme: 'snow',
        });


        // const placeholderPickerItems = Array.prototype.slice.call(document.querySelectorAll('.ql-placeholder .ql-picker-item'));
        //
        // placeholderPickerItems.forEach(item => item.textContent = item.dataset.value);
        // document.querySelector('.ql-placeholder .ql-picker-label').innerHTML = 'Insert placeholder' + document.querySelector('.ql-placeholder .ql-picker-label').innerHTML;


        var txtArea = document.createElement('textarea');
        txtArea.style.cssText = "width: 100%;margin: 0px;background: rgb(29, 29, 29);box-sizing: border-box;color: rgb(204, 204, 204);font-size: 15px;outline: none;padding: 20px;line-height: 24px;font-family: Consolas, Menlo, Monaco, &quot;Courier New&quot;, monospace;position: absolute;top: 0;bottom: 0;border: none;display:none; height: 100%"

        var htmlEditor = quill.addContainer('ql-custom');
        htmlEditor.appendChild(txtArea);

        var hiddenValueEl = $(containerEl).siblings(".rte-field-hidden-element:first");
        quill.on('text-change', function (delta, oldDelta, source) {
            if (hiddenValueEl) {
                hiddenValueEl.val(quill.root.innerHTML);
            }
        });
        hiddenValueEl.on('trigger-quill-change', function () {
            quill.pasteHTML(hiddenValueEl.val());            
        });

        if (editorContent) {
            quill.pasteHTML(editorContent);
            txtArea.value = editorContent;
        }
    }

    window.addEventListener('DOMContentLoaded', (event) => {
      initRTE("#$elementId", "$value");
      $("#$elementId").parents('form').on('submit',  function(e) {
          if ($("#$elementId").siblings(".rte-field-hidden-element:first").length > 0) {
              $("#$elementId").siblings(".rte-field-hidden-element:first").val(document.querySelector("#$elementId").children[0].innerHTML);
          } else {
              $("#$elementId-hidden").val(document.querySelector("#$elementId").children[0].innerHTML);
          }
  
      });
    });


    
</script>
HTML;

  }

}