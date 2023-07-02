<?php

class Lingva{
    var $server = 'https://lingva.ml';
    var $port = '80';

    public function translate($sourcelang, $targetlang, $text){ 
        $cc = 0;       
        $arr = false;
        $text = str_replace('%', '&#37;', $text);
        $text = str_replace('/', '|||', $text);
        $vector = '/api/v1/'.$sourcelang.'/'.$targetlang.'/'.$text;
        $vector = str_replace(" ","%20", $vector);
        $url = $this->server.':'.$this->port.$vector;

        $ret = get_data($url, false);
        $arr = json_decode($ret, true);

        if(isset($arr['translation'])){
            $arr['translation'] = str_replace('|||', '/', $arr['translation']);
        }
        
        if(!isset($arr['error'])){
            return $arr['translation'];
        } else {
            return false;
        }
    }
}

function get_data($url, bool $useTor) {
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.67 Safari/537.36');
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120);
    curl_setopt($ch, CURLOPT_TIMEOUT, 180);
    curl_setopt($ch, CURLOPT_HTTPGET, 1);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_ENCODING, "UTF-8");
    curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
    curl_setopt($ch, CURLOPT_COOKIESESSION, TRUE);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    //curl_setopt($ch, CURLOPT_POST, count($fields));
    //curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
    
    if ($useTor) {
        curl_setopt($ch, CURLOPT_PROXY, 'http://localhost:9050');
        curl_setopt($ch, CURLOPT_PROXYTYPE, 7);
    }
    
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}
