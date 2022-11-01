<?php
/**
 * Add the top level menu page.
*/
function wgt_insta_options_page() {
		add_menu_page(
			'gEtCoPy',
			'gEtCoPy',
			'manage_options',
			'wgt-getcopy',
			'wgt_insta_options_page_html',
			'dashicons-instagram',
			25
		);
	}
	 
	 
/**
 * Register menu in admin panel.
*/
add_action( 'admin_menu', 'wgt_insta_options_page' );
	
/**
 * Top level menu callback function
 */
 
function wgt_insta_options_page_html() 
{

    // check user capabilities
    if ( ! current_user_can( 'manage_options' ) ) 
    {
        return;
    }
	
	if ( isset( $_GET['settings-updated'] ) )
	{
        // add settings saved message with the class of "updated"
        add_settings_error( 'wgt_insta_messages', 'wgt_insta_message', __( 'Settings Saved', 'wgt-insta' ), 'updated' );
    }

    if(isset($_REQUEST['account']))
    {
		if($_REQUEST['account'] == 'disconnect')
		{
			update_option('wgt_instagram_connect','disconnect');
			global $wpdb;
			$wpdb->query("TRUNCATE TABLE `wp_wgt_instagram_user`");
			$wpdb->query("TRUNCATE TABLE `wp_wgt_instagram_posts`");
		}
	}
 
    // show error/update messages
    settings_errors( 'wgt_insta_messages' );
		
	?>
    <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		<?php //print_r($_REQUEST);
		$active_tab = (isset($_GET['tab']))?$_GET['tab']:'homepage';		
		do_action('wgt_insta_admin_tabs',$active_tab);		
		?>
        <form action="options.php" method="post">
            <?php
			if($active_tab=='general')
			{
				settings_fields( 'wgt_insta_general' );			
            	do_settings_sections( 'wgt_insta_general' );
            	//do_settings_sections( 'wgt_insta_general_logo' );
			}
			else if($active_tab=='footer')
			{
				settings_fields( 'wgt_insta_footer' );
            	do_settings_sections( 'wgt_insta_footer' );	
			}
			else
			{			
				settings_fields( 'wgt_insta_auth' );			
            	do_settings_sections( 'wgt_insta_auth' );	
			}
            
            // output save settings button
			submit_button( 'Save Settings' );
		
            ?>
        </form>
		<p>You can use this shortcode <code>[instagram]</code> in page or post.</p>
    </div>
    <?php
	
}

// save token and data
add_action('admin_init','wgt_admin_init_callback'); 
function wgt_admin_init_callback()
{
	
	if (!current_user_can('administrator')) 
	{
		return;
	}
	
	if (!empty($_GET['wgt_access_token'])) 
	{
		$wgt_options = get_option( 'wgt_insta_options' );
		// check again for empty string
		if (empty($wgt_options)) 
		{
			$wgt_options = [];
		}
		$acc_type = ($_GET['type'])?$_GET['type']:'personal';
		$wgt_options['wgt_insta_token'] = $_GET['wgt_access_token'];
		$wgt_options['wgt_insta_token_type'] = $_GET['token_type'];
		$wgt_options['wgt_insta_token_extime'] = $_GET['expires_in'];
		$wgt_options['wgt_type'] = $_GET['type'];
		update_option('wgt_insta_options',$wgt_options);
		wgt_feed_save();
		wgt_check_token();
		wgt_user_save();
		$newURL = admin_url('admin.php?page=wgt-getcopy');
		header('Location: '.$newURL);
	}
	
	if( !wp_next_scheduled('wgt_insta_cron_schedule_token_refresh') )
	{
		wp_schedule_event( time(), 'sixtydays', 'wgt_insta_cron_schedule_token_refresh' );
	}
	/* get feeds each 30 mint thirtyminutes,daily*/
	if( !wp_next_scheduled('wgt_insta_cron_schedule_get_feed') )
	{
		wp_schedule_event( time(), 'thirtyminutes', 'wgt_insta_cron_schedule_get_feed' );
	}	
	
}

add_action('init','wgt_init_callback');
function wgt_init_callback()
{
	//wgt_feed_save();
	if( !wp_next_scheduled('wgt_insta_cron_schedule_token_refresh') )
	{
		wp_schedule_event( time(), 'sixtydays', 'wgt_insta_cron_schedule_token_refresh' );
	}
	/* get feeds each 30 mint thirtyminutes,daily*/
	if( !wp_next_scheduled('wgt_insta_cron_schedule_get_feed') )
	{
		wp_schedule_event( time(), 'thirtyminutes', 'wgt_insta_cron_schedule_get_feed' );
	}
}

/*
* Displays all messages registered to 'your-settings-error-slug'
*/
function wgt_admin_notices_action() 
{
    settings_errors( 'wgt_save_in_db_message' );
}
add_action( 'admin_notices', 'wgt_admin_notices_action' );

/*
* Set time cron job
*/
add_filter( 'cron_schedules', function ( $schedules ) 
{
   $schedules['sixtydays'] = array(
       'interval' => 60*60*24*59,
       'display' => __( 'Sixty Days' )
   );
   $schedules['thirtyminutes'] = array(
       'interval' => 60*30,
       'display' => __( '30 Minutes' )
   );
   return $schedules;
} );
/*
* Need refresh token, long token will expire after 60 days.
*/
add_action( 'wgt_insta_cron_schedule_token_refresh', 'do_wgt_insta_cron_schedule' );
 
function do_wgt_insta_cron_schedule() 
{
    // do something every sixty days
	$getoptions = get_option( 'wgt_insta_options' );
	if(isset($getoptions['wgt_insta_token']))
	{
		$token = $getoptions['wgt_insta_token'];
		get_access_token($token);
	}
}

/*
* Need refresh get feed, each after 30minutes,hourly,twicedaily,daily days.
*/
add_action( 'wgt_insta_cron_schedule_get_feed', 'do_wgt_insta_cron_schedule_get_feed' );
 
function do_wgt_insta_cron_schedule_get_feed() 
{
    //do something every sixty days
	wgt_feed_save();
}
/*
* Create tabs in setting page
*/
add_action('wgt_insta_admin_tabs','wgt_insta_admin_tabs_call',10,1);
function wgt_insta_admin_tabs_call( $current = 'homepage' ) 
{
    $tabs = array( 'homepage' => 'Connect', 'general' => 'General', 'footer' => 'Footer' );
    echo '<div id="icon-themes" class="icon32"><br></div>';
    echo '<h2 class="nav-tab-wrapper">';
    foreach( $tabs as $tab => $name )
    {
        $class = ( $tab == $current ) ? ' nav-tab-active' : '';
        echo "<a class='nav-tab $class' href='?page=wgt-getcopy&tab=$tab'>$name</a>";
    }
    echo '</h2>';
}
add_action( 'wp_ajax_wgt_loadmore_action', 'wgt_loadmore_action_call' );
add_action( 'wp_ajax_nopriv_wgt_loadmore_action', 'wgt_loadmore_action_call' );

function wgt_loadmore_action_call()
{
	$html='';
	if($_REQUEST['paged'])
	{
		$paged = $_REQUEST['paged'];
		$type  = $_REQUEST['type'];
		$general_options = get_option( 'wgt_insta_general' );
		$per_page = (isset($general_options['wgt_insta_per_page']))?$general_options['wgt_insta_per_page']:6;
		$offset= ($paged - 1) * $per_page;
		$media_data = get_feeds($offset,$per_page,$type);
		$num_rows = $media_data['num_rows'];
		$total_pages = ceil($num_rows/$per_page);
		$nextpage = ($total_pages>$paged)?'true':'false';
		foreach ($media_data['data'] as $post) 
		{
		$html .='<div class="colunm insta-item">';
		if($post['media_type']=='VIDEO')
		{ 
			$url_thumbnail = $post['thumbnail_url']; 
			$html.='<a href="'.$post['permalink'].'" target="_blank">';
			$html.='<video width="320" height="240" controls><source src="'.$post['media_url'].'" type="video/mp4"><source src="'.$post['media_url'].'" type="video/ogg">Your browser does not support the video tag.
		   </video>';
		   $html.='</a>';
		}
		else
		{ 
			$url_thumbnail = $post['media_url']; 
			$html .='<a class="insta-link" href="' . $post['permalink'] . '" target="_blank" title="'. $post['caption'] .'"><img alt="'. $post['caption'] .'" src="' . $url_thumbnail . '" /><div class="insta-overlay"><i class="fab fa-instagram"></i></div></a>';
		}
		
		$html .='</div>';
		}
	}
	echo json_encode(array('nextpage'=>$nextpage,'data'=>$html,'type'=>$type));
	wp_die();
}
?>