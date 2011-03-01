<?php
/**
 * Wozozo_Sakusui
 *
 * @package Wozozo
 * @author Keisuke SATO <ksato@otobank.co.jp>
 */

require_once 'HTTP/Request2.php';

class Wozozo_Sakusui
{
    const LUNCHMENU_URL = 'http://www.teraken.co.jp/menu/lunchmenu/index.html';

    protected $lunchMenus = array();

    /**
     *
     */
    public function __construct($options = array())
    {
        $this->lunchMenus = $this->fetchLunchMenus();
    }

    /**
     * 指定した日のランチメニューを取得する
     *
     * @param DateTime $datetime
     * @return
     */
    public function getLunchMenu(DateTime $datetime)
    {
        $date = $datetime->format('Y-m-d');
        if (isset($this->lunchMenus[$date])) {
            return $this->lunchMenus[$date];
        }
        return null;
    }

    /**
     * さくら水産のランチ情報を取得する
     *
     * @return array | false
     */
    public function fetchLunchMenus()
    {
        $menus = array();
        $today = new DateTime;

        $request = new HTTP_Request2;
        $request->setMethod(HTTP_Request2::METHOD_GET);
        $request->setUrl(self::LUNCHMENU_URL);
        try {
            $response = $request->send();
            if (200 == $response->getStatus()) {
                $dom = new DOMDocument;
                @$dom->loadHTML($response->getBody());
                $xml = simplexml_import_dom($dom);
                foreach ($xml->xpath('//table/tr') as $tr) {
                    if (preg_match('/(\d+)月(\d+)日/', $tr->td[0]->div, $matches)) {
                        $dateinfo = new DateTime(sprintf('%04d-%02d-%02d', $today->format('Y'), $matches[1], $matches[2]));

                        $_menus = array();
                        foreach ($tr->td[1]->div->strong as $strong) {
                            $_menus[] = (string) $strong;
                        }
                        $menus[$dateinfo->format('Y-m-d')] = $_menus;
                    }
                }
            }
        } catch (HTTP_Request2_Exception $e) {
            // HTTP Error
            return false;
        }

        return $menus;
    }
}

