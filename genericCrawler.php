<?php
require 'simple_html_dom.php';

class GenericCrawler
 {

    /**
     * The function will return information crawled from the url provided
     * This logic works best when the selector point to the link of an item
     * I usually use this to download large numbers of items from a page like books, images, songs etc...
     * 
     * @param [string] $link
     * @param [string] $selector
     * @param boolean $download
     * @return void
     */
public function getItems($link, $selector, $download = false)
    {

        $html = new simple_html_dom();

        $context = stream_context_create(array(
            'http' => array(
                'header' => array('User-Agent: Mozilla/5.0 (Windows; U; Windows NT 6.1; rv:2.2) Gecko/20110201'),
            ),
        ));

        $itemList = [];

        $html->load_file($link, false, $context);

        $links = $html->find($selector);

        foreach ($links as $currentLink) {
             $currentLink = $currentLink->href;
             array_push($itemList, $currentLink);

            }

        echo 'Found ' . count($itemList) . ' links' . PHP_EOL;

            if($download == true) {
                $date = new DateTime();
                echo  ' Starting Download' . PHP_EOL;
                foreach($itemList as $item) {
                    exec('wget ' . $item . ' -P items' . $date->getTimestamp());
                }
            }
            else {
                echo 'Download argument not set, not downloading items.' . PHP_EOL;
                echo 'If you wish to automatically download the items, set the 3rd argument to True' . PHP_EOL;
            }
    }
}