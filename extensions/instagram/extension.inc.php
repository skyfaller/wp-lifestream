<?php
class Lifestream_InstagramFeed extends Lifestream_PhotoFeed
{
	const ID	= 'instagram';
	const NAME	= 'Instagram';
	const URL	= 'http://www.instagram.com/';
	
	function get_options()
	{		
		return array(
			'username' => array($this->lifestream->__('Username:'), true, '', ''),
			'userid' => array($this->lifestream->__('User ID:'), true, '', ''),
		);
	}
	
	function get_public_url()
	{
		return 'http://instagram.heroku.com/users/'.$this->get_option('username');
	}

	function get_url()
	{
		return 'http://instagram.heroku.com/users/'.$this->get_option('userid').'.atom';
	}

	function get_thumbnail_url($row, $item)
	{
		preg_match('/img src="\/([^"]+)"/', $item['content'], $matches);
		return $matches[1];
	}
}
$lifestream->register_feed('Lifestream_InstagramFeed');
?>
