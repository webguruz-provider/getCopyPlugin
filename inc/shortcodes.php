<?php
/*
* Get Short code for use in page or post etc.
*/

/*
* Create shortcode function.
*/
	
add_shortcode( 'instagram', 'wgt_insta_shorcode_call_func' );
function wgt_insta_shorcode_call_func() 
{
	
    $general_options = get_option( 'wgt_insta_general' );
    $account_status  = get_option( 'wgt_instagram_connect' );
    $footer_options  = get_option( 'wgt_insta_footer' );
	$offset=0;
	
	$per_page = (isset($general_options['wgt_insta_per_page']))?$general_options['wgt_insta_per_page']:6;
	$colunm = (isset($general_options['wgt_insta_per_row']))?$general_options['wgt_insta_per_row']:'three';
	$footer_info = (isset($footer_options['wgt_insta_ft_info']))?$footer_options['wgt_insta_ft_info']:'';
	
	/*******Grid Design********/
	$grid_design = (isset($general_options['wgt_insta_design']))?$general_options['wgt_insta_design']:'grid';
		
	if($grid_design != 'grid')
	{
		$grid_design == 'smllarg';
		$colunm_class = 'insta-col-'. $colunm.' '.$grid_design.'';
	}
	else
	{
		$colunm_class = 'insta-col-'. $colunm;
	}
	/*******end Grid Design********/	
	
	$html ='';
	$media_data = get_feeds($offset,$per_page,$type='post');
	if($media_data)
	{
	$html.='<div id="instagram-widget" class="instagram-widget">';
	$getoptions = get_option( 'wgt_insta_options' );
	$options = get_option( 'wgt_insta_general' );
    
    if(!empty($options['wgt_insta_logo']))
    {
    	$img = wp_get_attachment_image_src( $options['wgt_insta_logo'], 'medium' );	
    }
    
     
    if(!empty($img[0]))
    {
    	$profile_image = '<img src="'.$img[0].'">';
    }
    else
    {
    	$profile_image = '<i class="fab fa-instagram"></i>';
    }

	
	$user_data = get_user_info();	
	$num_rows = $media_data['num_rows'];
	$html .='<div class="user-info">';
	$html .='<div class="user-info-name sh"><span>'.$profile_image.'</span> '. $user_data['username'] .'</div>';
	$html .='<div class="user-info-name"><span><i class="fa fa-camera-retro"></i></span> '. $user_data['media_count'] .'</div>';
	$html .='</div>';
	
	$html .='<div class="wc-all-posts">';			
	$html .='<div class="row insta-post-list '. $colunm_class .'">';
	foreach ($media_data['data'] as $post) 
	{
	$link = (isset($post['permalink']))? $post['permalink'] : '';
	$caption = (isset($post['caption']))? $post['caption'] : '';
	$html .='<div class="colunm insta-item">';
	if($post['media_type']=='VIDEO')
	{ 
		$url_thumbnail = $post['thumbnail_url']; 
	}
	else
	{ 
		$url_thumbnail = $post['media_url']; 
	}
	$html .='<a class="insta-link" href="' . $link . '" target="_blank" title="'. $caption .'"><img alt="'. $caption .'" src="' . $url_thumbnail . '" /><div class="insta-overlay"><i class="fab fa-instagram"></i></div></a>';
	$html .='</div>';
	}
	$html .='</div>';	
	$html .='</div>';
	
	if($num_rows>$per_page)
	{
		$html .='<div class="wgt-load-more"><button type="post" id="wgt-loadmore" class="wgt-load-more btn">Load More</button></div>';
	}
	
	
	$html .='</div>';
	$html .='</div>';
	}
	else
	{
		$html.='Please connect your account.';
	}
    return $html;
}
?>
