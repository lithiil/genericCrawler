    <?php
    require 'simple_html_dom.php';

    class GenericCrawler
    {
        /**
         * This function will tell you if the url is up and valid
         *
         * @param [type] $link
         * @return boolean
         */
        function isDomainAvailible($link)
        {
                //check, if a valid url is provided
                if(!filter_var($link, FILTER_VALIDATE_URL))
                {
                        return false;
                }
 
                //initialize curl
                $curlInit = curl_init($link);
                curl_setopt($curlInit,CURLOPT_CONNECTTIMEOUT,10);
                curl_setopt($curlInit,CURLOPT_HEADER,true);
                curl_setopt($curlInit,CURLOPT_NOBODY,true);
                curl_setopt($curlInit,CURLOPT_RETURNTRANSFER,true);
 
                //get answer
                $response = curl_exec($curlInit);
 
                curl_close($curlInit);
 
                if ($response) return true;
 
                return false;
        }

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
            $linkStatus = $this->isDomainAvailible($link);
            if($linkStatus === false)die($link . (' is either not a valid link or is not responding with 200' . PHP_EOL));
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

                if (count($itemList) <= 0){
                    exit('No items found with that selector!'.PHP_EOL);
                }

            echo 'Found ' . count($itemList) . ' links' . PHP_EOL;

                if($download == true) {
                    $date = new DateTime();
                    echo  ' Starting Download' . PHP_EOL;
                    foreach($itemList as $item) {
                        exec('wget ' . escapeshellarg($item) . ' -P items' . $date->getTimestamp());
                    }
                }

                else {
                    echo 'Download argument not set, not downloading items.' . PHP_EOL;
                    echo 'If you wish to automatically download the items, set the 3rd argument to True' . PHP_EOL;
                }
        }
    }