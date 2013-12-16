<?


use lib\Form\Dropdown\Dropdown;

//_# UTILIZANDO COM ARRAY SIMPLES ______________________________________________
$dropdown = new Dropdown();
$dropdown->setName('mydropdown');
$dropdown->setId('myuniqueid');
$dropdown->setStyle('width:150px; height:50px');
$dropdown->setFirstOpt('Texto opcional','index_opcional');
$dropdown->setSelected('selecionado_opcional');
$dropdown->setOptionsArray(array(
    'Foo','Bar','Baz','Qux'
));

echo $dropdown->getElement();

//_# UTILIZANDO COM ARRAY DE OBJETOS ___________________________________________
$dropdown = new Dropdown('interesse');

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

$dropdown->setOptionsObj($arrayObj, 'id', 'name');

echo $dropdown->getElement();