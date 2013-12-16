<?

namespace lib\Form\Upload\Uploadify;

use lib\Form\Upload\iUploadBuilder;

class UploadifyPainel implements iUploadBuilder
{

  /**
   *
   * @param string $field_name
   * @param string $type
   * @param bool $obg
   * @param bool $multiple
   * @return string
   */
  public static function getButton ( $field_name, $type, $obg = false, $multiple = false, $uploader = null )
  {
    if ( is_null($uploader) ) {
      $uploader = '/backend/plugins/uploadify/uploadify.php';
    }
    $Upl = new Uploadify($field_name);
    $Upl->setClass('uploadify');
    $Upl->setMultiple($multiple);
    $Upl->setType($type);
    if ( $obg )
      $Upl->setOpt('required', 'true');
    $Upl->setOpt('swf', "'/backend/plugins/uploadify/uploadify.swf'");
    $Upl->setOpt('uploader', "'{$uploader}'");
    $Upl->setOpt('auto', "false");
    $Upl->setFormData('timestamp', "'" . time() . "'");
    $Upl->setFormData('token', "'" . md5('unique_salt' . time()) . "'");
    $Upl->setFormData('file_id', "'" . $field_name . "'");

    $r = $Upl->getButton();

    return $r;
  }

}

