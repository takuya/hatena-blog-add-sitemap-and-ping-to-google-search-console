<?php

require_once "HatenaBlogSiteMap.php";

function main(){
  
  $blog_home = 'https://takuya-1st.hatenablog.jp/';
  $sm = new HatenaBlogSiteMap($blog_home);
  $sm->checkSiteMapPeriodical();
  $sm->sendWebPing();
}



main();