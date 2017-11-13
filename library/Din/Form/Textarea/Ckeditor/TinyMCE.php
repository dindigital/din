<?php

namespace Din\Form\Textarea\Ckeditor;

use Din\Session\Session;

class TinyMCE
{
    /**
     * nome do campo html
     * @var string
     */
    private $_field_name;

    /**
     * nome da classe do elemento
     * @var type
     */
    private $_class;
    private $_finderPath;

    /**
     *
     * @param string $field_name
     */
    public function __construct($field_name)
    {
        $this->setName($field_name);
        $this->_finderPath = '/admin/js/ckfinder23/';
    }

    /**
     *
     * @param string $field_name
     */
    public function setName($field_name)
    {
        $this->_field_name = $field_name;
    }

    /**
     *
     * @param string $class
     */
    public function setClass($class)
    {
        $this->_class = $class;
    }

    public function setFinderPath($path)
    {
        $this->_finderPath = $path;
    }

    /**
     * retorna o html/javscript do elemento textarea trocado pelo ckeditor
     * @param string $value
     * @return string
     */
    public function getElement($value = '')
    {
        $session = new Session('IsAuthorized');
        $session->set(0, 1);

        $r = '<textarea id="'.$this->_field_name.'" name="'.$this->_field_name.'" class="'.$this->_class.'">'.$value.'</textarea>'.PHP_EOL;
        $r .= '<script type="text/javascript">'.PHP_EOL;
        $r .= ' tinymce.init({'.PHP_EOL;
        $r .= '     selector: "#'.$this->_field_name.'",'.PHP_EOL;
        $r .= '     theme: "modern",'.PHP_EOL;
        $r .= '     width: "100%",'.PHP_EOL;
        $r .= '     height: 400,'.PHP_EOL;
        $r .= '     language: "pt_BR",'.PHP_EOL;
        $r .= '     plugins : ['.PHP_EOL;
        $r .= '         "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",'.PHP_EOL;
        $r .= '         "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",'.PHP_EOL;
        $r .= '         "save table contextmenu directionality emoticons template paste textcolor moxiemanager",'.PHP_EOL;
        $r .= '     ],'.PHP_EOL;
        $r .= '     toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons",'.PHP_EOL;
        $r .= ' });'.PHP_EOL;
        $r .= '</script>'.PHP_EOL;

        return $r;
    }
}