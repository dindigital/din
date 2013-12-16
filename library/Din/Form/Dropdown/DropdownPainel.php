<?

namespace lib\Form\Dropdown;

/**
 * @package Form.Dropdown 
 * 
 */
class DropdownPainel
{

  public static function fromArray ( $name, $array, $selected = '', $firstOption = '', $id = null )
  {
    $d = new Dropdown($name);
    $d->setClass('uniform');
    $d->setSelected($selected);
    $d->setOptionsArray($array);
    if ( $firstOption )
      $d->setFirstOpt($firstOption);

    if ( $id )
      $d->setId($id);

    return $d->getElement();
  }

}