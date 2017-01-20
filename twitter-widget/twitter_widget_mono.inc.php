<?php
/*
 Twitter単一ウィジェット表示プラグイン
 twitter_widget_mono.inc.php

 rebuild by ponoca

 rebuild for personal use.

 user-id         : twitter user id
 data-id         : unique 18 digit number for tweet
 data-theme      : light(default) or dark
 data-link-color : link color in tweet (not "data-link_color")

----------------------------------------
 Twitterウィジェット表示プラグイン
 twitter_widget.inc.php

 by http://kaz-ic.net/

 Released under the MIT License.
 http://opensource.org/licenses/MIT
----------------------------------------
*/

function plugin_twitter_widget_mono_convert(){
	$params = array(
		'user-id'         => '',
		'data-id'         => '',
		'data-theme'      => 'light',
		'data-link-color' => '#66ABFF'
	);

	if(!func_num_args()) return FALSE;
	$args = func_get_args();
	if(!empty($args)) foreach($args as $arg) twitter_widget_mono_check_arg($arg, $params);

	return <<<EOD
<blockquote class="twitter-tweet" data-lang="ja" data-theme="{$params['data-theme']}" data-link-color="{$params['data-link-color']}">
<a href="https://twitter.com/{$params['user-id']}/status/{$params['data-id']}"></a></blockquote><script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
EOD;
}

function plugin_twitter_widget_mono_inline(){
	$args = func_get_args();
	return call_user_func_array('plugin_twitter_widget_mono_convert', $args);
}

function twitter_widget_mono_check_arg($val, & $params){
	if(!strpos($val, '=')) return;
	list($l_val, $v_val) = explode('=', strtolower($val));
	foreach(array_keys($params) as $key){
		if(strpos($l_val, $key) === 0){
			$params[$key] = $v_val;
			return;
		}
	}
}
?>
