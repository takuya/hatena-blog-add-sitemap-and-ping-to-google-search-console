<?php

require_once "HatenaBlogSiteMap.php";
require_once "GoogleSearchConsoleAddSiteMap.php";


function loadEnv(){
  
  $env = getenv();
  $pass_prahse=$env["MY_ENCRYPT_KEY"];
  $aes_iv="9R4JPSknDYhzoTkw";
  
  $client_secret_encrypted = "PvOjZck0o/egAItyPzUSgG87al+w67LLty53t4a3eTPfS3TuG/Qh6s+oJ08hzeaq";
  $refresh_token_encrypted = "X+Op2eoeBaPHkusUey9HPMCCJ+Zndb9OiA0VaAZt04iM0kwI7JHxXqTFPZhXfJQ899+jAyCAoN41sK3YMiP7hjukSawouPovP4sMCGj6/m5tgUMad6KIVGreMsh5jbySA1nUzYR8ih2fO204T3gd/Q==";
  $client_id_encrypted = "nWM5usSQqtXoLJMEBYEa1o3uZTCbeWSi4SFYFmDJn9GqK2a1C3xM8hXe4i9W9Up6QG9kbvwiB1WxQNHFCuBKvEFVTjjgY2oNKMP4NEVwRdM=";
  
  $client_secret=openssl_decrypt($client_secret_encrypted, 'AES-256-CBC',$pass_prahse,0,$aes_iv );
  $client_id=openssl_decrypt($client_id_encrypted, 'AES-256-CBC',$pass_prahse,0,$aes_iv );
  $refresh_token=openssl_decrypt($refresh_token_encrypted, 'AES-256-CBC',$pass_prahse,0,$aes_iv );

  $ret = compact('refresh_token','client_id','client_secret');
  return $ret;
}

function main(){
  
  $secret = loadEnv();
  
  $blog_home = 'https://takuya-1st.hatenablog.jp/';
  $sm = new HatenaBlogSiteMap($blog_home);
  $sm->checkSiteMapPeriodical();
  $sitemap_list = $sm->siteMapPeriodical();
  
  $cli = new GoogleSearchConsoleAddSiteMap(...$secret);
  foreach ($sitemap_list as $item) {
    echo "add Site $item ...";
    $cli->addSiteMap($blog_home,$item);
    echo "OK\n";
  }
}



main();