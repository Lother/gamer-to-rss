#gamer-to-rss

###關於此程式
巴哈姆特小屋轉換 RSS 格式提供閱讀器讀取用。

###RSS
目前提供巴哈小屋的創作與留言的RSS 轉換，
必須自行將此程式提供 RSS 閱讀器使用，
由於只會讀取第一頁，所以過往發表過的創作不會被存取到。

###Example
```php
foreach (glob("GamerHome/GamerHome*.php") as $filename)
    include $filename;
//直接把帳號從 GET 傳入
$owner = isset($_GET['owner']) ? $_GET['owner'] : '';
//轉換小屋創作(預設)、訪客留言(R)、訂閱聯播(S)、好友創作(F)
$mode = isset($_GET['mode']) ? $_GET['mode'] : '';
//啟動轉換
$GamerHome;
switch ($mode)
{
	case 'R': case 'r': 
		$GamerHome = new GamerHomeReplyList($owner);
		break;
	case 'S': case 's': 
		$GamerHome = new GamerHomeSubscribeCreation($owner);
		break;
	case 'F': case 'f': 
		$GamerHome = new GamerHomeSubscribeFriend($owner);
		break;
	default:
		$GamerHome = new GamerHomeCreation($owner);
}
echo $GamerHome->asXml();

```

###其他說明
創作轉換來源改為從清單
並新增留言轉換


Google Reader已經成為歷史...

