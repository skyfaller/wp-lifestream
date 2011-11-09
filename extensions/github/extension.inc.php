<?php
class Lifestream_GitHubFeed extends Lifestream_Feed
{
	const ID			= 'github';
	const NAME			= 'GitHub';
	const URL			= 'http://github.com/';
	const DESCRIPTION	= 'You can obtain your GitHub feed URL from the <a href="https://github.com/dashboard/yours">Your Dashboard</a> page. You will find the feed link in orange feed icon next to "News Feed".';
	const LABEL			= 'Lifestream_CommitLabel';

	function parse_message($text)
	{
		preg_match('/<blockquote title=\"([^\"]+?)(?:\sgit-svn-id\:\s[^\"]+)?\">/i', $text, $match);
		return $match[1];
	}
	function parse_repo($text)
	{
		preg_match('/pushed to (.+) at (.+\/.+)/i', $text, $match);
		return array($match[1], $match[2]);
	}
	
	function get_repository_name(&$event, &$bit)
	{
		return $bit['repository'];
	}
	
	function get_repository_link(&$event, &$bit)
	{
		$name = $this->get_repository_name($event, $bit);
		if (!$name) return;
		return $this->lifestream->get_anchor_html($this->get_repository_name($event, $bit), sprintf('http://github.com/%s/', $name));
	}
	
	function get_public_url()
	{
		$url = 'http://github.com/';  // default
		if (preg_match('/^http:\/\/[w.]*github\.com\/(.*)\.atom$/', $this->get_option('url'), $matches)) {
			$url = 'http://github.com/'. $matches[1];
		}
		return $url;
	}
	
	function yield($row, $url, $key)
	{
		if (strpos($row->get_id(), "PushEvent") === false) {
			return null;
		} else {
			$data = parent::yield($row, $url, $key);
			$description = $this->lifestream->html_entity_decode($row->get_description());
			$message = $this->parse_message($description);
			if (!$message)
			{
				var_dump(htmlspecialchars($description));
			}
			$data['title'] = $message;
			$repo = $this->parse_repo($row->get_title());
			$data['branch'] = $repo[0];
			$data['repository'] = $repo[1];
			$data['group_key'] = $data['repository'];
			return $data;
		}
	}
}
$lifestream->register_feed('Lifestream_GitHubFeed');
?>
