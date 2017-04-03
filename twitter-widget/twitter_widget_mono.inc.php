<?php
/*
 Twitter単一ウィジェット表示プラグイン
 twitter_widget_mono.inc.php,v 1.01 2017/01/21 13:27:00

 rebuild by ponoca

 rebuild for personal use.

 user-id           : twitter user id
 data-id           : unique 64bit unsigned number for tweet
 *defalut option
 data-lang         : ja(default)
 data-theme        : light(default) or dark
 data-link-color   : link color in tweet (not "data-link_color")

 *additional option
 width             : tweet maximum width between 250-550 pixcels
 data-cards        : set "hidden" to hide media
 data-conversation : set "none" to hide previous tweet in conversation thread
 align             : set "left/center/right" to align tweet container

 v 1.00 初版
 v 1.01 タグ埋め込み回避、引数チェック処理追加
*/

// Pattern of ID (数字のみ)
define('PLUGIN_TWITTER_WIDGET_MONO_ID_REGEX', '/^[0-9]*$/');

// Show usage
function plugin_twitter_widget_mono_usage($convert = TRUE, $message = '')
{
	if ($convert) {
		if ($message == '') {
			return 'USAGE: #twitter_widget_mono(user-id=<i>user-id</i>,data-id=<i>data-id</i>[,data-theme=<i>light</i>|<i>dark</i>][,data-link-color=<i>#ZZZZZZ<i>)' . '<br />';
		} else {
			return 'USAGE: #twitter_widget_mono: ' . $message . '<br />';
		}
	} else {
		if ($message == '') {
			return 'USAGE: &amp;twitter_widget_mono(user-id=<i>user-id</i>,data-id=<i>data-id</i>[,data-theme=<i>light</i>|<i>dark</i>][,data-link-color=<i>#ZZZZZZ<i>);';
		} else {
			return 'USAGE: &amp;twitter_widget_mono: ' . $message . ';';
		}
	}
}

function plugin_twitter_widget_mono_convert(){
	$params = array(
		'user-id'           => '',
		'data-id'           => '',
		'data-lang'         => 'ja',
		'data-theme'        => 'light',
		'data-link-color'   => '#66ABFF',
		'width'             => '-1',
		'data-cards'        => '',
		'data-conversation' => '',
		'align'             => '',
		'type'              => '',
		'data-status'       => ''
	);
	$convert = TRUE;
	if (func_num_args() < 2)
		return plugin_twitter_widget_mono_usage($convert);
	$args = func_get_args();
	if (!empty($args)) foreach($args as $arg) twitter_widget_mono_check_arg($arg, $params);
	if (strlen($params['user-id']) < 1)
		return plugin_twitter_widget_mono_usage($convert, 'no user-id is specified');
	if (! preg_match(PLUGIN_TWITTER_WIDGET_MONO_ID_REGEX, $params['data-id']))
		return plugin_twitter_widget_mono_usage($convert, 'invalid data-id string: ' . $params['data-id']);
	if (! is_numeric($params['width']))
		return plugin_twitter_widget_mono_usage($convert, 'invalid width size: ' . $params['width']);
	$blockwidth = ($params['width'] > 0) ? ' width="' . $params['width'] . '"' : '';
	$cards = ($params['data-cards'] == 'hidden') ? ' data-cards="hidden"' : '';
	$conversation = ($params['data-conversation'] == 'none') ? ' data-conversation="none"' : '';
	$data_align = ($params['align'] == '') ? '' : ' data-align="' . $params['align'] . '"';
	$tweet_type = $params['type'];
	$data_status = ($params['data-status'] == 'hidden') ? ' data-status=hidden' : '';

	if ($params['type'] == 'video'){
	return <<<EOD
<blockquote class="twitter-video" data-lang="{$params['data-lang']}"$data_status>
<a href="https://twitter.com/{$params['user-id']}/status/{$params['data-id']}"></a></blockquote><script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
EOD;
	} else{
	return <<<EOD
<blockquote class="twitter-tweet" data-lang="{$params['data-lang']}" data-theme="{$params['data-theme']}" data-link-color="{$params['data-link-color']}"$blockwidth$cards$conversation$data_align>
<a href="https://twitter.com/{$params['user-id']}/status/{$params['data-id']}"></a></blockquote><script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
EOD;
	}
}

function plugin_twitter_widget_mono_inline(){
	$convert = FALSE;
	if (func_num_args() < 3)
		return plugin_twitter_widget_mono_usage($convert);
	$args = func_get_args();
	return call_user_func_array('plugin_twitter_widget_mono_convert', $args);
}

function twitter_widget_mono_check_arg($val, & $params){
	if (!strpos($val, '=')) return;
	list($l_val, $v_val) = explode('=', strtolower($val));
	foreach(array_keys($params) as $key){
		if (strpos($l_val, $key) === 0){
			$params[$key] = htmlspecialchars($v_val);
			return;
		}
	}
}
?>
