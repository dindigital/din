<?

namespace lib\Form\Upload\Uploadify;

use lib\Form\Upload\FileTypes;

/**
* Classe de implementação do plugin javascript Uplodify.
* Documentação: http://www.uploadify.com/
*
* @package Form.Upload
*/
class Uploadify
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

/**
* aceita multiplos arquivos? true|false
* @var bool
*/
private $_multiple;

/**
* opções chave/valor na hora de implementar o pupload
* @var array
*/
private $_opt = array();

/**
* tipo de arquivo. Exemplo: 'imagem'
* @var string
*/
private $_type;

/**
* opções para a opção formData {}
* @var array
*/
private $_form_data = array();

/**
*
* @param string $field_name
*/
public function __construct ( $field_name )
{
$this->setName($field_name);
}

/**
*
* @param string $field_name
*/
public function setName ( $field_name )
{
$this->_field_name = $field_name;
}

/**
*
* @param string $class
*/
public function setClass ( $class )
{
$this->_class = $class;
}

/**
*
* @param bool $bool
*/
public function setMultiple ( $bool )
{
$this->_multiple = $bool;
}

/**
*
* @param string $type
*/
public function setType ( $type )
{
$type = FileTypes::typeByName($type);
$extensions = '*.' . implode('; *.', $type->ext) . ';';

$r = "   fileTypeDesc : '{$type->desc}'," . PHP_EOL;
$r .= "   fileTypeExts : '{$extensions}',";

$this->_type = $r;
}

/**
* seta opções de implementação.
* exemplo: setOpt('auto', "false")
* @param string $key
* @param string $value
*/
public function setOpt ( $key, $value )
{
$this->_opt[$key] = $value;
}

/**

* @param string $key
* @param string $value
*/
public function setFormData ( $key, $value )
{
$this->_form_data[$key] = $value;
}

/**
* retorna o html/javscript do botão de upload.
* @return string
*/
public function getButton ()
{

$r = '<input id="' . $this->_field_name . '" type="file" ';
$r .= 'class="' . $this->_class . '" />' . PHP_EOL;

$r .= '<script>' . PHP_EOL;
$r .= '$(function() {' . PHP_EOL;
$r .= ' $("#' . $this->_field_name . '").uploadify({' . PHP_EOL;

if ( $this->_multiple ){
$r .= "   multi : true," . PHP_EOL;
} else {
$r .= "   multi : false," . PHP_EOL;
//$r .= "   queueSizeLimit : 1," . PHP_EOL;
$r .= "   onSelect : function(file) {" . PHP_EOL;
$r .= "     if ($(\"#{$this->_field_name}\").data('uploadify').queueData.queueLength > 0){" . PHP_EOL;
$r .= "       $.each($(\"#{$this->_field_name}\").data('uploadify').queueData.files, function(i,o){" . PHP_EOL;
$r .= "         if (i != file.id)" . PHP_EOL;
$r .= "           $(\"#{$this->_field_name}\").uploadify('cancel', i);" . PHP_EOL;
$r .= "       });" . PHP_EOL;

$r .= "     }" . PHP_EOL;
$r .= "   }," . PHP_EOL;

}

foreach ( $this->_opt as $k => $v ) {
$r .= "   {$k} : {$v}," . PHP_EOL;
}

$r .= $this->_type . PHP_EOL;
$r .= "   formData : {" . PHP_EOL;
$last_key = array_keys($this->_form_data);
$last_key = end($last_key);
foreach ( $this->_form_data as $k => $v ) {
if ( $last_key == $k )
$r .= "     {$k} : {$v}" . PHP_EOL;
else
$r .= "     {$k} : {$v}," . PHP_EOL;
}
$r .= "   }" . PHP_EOL;
$r .= ' });' . PHP_EOL;
$r .= '});' . PHP_EOL;
$r .= '</script>' . PHP_EOL;

return $r;
}

}