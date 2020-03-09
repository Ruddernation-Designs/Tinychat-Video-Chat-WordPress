<?php
/*
* Plugin Name: HTML5 Video Chat - Tinychat
* Plugin URI: https://wordpress.org/plugins/tc-video-chat/
* Author: Ruddernation Designs
* Author URI: https://profiles.wordpress.org/ruddernationdesigns
* Description: TinyChat full screen video chat for WordPress/BuddyPress in HTML5 WebRTC, This advanced version allows you to add your own room name, To use YouTube then use Firefox or Edge browsers as the videos work then and you can also select to play a video, Do not use Chrome!
* Requires at least: WordPress 4.6, BuddyPress 4.0
* Tested up to: WordPress 5.4
* Version: 1.5.6
* License: GNUv3
* License URI: https://www.gnu.org/licenses/gpl-3.0.en.html
* Date: 09th March 2020
*/
define('COMPARE_VERSION', '1.5.5');
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

register_activation_hook(__FILE__, 'tc_video_chat_install');

function tc_video_chat_install() {
	
	global $wpdb, $wp_version;
	$post_date = date("Y-m-d H:i:s");
	$post_date_gmt = gmdate("Y-m-d H:i:s");
	$sql = "SELECT * FROM ".$wpdb->posts." WHERE post_content LIKE '%[tc_video_chat_page]%' AND `post_type` NOT IN('revision') LIMIT 1";
	$page = $wpdb->get_row($sql, ARRAY_A);
	
	if($page == NULL) {
		$sql ="INSERT INTO ".$wpdb->posts."(
			post_author, post_date, post_date_gmt, post_content, post_content_filtered, post_title, post_excerpt,  post_status, comment_status, ping_status, post_password, post_name, to_ping, pinged, post_modified, post_modified_gmt, post_parent, menu_order, post_type)
VALUES ('1', '$post_date', '$post_date_gmt', '[tc_video_chat_page]', '', 'Tinychat Video Chat', '', 'publish', 'closed', 'closed', '', 'video-chat', '', '', '$post_date', '$post_date_gmt', '0', '0', 'page')";
		$wpdb->query($sql);
		$post_id = $wpdb->insert_id;
		$wpdb->query("UPDATE $wpdb->posts SET guid = '" . get_permalink($post_id) . "' WHERE ID = '$post_id'");
	}
	else
	{
		$post_id = $page['ID'];
	}
	update_option('tc_video_chat_url', get_permalink($post_id));
}
add_filter('the_content', 'wp_show_tc_video_chat_page', 222);
function wp_show_tc_video_chat_page($content = '') {
	if(preg_match("/\[tc_video_chat_page\]/",$content)) {
		wp_show_tc_video_chat();
		return "";
	}
	return $content;
}
function wp_show_tc_video_chat() {
	if(!get_option('tc_video_chat_enabled', 0)) {
	}
?>
<h2>Tinychat Video Chat</h2>
<br>
<form method="post" class="form">
<input type="text" name="room" title="Enter Room Name, If it does not exist then it will create the room for you." tabindex="1" placeholder="Tinychat room name" autofocus required/>
<input type="submit" class="button2" value="Chat"/></form>
<br>
<p>To watch YouTube videos please use Firefox/Edge/Opera browsers, These have been tested and the videos work on them.</p><br>

<strong>This allows you to join Tinychat chat rooms with Camera/Mic of up to 12 people,<br>
	It also has YouTube so you can play your videos, There are hundreds of registered &amp; unregistered chat rooms that you can join.
	</strong>
</p>   
<?php
	$room = filter_input(INPUT_POST, 'room');
	if(preg_match('/^[a-z0-9]/', $room=strtolower($room))) 
	{
		$room=preg_replace('/[^a-zA-Z0-9]/','',$room);
		{
		if (strlen($room) < 3)
		{
			echo '<p>The Tinychat room needs to be more than 3 characters.</p>'; 
		}
		else
			if (strlen($room) > 32) 
			{
				echo '<p>The Tinychat room needs to be less than 32 characters.</p>';
			} 
		else
		{
			echo '<style>iframe {width: 100%;height: 98%;position:fixed; top:0px;left:0px;right:0px;bottom:0px;z-index:9999999;}</style>
				
<iframe src="https://tinychat.com/room/'.$room.'" name="room" frameborder="0" scrolling="no" height="98%" width="100%" allow="geolocation; microphone; camera;"></iframe>';
            }
					}
							}
									}?>
