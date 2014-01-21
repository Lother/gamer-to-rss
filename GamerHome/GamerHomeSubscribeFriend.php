<?php
/**
 * 巴哈小屋轉換 RSS
 */
class GamerHomeSubscribeFriend extends GamerHome{
	protected $_creation_url = 'http://home.gamer.com.tw/subscribe_creation.php?type=friend&owner=';
	protected $_title = ' 的好友創作 RSS';
	protected $_find_page = '好友創作';
	protected $_detail_url = 'http://home.gamer.com.tw/creationDetail.php?sn=';

	protected function parseEntries()
	{
		if(preg_match_all('#<a href="creationDetail\.php\?sn=(\d+)">([^<]+)<\/a><\/td>[^<]+<[^<]+<a href="[^"]+">([^<]+)<\/a><\/td>[^>]+>(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})<\/td>#isu', $this->_html, $matches)){
			foreach($matches[1] as $i => $id){
				$tmp = array(
					'id' => $id,
					'title' => $matches[2][$i],
					'url' => $this->_detail_url.$id,
					'author' => $matches[3][$i],
					'description'  => $matches[3][$i].' 的 小屋創作',
					'pubdate' => $matches[4][$i],
				);
				$html2 = file_get_contents($this->_detail_url.$id);
				if (preg_match('#<div class="MSG-list8C">(.+)<\/div>\n<div class="MSG-list8E">#isu',$html2,$matches3))
					$tmp['description'] = htmlspecialchars($matches3[1]);
				
				if (preg_match_all('#reply_div_([^"]+)[^<]+<[^<]+<[^<]+<[^<]+<[^<]+<A class="msgname AT1" href="([^=]+=)([^"]+)">([^<]+)[^>]+>[^>]+>(.+?)<SPAN class="msgtime ST1">(:?(\d{2}-\d{2} \d{2}:\d{2})|([^天<]+)天(\d{2}:\d{2})|(\d{1,2})([^前<]+)前|1分內)<\/SPAN>#isu',$html2,$matches2))
				{
					foreach($matches2[1] as $j => $cid ){
						if($this->_owner==$matches2[3][$j])
						{
							$tmp2 = array(
								'id' => $cid,
								'title' => $matches2[4][$j]." 回應 ".$matches[2][$i],
								'url' => $this->_detail_url.$id."&cid=".$cid,
								'author' => $matches2[3][$j],
								'description'  => $matches[2][$i].'的 小屋創作回覆',
								'pubdate' => $matches2[6][$j],
							);
							$tmp2['description'] = htmlspecialchars ( str_replace("<P class=msgitembar>","",$matches2[5][$j]));
							
							if($matches2[7][$j]!="")
								$tmp2['pubdate'] =  date("Y")."-" . $matches2[6][$j] . ":00";
							else if ($matches2[8][$j]!=""&&$matches2[9][$j]!="")
								$tmp2['pubdate'] = date("Y-m-d ".$matches2[9][$j].":s",strtotime("-".(($matches2[8][$j]=="昨")?1:2) ." day"));
							else if($matches2[10][$j]!=""&&$matches2[11][$j]!="")
								$tmp2['pubdate'] = date("Y-m-d H:i:s",strtotime("-".$matches2[10][$j] ." ". (($matches2[11][$j]=="分")?" minute":"hour")));
							else
								$tmp2['pubdate'] = date("Y-m-d H:i:s",time());
							if(strtotime($tmp2['pubdate']) - strtotime($matches[4][$i]) > 60*60*24*365)
								$tmp2['pubdate'] = date("Y-m-d H:i:s",strtotime($tmp2['pubdate'] . " - 1 years"));		
							array_push($this->_entries ,$tmp );
							array_push($this->_entries ,$tmp2 );
						}
					}
				}
			}
		}
	}
	
}
