<?php
foreach (glob("GamerHome/GamerHome*.php") as $filename)
    include $filename;
//直接把帳號從 GET 傳入
$owner = isset($_GET['owner']) ? $_GET['owner'] : '';
//轉換小屋創作(預設)、訪客留言(R)、訂閱聯播(S)、好友創作(F)
$mode = isset($_GET['mode']) ? $_GET['mode'] : '';

$filter = isset($_GET['filter']) ? $_GET['filter'] : '';

//啟動轉換
$GamerHome;
switch ($mode)
{
	case 'R': case 'r': 
		$GamerHome = new GamerHomeReplyList($owner,$filter);
		break;
	case 'S': case 's': 
		$GamerHome = new GamerHomeSubscribeCreation($owner,$filter);
		break;
	case 'F': case 'f': 
		$GamerHome = new GamerHomeSubscribeFriend($owner,$filter);
		break;
	default:
		$GamerHome = new GamerHomeCreation($owner,$filter);
}
echo $GamerHome->asXml();

?>
