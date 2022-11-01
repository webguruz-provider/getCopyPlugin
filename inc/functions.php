<?php
/*
* Send Curl Request
*/

/*
* Connect instagram api with curl. 
*/


function wgt_insta_api_curl_connect( $api_url ){
	
	$args = array(
			'timeout' => 60,
			'sslverify' => false
	);
	$response = wp_remote_get( $api_url, $args );

	if ( ! is_wp_error( $response ) ) {
		// certain ways of representing the html for double quotes causes errors so replaced here.
		$response = json_decode( str_replace( '%22', '&rdquo;', $response['body'] ), true );
	}

	return $response;
}

/*
* Get post by tag
*/

function wgt_insta_get_post_by_tag($access_token,$tag){
	if(isset($access_token) && isset($tag)){
	$return = wgt_insta_api_curl_connect('https://api.instagram.com/v1/tags/' . $tag . '/media/recent?access_token=' . $access_token);
	var_dump( $return ); // if you want to display everything the function returns
	foreach ( $return->data as $post ) {
		echo '<a href="' . $post->images->standard_resolution->url . '"><img src="' . $post->images->thumbnail->url . '" /></a>';
	}
	}
}

/*
* Get instagram user info by access token.
*/

function wgt_insta_get_user_by_token($access_token){
	if(isset($access_token))
	{ 
		$getoptions = get_option( 'wgt_insta_options' );
		if(isset($getoptions['wgt_type']) && $getoptions['wgt_type']=='busness' )
		{
		$account_id = wgt_get_b_user_data($access_token);
		$url_user='https://graph.facebook.com/v12.0/'. $account_id .'?fields=biography%2Cid%2Cusername%2Cwebsite%2Cprofile_picture_url,media_count,followers_count,follows_count&access_token='. $access_token;	
		}
		else
		{
		$url_user='https://graph.instagram.com/me?fields=id,account_type,media_count,username&access_token='. $access_token;
		}
		$return_user = wgt_insta_api_curl_connect($url_user);
		//var_dump( $return_user );
		return $return_user;
	}
}

function wgt_get_b_user_data($access_token){
	$url_user ='https://graph.facebook.com/v12.0/me/accounts?access_token='. $access_token;
	$data_user = wgt_insta_api_curl_connect($url_user);
	$pageid = $data_user['data'][0]['id'];
	$acc_user ='https://graph.facebook.com/v12.0/'. $pageid .'?fields=instagram_business_account&access_token='. $access_token;
	$accdata_user = wgt_insta_api_curl_connect($acc_user);
	return $accdata_user['instagram_business_account']['id'];
}
/*
* Get instagram user info by username
*/

function wgt_insta_get_by_username($username){
	if(isset($username)){
	
	$url='https://www.instagram.com/'. $username .'/?__a=1';
	$user_search = wgt_insta_api_curl_connect($url);
	//echo $user_search->video_url;
	//var_dump( $user_search );
	return $user_search;
	}
	
}

/*
* Get instagram user media post.
*/

function wgt_insta_get_post_by_access_token($access_token,$next=''){
	if(isset($access_token)){
	//&limit=2
	$nextpage='';
	$getoptions = get_option( 'wgt_insta_options' );
	$settingoptions = get_option( 'wgt_insta_general' );
	$per_page = (isset($settingoptions['wgt_insta_per_page']))?$settingoptions['wgt_insta_per_page']:6;
	if(isset($next) && !empty($next))
	{
	$url = $next;	
	}
	else
	{
	if(isset($getoptions['wgt_type']) && $getoptions['wgt_type']=='busness' )
	{
	$account_id = wgt_get_b_user_data($access_token);
	$url ='https://graph.facebook.com/v12.0/' . $account_id . '/media?fields=id,caption,media_type,media_url,thumbnail_url,comments_count,like_count,permalink,username&access_token='. $access_token .'&limit='. $per_page;
	}
	else
	{	
	$url='https://graph.instagram.com/me/media?fields=id,caption,media_type,media_url,thumbnail_url,permalink,username&access_token='. $access_token.'&limit='. $per_page;
	}
	}
	
	
	$return = wgt_insta_api_curl_connect($url);
	//var_dump( $return );
	/*foreach ($return->data as $post) {
	echo '<a href="' . $post->permalink . '" target="_blank"><img src="' . $post->media_url . '" /></a>';
	}*/
	return $return;
	}
	
}

function wgt_busness_get_post_by_access_token($access_token,$next=''){
	if(isset($access_token)){
	//&limit=2
	$nextpage='';
	$settingoptions = get_option( 'wgt_insta_general' );
	$per_page = (isset($settingoptions['wgt_insta_per_page']))?$settingoptions['wgt_insta_per_page']:6;
	if(isset($next) && !empty($next)){
	$url = $next;	
	}else{
	$url='https://graph.facebook.com/v12.0/17841449957129516/media?fields=id,caption,media_type,media_url,thumbnail_url,permalink,username&access_token='. $access_token.'&limit='. $per_page;
	}
	
	
	$return = wgt_insta_api_curl_connect($url);
	//var_dump( $return );
	/*foreach ($return->data as $post) {
	echo '<a href="' . $post->permalink . '" target="_blank"><img src="' . $post->media_url . '" /></a>';
	}*/
	return $return;
	}
	
}
/*
* Get instagram user refresh access token after very 59 days.
*/

function get_access_token($access_token){
	
	if(isset($access_token)){
	$url_get_token='https://graph.instagram.com/refresh_access_token?grant_type=ig_refresh_token&access_token='. $access_token;
	$return_token = wgt_insta_api_curl_connect($url_get_token);
	//var_dump( $return_user ); access_token
	$setoptions = get_option( 'wgt_insta_options' );
	$setoptions['wgt_insta_token'] = $return_token['access_token'];
	$setoptions['wgt_insta_token_type'] = $return_token['token_type'];
	$setoptions['wgt_insta_token_extime'] = $return_token['expires_in'];
	update_option('wgt_insta_options',$setoptions);
	}
}



// generate code generation url
function wgt_ig_getOAuthURL() {
	$AppCons = wgt_ig_AppConstants();
    $oauthURL = 'https://api.instagram.com/oauth/authorize/';
    $oauthURL = $AppCons['url'];
    $state_uri =  urlencode(wgt_ig_getIGStateURI());
    $red_uri = $AppCons['redURI'];
    $oauthURL .= "?client_id={$AppCons['clientID']}&response_type=code&scope=user_profile,user_media&state={$state_uri}&redirect_uri={$red_uri}";
    return $oauthURL;
}

// generate redirect uri 
function wgt_ig_getIGStateURI(){
	$url = admin_url('admin.php?page=wgt-getcopy');
	$url_str = base64_encode($url);
	return $url_str;
}
// generate redirect uri config
function wgt_ig_AppConstants() {
    $bvar = 'base6'.'4_de';
	$acs = [
	    'url' => 'aHR0cHM6Ly9nZXRjb3B5LmlvL2dFdENvUHlBcHAvYXBwLw==',
        'clientID' => 'NDE1MzA5NjYwMjk4MzE0',
        'redURI' => 'aHR0cHM6Ly9nZXRjb3B5LmlvL2dFdENvUHlBcHA='
    ];
    $bvar .= 'code';
    $acs = array_map($bvar,$acs);
    return $acs;
}

// Check token valid or not

function wgt_check_token(){
	$wgt_options = get_option( 'wgt_insta_options' );
	if(isset($wgt_options['wgt_insta_token'])){
		$token = $wgt_options['wgt_insta_token'];
		$user_data = wgt_insta_get_user_by_token($token);
		if(!empty($user_data)){
			if($user_data['id']){
				update_option('wgt_instagram_connect','connected');
				wgt_user_save();
			}else{
				update_option('wgt_instagram_connect','not');
			}
		}else{
			update_option('wgt_instagram_connect','not');
		}
	}else{
		update_option('wgt_instagram_connect','not');
	}
}

function wgt_request(){
	
	$AppCons = wgt_ig_AppConstants();
	$sitekey = $_SERVER['HTTP_HOST'];	
    $activation_uri = $AppCons['redURI'].'/wp-plugin-free/getcopyjson.php?site_key='. $sitekey;
		$remote = wp_remote_get(
					$activation_uri,
					array(
						'timeout' => 10,
						'headers' => array(
							'Accept' => 'application/json'
						)
					)
				);

				if(
					is_wp_error( $remote )
					|| 200 !== wp_remote_retrieve_response_code( $remote )
					|| empty( wp_remote_retrieve_body( $remote ) )
				) {
					return false;
				}
		if(!empty($remote)){
			set_transient( getcopycache_key, $remote, DAY_IN_SECONDS );
		}
		
	
	
	$remote = json_decode( wp_remote_retrieve_body( $remote ) );
	
			
	return $remote;
}
/*
*
*/
function wgt_get_customizer_css() {
    ob_start();
	$theme_settting = get_option( 'wgt_insta_general' );
	$theme_color = (isset($theme_settting['wgt_insta_theme_color']))?$theme_settting['wgt_insta_theme_color']:'';
	$theme_text_color = (isset($theme_settting['wgt_insta_theme_textcolor']))?$theme_settting['wgt_insta_theme_textcolor']:'';
    if ( ! empty( $theme_color ) ) {
      ?>
      .instagram-widget .user-info , button.wgt-load-more.btn , .footer-inner p {background: <?=$theme_color;?>;}
	  .instagram-widget .user-info , button.wgt-load-more.btn , .footer-inner p { color: <?=$theme_text_color;?>;}
      <?php
    }

    $css = ob_get_clean();
    return $css;
}
  
/*
* Creates custom database tables and directory for storing custom
* images
*/
function wgt_create_database_table() {
	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();
	$table_name      = esc_sql( $wpdb->prefix . 'wgt_instagram_posts' );

	if ( $wpdb->get_var( "show tables like '$table_name'" ) != $table_name ) {
		$sql = "CREATE TABLE " . $table_name . " (
		id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
		instagram_id VARCHAR(1000) DEFAULT '' NOT NULL,
		media_id VARCHAR(1000) DEFAULT '' NOT NULL,
		media_type VARCHAR(1000) DEFAULT '' NOT NULL,
		json_data LONGTEXT DEFAULT '' NOT NULL,
		created_on DATETIME,
		last_requested DATE
	) $charset_collate;";
		$wpdb->query( $sql );
	}
	$error = $wpdb->last_error;
	$query = $wpdb->last_query;
	
	$user_table_name = esc_sql( $wpdb->prefix . 'wgt_instagram_user' );

	if ( $wpdb->get_var( "show tables like '$user_table_name'" ) != $user_table_name ) {
		$sql = "CREATE TABLE " . $user_table_name . " (
		id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
		instagram_id VARCHAR(1000) DEFAULT '' NOT NULL,
		json_data LONGTEXT DEFAULT '' NOT NULL,
		created_on DATETIME,
		last_requested DATE
	) $charset_collate;";
		$wpdb->query( $sql );
	}
	$error = $wpdb->last_error;
	$query = $wpdb->last_query;
}

/*
* Save feeds in db
*/

function wgt_feed_save(){
	global $wpdb;
	$status='';
	$getoptions = get_option( 'wgt_insta_options' );
	if(isset($getoptions['wgt_insta_token'])){
	$token = $getoptions['wgt_insta_token'];
	$entry_string_arr=[];
	$user_data = wgt_insta_get_user_by_token($token);
	$row_cnt = (isset($user_data['media_count']))?$user_data['media_count']:1;
	$settingoptions = get_option( 'wgt_insta_general' );
	$limit = (isset($settingoptions['wgt_insta_per_page']))?$settingoptions['wgt_insta_per_page']:6;
	$totalPage= ceil($row_cnt/$limit);
	$nextpage='';
	for($i=0;$i<$totalPage;$i++){
	$media_data = wgt_insta_get_post_by_access_token($token,$nextpage);
	if(isset($media_data['data'])){
	foreach($media_data['data'] as $media){
	$entry_data = array(
			"'" . esc_sql( $media['username'] ) . "'",
			"'" . esc_sql( $media['id'] ) . "'",
			"'" . esc_sql( $media['media_type'] ) . "'",
			"'" . esc_sql( json_encode($media) ) . "'",
			"'" . date( 'Y-m-d H:i:s' ) . "'",
			"'" . date( 'Y-m-d H:i:s' ) . "'"
		);
    
	$entry_string_arr[] = '('. implode( ',',$entry_data ) .')';
	}//loop data array to srt
	if(isset($media_data['paging']['next'])){
		$nextpage=$media_data['paging']['next'];
		update_option('wgt_paging',$media_data['paging']);
	}//if paging
	else{
		break;
	}
	}//if data exist
	else{
		break;
	}
	
	}//for loop get next page data
	/*if(!empty($entry_string_arr)){*/
		
	$entry_string = implode( ',',$entry_string_arr );	
	
	$table_name = $wpdb->prefix . 'wgt_instagram_posts';
	$delete = $wpdb->query("TRUNCATE TABLE $table_name");
	$error = $wpdb->query( "INSERT INTO $table_name (instagram_id,media_id,media_type,json_data,created_on,last_requested) VALUES $entry_string;" );
	
	if ( $error !== false ) {
		$status='Done';
	}else{
	$status = $wpdb->last_error;
	$query = $wpdb->last_query;
	add_settings_error( 'wgt_save_in_db_message', 'wgt_save_in_db_message', __( 'Error inserting post.', 'wgt-insta' ) . ' ' . $status . '<br><code>' . $query . '</code>', 'error' );	
	}
	/*}*/
	
	}
	return $status;	
}

function wgt_user_save(){
	global $wpdb;
	$status='';
	$getoptions = get_option( 'wgt_insta_options' );
	if(isset($getoptions['wgt_insta_token'])){
	$token = $getoptions['wgt_insta_token'];
	$user_data = wgt_insta_get_user_by_token($token);
	$instagram_id = $user_data['id'];
	if(!empty($user_data)){
	$user_entry_data = array(
			"'" . esc_sql( $instagram_id ) . "'",
			"'" . esc_sql( json_encode($user_data) ) . "'",
			"'" . date( 'Y-m-d H:i:s' ) . "'",
			"'". date( 'Y-m-d H:i:s' ) . "'"
		);

	$user_entry_string = implode( ',',$user_entry_data );
	$user_table_name = $wpdb->prefix . 'wgt_instagram_user';
	
	$user_exist = $wpdb->get_var("SELECT COUNT(*) FROM $user_table_name WHERE instagram_id=$instagram_id");
	if($user_exist<1){
	$user_error = $wpdb->query( "INSERT INTO $user_table_name (instagram_id,json_data,created_on,last_requested) VALUES ($user_entry_string);" );
	}else{
	$user_error = $wpdb->query( "UPDATE $user_table_name SET json_data= '". esc_sql( json_encode($user_data) ) ."',last_requested='". date( 'Y-m-d H:i:s' ) ."' WHERE instagram_id=". $instagram_id );
	}
	if( $user_error !== false ) {
	$status='Done';
	}else{
	$status = $wpdb->last_error;
	$query = $wpdb->last_query;
	add_settings_error( 'wgt_save_in_db_message', 'wgt_save_in_db_message', __( 'Error inserting post.', 'wgt-insta' ) . ' ' . $status . '<br><code>' . $query . '</code>', 'error' );	
	}
	
	}
	
	}
	return $status;	
}
/*
* Get user info from db
*/

function get_user_info(){
	global $wpdb;
	$table_name = $wpdb->prefix . 'wgt_instagram_user';
	$results = $wpdb->get_row( "SELECT * FROM $table_name ORDER BY id DESC limit 1", ARRAY_A );
	if($results)
	{
		return $result = json_decode($results['json_data'], true);
	}
}
/*
* Get user feeds from db
*/

function get_feeds($offset=0,$limit=6,$type){
	global $wpdb;
	$media_arr=[];
   
	$table_name = $wpdb->prefix . 'wgt_instagram_posts';
	
	
	if($type == 'video')
	{		
		$results = $wpdb->get_results( "SELECT * FROM $table_name WHERE `media_type` = 'VIDEO' ORDER BY id DESC limit $offset,$limit", ARRAY_A );
		$count_results = $wpdb->get_var( "SELECT count(id) FROM $table_name WHERE `media_type` = 'VIDEO'");
	}
	else
	{		
		$results = $wpdb->get_results( "SELECT * FROM $table_name ORDER BY id DESC limit $offset,$limit", ARRAY_A );
		$count_results = $wpdb->get_var( "SELECT count(id) FROM $table_name" );
	}

	if($results)
	{
		$media_arr['num_rows']=$count_results;
		foreach ($results as $post) 
		{
			$media_arr['data'][]= json_decode($post['json_data'], true);
		}
		$media_arr['data'] = array_reverse($media_arr['data']);

		return $result = $media_arr;	
	}
	
}


add_filter( 'theme_page_templates', 'pt_add_page_template_to_dropdown' );	
function pt_add_page_template_to_dropdown( $templates )
{	
   $path = wp_normalize_path(plugin_dir_path( __FILE__ ).'/templates/insta-template.php');
   $templates[$path] = __( 'Instagram Template', 'text-domain' );    
   return $templates;
}

add_filter('template_include', 'pt_change_page_template', 99);
function pt_change_page_template($template)
{
    if (is_page()) 
    {
        $meta = get_post_meta(get_the_ID());

        if (!empty($meta['_wp_page_template'][0]) && $meta['_wp_page_template'][0] != $template) 
        {
        	if(strpos($meta['_wp_page_template'][0], 'insta-template') !== false)
        	{
        	
        		$template = $meta['_wp_page_template'][0];

        	}            
        }
    }

    return $template;
}

?>
