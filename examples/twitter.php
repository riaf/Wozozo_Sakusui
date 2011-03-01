<?php
require_once __DIR__ . '/../src/Sakusui.php';
require_once 'Services/Twitter.php';
require_once 'HTTP/OAuth/Consumer.php';

try {
    $twitter = new Services_Twitter();
    $oauth = new HTTP_OAuth_Consumer(
       'consumer_key',
       'consumer_secret',
       'access_token',
       'access_token_secret'
    );
    $twitter->setOAuth($oauth);

    $sakusui = new Wozozo_Sakusui;
    $lunchMenus = $sakusui->getLunchMenu(new DateTime('today'));

    $twitter->statuses->update("本日のさく水ランチ：" . implode(' / ', $lunchMenus));
} catch (Services_Twitter_Exception $e) {
    echo $e->getMessage(), PHP_EOL;
}
