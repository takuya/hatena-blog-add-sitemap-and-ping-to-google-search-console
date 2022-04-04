# はてなブログのサイトマップをGoogleへ送信する。


Google Search Console に はてなブログの `/sitemap_periodical.xml` を送信すると、検索インデックスされると聞いたので

定期的に実行して、毎月のインデックスを送信する。

あわせて、web ping ( 更新通知 ) をGoogle へ送信する。

併せて、sitemap_periodicalをはてなブログへキャッシュさせる。

Google SearchConsoleが、sitemap_periodicalの読み込み失敗する。 

はてなブログにアクセスして、sitemap_periodicalがちゃんと生成されるかチェックしてキャッシュさせる