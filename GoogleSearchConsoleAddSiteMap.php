<?php

class GoogleSearchConsoleAddSiteMap {
  
  protected string $client_id;
  protected string $client_secret;
  protected string $refresh_token;
  protected string $token_uri='https://www.googleapis.com/oauth2/v4/token';
  protected int $token_expires;
  protected string $token = "";
  public function __construct($refresh_token,$client_id,$client_secret) {
    $this->client_id = $client_id;
    $this->client_secret =$client_secret;
    $this->refresh_token = $refresh_token;
    $this->token_expires = time()-9999;
  }
  public function token_expired(){
    return $this->token_expires<time();
  }
  public function accessToken():string{
    if ($this->token_expired()){
      $params = [
        "client_id"=>$this->client_id,
        "client_secret"=>$this->client_secret,
        "refresh_token"=>$this->refresh_token,
        "grant_type"=>"refresh_token",
      ];
      $curl = curl_init($this->token_uri);
      curl_setopt($curl,CURLOPT_POST, TRUE);
      curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
      curl_setopt($curl,CURLOPT_RETURNTRANSFER, TRUE);
      $ret= curl_exec($curl);
      $res_json = json_decode($ret,true);
      if ( !empty($res_json['error']) ) {
        throw new RuntimeException($ret);
      }
      $this->token_expires=strtotime("+{$res_json['expires_in']}sec");
      $this->token = $res_json['access_token'];
    }
    return $this->token;
  }
  public function listSiteMap($site_url){
    $url = "https://www.googleapis.com/webmasters/v3/sites/".rawurlencode($site_url)."/sitemaps";
    $curl = curl_init($url);
    curl_setopt($curl,CURLOPT_HTTPHEADER,[
      "Authorization: Bearer {$this->accessToken()}"
    ]);
    curl_setopt($curl,CURLOPT_RETURNTRANSFER, TRUE);
    $ret= curl_exec($curl);
    $res_json = json_decode($ret,true);
    if ( !empty($res_json['error']) ) {
      throw new RuntimeException($ret);
    }
    return $res_json;
  }
  public function addSiteMap($site_url, $sitemap_url) {
    $url = "https://www.googleapis.com/webmasters/v3/sites/".rawurlencode($site_url)."/sitemaps";
    $url = $url.'/'.rawurlencode($sitemap_url);
    $curl = curl_init($url);
    curl_setopt($curl,CURLOPT_PUT, TRUE);
    curl_setopt($curl,CURLOPT_HTTPHEADER,[
      "Authorization: Bearer {$this->accessToken()}"
    ]);
    curl_setopt($curl,CURLOPT_RETURNTRANSFER, TRUE);
    // curl_setopt($curl,CURLOPT_VERBOSE, TRUE);
    $ret= curl_exec($curl);
    $res_json = json_decode($ret,true);
    if ( !empty($res_json['error']) ) {
      throw new RuntimeException($ret);
    }
    return $res_json;
  }
  
  
}


