<?

require $_SERVER['DOCUMENT_ROOT'] . '/lib/Form/Textarea/Ckeditor/Ckeditor.php';
require $_SERVER['DOCUMENT_ROOT'] . '/lib/Session/iSession.php';
require $_SERVER['DOCUMENT_ROOT'] . '/lib/Session/Session.php';


$ck1 = new \lib\Form\Textarea\Ckeditor\Ckeditor('campo1');
$campo1 = $ck1->getElement();

$ck2 = new \lib\Form\Textarea\Ckeditor\Ckeditor('campo2');
$campo2 = $ck2->getElement();



?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>CKFinder - Sample - CKEditor Integration</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <script type="text/javascript" src="/backend/scripts/jquery-1.8.2.min.js"></script>
    <script type="text/javascript" src="/backend/plugins/ckeditor365/ckeditor.js"></script>
    <script type="text/javascript" src="/backend/plugins/ckfinder23/ckfinder.js"></script>
  </head>
  <body>
    <?=$campo1?>
    <?=$campo2?>
  </body>
</html>
