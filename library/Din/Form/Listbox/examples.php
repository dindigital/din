<?


use lib\Form\Listbox\Listbox;

//_# UTILIZANDO COM ARRAY SIMPLES ______________________________________________
$listbox = new Listbox();
$listbox->setName('mylistbox');
$listbox->setStyle('width:400px;height:300px');
$listbox->setFirstOpt('Nenhum');
$listbox->setSelected(array(
    '2','1'
));
$listbox->setOptionsArray(array(
    'Foo','Bar','Baz','Qux'
));

echo $listbox->getElement();

//_# UTILIZANDO COM ARRAY DE OBJETOS ___________________________________________
$listbox = new Listbox('interesse');
$listbox->setId('elementid');

$obj1 = new stdClass();
$obj1->id = '45';
$obj1->name = 'Foo';

$obj2 = new stdClass();
$obj2->id = '46';
$obj2->name = 'Bar';

$obj3 = new stdClass();
$obj3->id = '64';
$obj3->name = 'Baz';

$arrayObj = array(
    $obj1, $obj2, $obj3
);

$listbox->setSelected(array(
    '46'
));

$listbox->setOptionsObj($arrayObj, 'id', 'name');

echo $listbox->getElement();