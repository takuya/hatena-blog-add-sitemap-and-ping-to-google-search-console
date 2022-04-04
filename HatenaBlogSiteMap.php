<?php

class HatenaBlogSiteMap {
  public $blog_url;
  
  public function __construct($blog_url) {
    $blog_url = preg_replace("|/?$|", '', $blog_url);
    $blog_url = preg_replace("|^http://|", 'http://', $blog_url);
    $this->blog_url=$blog_url."/";
  }
  public function siteMapUrl(){
    return "{$this->blog_url}sitemap.xml";
  }
  public function siteMapPeriodical():array{
    $url = $this->siteMapUrl();
    $xml = file_get_contents($url);
    $xml = preg_replace('/(xmlns|xsi)[^=]*="[^"]*" ?/i', '', $xml);
    $dom = new DOMDocument();
    $dom->loadXML($xml);
    $xpath = new DOMXPath($dom);
    //
    $arr = [];
    $ret =$xpath->query('//loc/text()');
    foreach( $ret as $e){
      $url = (string)$e->nodeValue;
      $arr[]=$url;
    }
    return $arr;
  }
  public function checkSiteMapPeriodical():bool {
    $list = $this->siteMapPeriodical();
    foreach ( $list as $e ){
      echo "check {$e} is valid ...";
      $xml = file_get_contents($e);
      if ($this->validateXml($xml)!==true){
        throw new RuntimeException("Xml syntax error'$e'");
      }
      echo "OK".PHP_EOL;
      
    }
    return true;
  }
  public function validateXml($XmlContentString):bool {
    libxml_use_internal_errors(true);
    $doc = new DOMDocument();
    $doc->loadXML($XmlContentString);
    $errors = libxml_get_errors();
    libxml_clear_errors();
    return empty($errors);
  }
  public function sendWebPing(){
    $list = $this->siteMapPeriodical();
    foreach ( $list as $e ){
      echo "send notify $e ...";
      $ping_url = "http://www.google.com/ping?sitemap=".rawurlencode($e);
      $html = file_get_contents($ping_url);
      $dom = new DOMDocument();
      @$dom->loadHTML($html);
      $xml = simplexml_import_dom($dom);
      $title = (string)$xml->xpath("//title/text()")[0];
      if ( !preg_match('|Sitemap Notification Received$|', $title)){
        throw new RuntimeException("Notification Send Failed.");
      }
      echo "done.".PHP_EOL;
    }
  }
}
