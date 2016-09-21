<?php

require 'ObjectArray.php';

class User
{
    public $name;
    public $age;

    public function getTitle()
    {
        return (sprintf('%s [%d]', $this->name, $this->age));
    }
}

$array = new ObjectArray();

$a = new User();
$a->name = 'John';
$a->age = 25;
$array[$a] = '10 friends';

$b = new User();
$b->name = 'Nancy';
$b->age = 20;
$array[$b] = '43 friends';

$c = new User();
$c->name = 'Tom';
$c->age = 75;
$array[$c] = '3 friends';

foreach ($array as $user => $friends)
{
    print $user->getTitle();
    print ' has ';
    print $friends;
    print '!';
    print PHP_EOL;
}
