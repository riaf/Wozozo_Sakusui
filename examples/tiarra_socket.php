<?php
require_once __DIR__ . '/../src/Sakusui.php';
require_once 'Net/Socket/Tiarra.php';

$socketname = isset($argv[1]) ? $argv[1] : 'tiarra-socket';
$channel = isset($argv[2]) ? $argv[2] : '#example@example';

try {
    $sakusui = new Wozozo_Sakusui;
    $lunchMenus = $sakusui->getLunchMenu(new DateTime('today'));

    $tiarra = new Net_Socket_Tiarra($socketname);
    $tiarra->noticeMessage($channel, '今日のさくら水産ランチメニュー');
    foreach ($lunchMenus as $menu) {
        $tiarra->noticeMessage($channel, '  ' . $menu);
    }
} catch (Exception $e) {
    echo $e->getMessage(), PHP_EOL;
    exit 1;
}

