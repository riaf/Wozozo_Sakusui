<?php
require_once __DIR__ . '/../src/Sakusui.php';

$sakusui = new Wozozo_Sakusui;

// get today's lunch menu
print_r($sakusui->getLunchMenu(new DateTime('today')));

