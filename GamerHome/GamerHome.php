<?php
abstract class GamerHome {

	protected $_creation_url;
	protected $_owner;
	protected $_filter;
	protected $_html;
	protected $_entries;
	protected $_title;
	protected $_find_page;
	
	function __construct($owner = '',$filter = '')
	{
		if(empty($owner)) die('要提供帳號.');
		$this->_owner = $owner;
		$this->_filter = $filter;
		$this->_creation_url .= $this->_owner;
	}

	public function asXml()
	{
		$this->_html = $this->getPage();
		$this->_entries = array();
		$this->parseEntries();
		$this->asRss();
	}

	protected function getPage()
	{
		$html = file_get_contents($this->_creation_url);
		if(strpos($html, $this->_find_page)===false) die('查無此人.');
		return $html;
	}

	abstract protected function parseEntries();

	protected function asRss()
	{
		$XML = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><rss version="2.0" />');
		$channel = $XML->addChild('channel');
		$channel->addChild('title', $this->_owner. $this->_title);
		$channel->addChild('link', htmlspecialchars($this->_creation_url));
		$channel->addChild('language', 'zh-tw');
		$channel->addChild('lastBuildDate', date("D, j M Y H:i:s +0800", time()));
		$channel->addChild('ttl', '20');

		foreach($this->_entries as $id => $entry){
			$item = $channel->addChild('item');
			$item->addChild('title', $entry['title']);
			$item->addChild('link', htmlspecialchars($entry['url']));
			$item->addChild('author', $entry['author']);
			$item->addChild('description', $entry['description']);
			$item->addChild('pubDate', date("D, j M Y H:i:s +0800", strtotime($entry['pubdate'])));
		}

		header('Content-type: text/xml');
		echo $XML->asXML();
	}

}
