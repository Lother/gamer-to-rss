<?php
/**
 * 巴哈小屋轉換 RSS
 */
class GamerHomeReplyList extends GamerHome{
	protected $_creation_url = 'http://home.gamer.com.tw/homeReplyList.php?owner=';
	protected $_title = ' 的訪客留言 RSS';
	protected $_find_page = '訪客留言';
	protected $_detail_url = 'http://home.gamer.com.tw/homeReplyList.php?owner=';

	protected function parseEntries()
	{
		if(preg_match_all('#<div id="msg_(\d+)"><p><\/p><a [^>]+>[^>]+><\/a><a href="home\.php\?owner=[^>]+>([^<]+)<\/a>：(?:|[^>]+>回應[^>]+>([^<]+)<\/a>[^>]+>[^>]+>)([^<]+)<[^>]+>(:?(\d{2}-\d{2} \d{2}:\d{2})|([^天<]+)天(\d{2}:\d{2})|(\d{1,2})([^前<]+)前|1分內)<#isu', $this->_html, $matches)){
			foreach($matches[1] as $i => $id){
				$tmp = array(
					'id' => $matches[1][$i],
					'title' => $matches[2][$i] . (($matches[3][$i]!="")?">" . $matches[3][$i] :"")."：",
					'url' => $this->_detail_url . $this->_owner . "&msg=" . $matches[1][$i] ,
					'author' => $matches[2][$i],
					'description'  => $matches[4][$i],
				);
				if($matches[6][$i]!="")
					$tmp['pubdate'] =  date("Y")."-" . $matches[5][$i] . ":00";
				else if ($matches[7][$i]!=""&&$matches[8][$i]!="")
					$tmp['pubdate'] = date("Y-m-d ".$matches[8][$i].":s",strtotime("-".(($matches[7][$i]=="昨")?1:2) ." day"));
				else if($matches[9][$i]!=""&&$matches[10][$i]!="")
					$tmp['pubdate'] = date("Y-m-d H:i:s",strtotime("-".$matches[9][$i] ." ". (($matches[10][$i]=="分")?" minute":"hour")));
				else
					$tmp['pubdate'] = date("Y-m-d H:i:s",time());
				array_push($this->_entries , $tmp);
			}
		}	
	}
}
