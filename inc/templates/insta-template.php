<?php 
wp_head();
$general_options = get_option( 'wgt_insta_general' );
$account_status  = get_option( 'wgt_instagram_connect' );
$footer_options  = get_option( 'wgt_insta_footer' );
$wgt_options     = get_option( 'wgt_insta_options' );
$offset          = 0;
$per_page        = (isset($general_options['wgt_insta_per_page']))?$general_options['wgt_insta_per_page']:6;
$colunm          = (isset($general_options['wgt_insta_per_row']))?$general_options['wgt_insta_per_row']:'three';
$footer_info     = (isset($footer_options['wgt_insta_ft_info']))?$footer_options['wgt_insta_ft_info']:'';
$grid_design     = (isset($general_options['wgt_insta_design']))?$general_options['wgt_insta_design']:'grid';
		
if($grid_design != 'grid')
{
	$grid_design == 'smllarg';
	$colunm_class = 'insta-col-'. $colunm.' '.$grid_design.'';
}
else
{
	$colunm_class = 'insta-col-'. $colunm;
}

$getoptions = get_option( 'wgt_insta_options' );
$options    = get_option( 'wgt_insta_general' );

if(isset($getoptions['wgt_insta_token']))
{
	$token = $getoptions['wgt_insta_token'];
}
$user_data = get_user_info();
$media_data   = get_feeds($offset,$per_page,$type='post');
$media_videos = get_feeds($offset,$per_page,$type='video');
if($media_data)
{
	$num_rows     = $media_data['num_rows'];
	
}

if($media_videos)
{
	$media_rows   = $media_videos['num_rows'];		
}

?>

<header  class="insta-header">
   <div class="cstm-container">
   	  <div class="brand-wrap">
   	  	<a href="<?php echo home_url(); ?>" class="navbrand">

            <?php
			
			if(!empty($options['wgt_insta_logo']))
			{
				$img = wp_get_attachment_image_src( $options['wgt_insta_logo'], 'medium' );	
			}			    
		    
		    if(!empty($img[0]))
		    {
		    	echo '<img src="'.$img[0].'">';
		    }
		    else
		    {
		    	echo '<img src="'.plugins_url().'/gEtCoPy/images/instagram-logo-name.png" alt="logo">';
		    }
            ?>
   	  		
   	  	</a>
   	  	<div class="header-title">
   	  		<?php
   	  		if(!empty($options['wgt_insta_site_title']))
   	  		{
   	  			echo '<h3 style="color:'.$options['wgt_insta_theme_textcolor'].'">'.$options['wgt_insta_site_title'].'</h3>';
   	  		}
   	  		else
   	  		{
   	  			if(!empty($options['wgt_insta_theme_textcolor']))
   	  			{
   	  				$wc_color = $options['wgt_insta_theme_textcolor'];
   	  			}
   	  			else
   	  			{
   	  				$wc_color = '';
   	  			}
   	  			if($user_data)
   	  			{
   	  				echo '<h3 style="color:'.$wc_color.'">'.$user_data['username'].'</h3>';
   	  			}  
   	  			else 
   	  			{
   	  				echo '<h3 style="color:'.$wc_color.'">Insta Profile Name</h3>';
   	  			}	  			
   	  		}
   	  		?>
   	  	</div>
   	  </div>
   </div>	
</header>

<?php
if($options)
{
	if(array_key_exists('wgt_insta_theme_color',$options))
	{
		$background = $options['wgt_insta_theme_color'];
	}
	else
	{
		$background = '';
	}	
}
else
{
	$background = '';	
}
?>

<div class="full-width-container" style="background: <?php echo $background;?>">

	<div class="cstm-container">
		
		<?php
		if(empty($media_data))
		{
			echo 'Please connect your Instgram account from Admin';
		}
		else
		{
		?>
		<div class="profile-wrap">
          <div class="profile-pic">
          	<figure>
          		<?php 
                  if(@$user_data['profile_picture_url'])
                  {
              		 echo '<img src="'.$user_data['profile_picture_url'].'" alt="profile">';
                  }
                  else
                  {
                  	echo '<img src="'.plugins_url().'/gEtCoPy/images/avatar.png" alt="profile">';
                  }
          		?>
              </figure>
          </div>
          <div class="profile-detail">
          	<div class="profile_username" style="color:<?php echo @$options['wgt_insta_theme_textcolor'];?>">
          		<?php 
          		 if($user_data)
          		 {
          		 	echo $user_data['username']; 	
          		 }
          		?>
          	</div>
          	 <div class="profile-post-detail" style="color: <?php echo @$options['wgt_insta_theme_textcolor'];?>">
          	 	<?php 
          	 	if($user_data)
          		{
          			echo $user_data['media_count'].'posts';
          		}
          		?>
          	 </div>
          	 <?php
          	 if(@$user_data['biography'])
			    {
          	    echo '<div class="profile-post-detail" style="margin-top:10px;color: '.@$options['wgt_insta_theme_textcolor'].'>">';
				    echo '<strong>Bio : </strong>'.$user_data['biography'];
				    echo '</div>';
			    }
			    echo '<div class="profile-post-detail" style="margin-top:10px;color: '. @$options['wgt_insta_theme_textcolor'].'>">';
				 echo '<div class="followers_count"><strong>Followers : </strong>'. @$user_data['followers_count'] .'</div>';
				 echo '<div class="follows_count"><strong>Follows : </strong>'. @$user_data['follows_count'] .'</div>';
				 echo '</div>';
			    ?>
          </div>
		</div>


		<ul class="tabbing-list">
			<li>
				<a href="#" class="wc-active posts-tab">
					<span><i class="fas fa-border-all"></i></span> posts
				</a>
			</li>
			<li>
				<a href="#" class="video-tab">
					<span><i class="far fa-play-circle"></i></span>videos
				</a>
			</li>
		</ul>


		<div id="instagram-widget" class="instagram-widget">
		
		<div class="wc-all-posts">		
		<div class="row insta-post-list insta-gallery <?php echo $colunm_class;?>">
		<?php
		foreach ($media_data['data'] as $post) 
		{
		$link = (isset($post['permalink']))? $post['permalink'] : '';
		$caption = (isset($post['caption']))? $post['caption'] : '';
		?>
		<div class="colunm insta-item">
		<?php
		if($post['media_type']=='VIDEO')
		{ 
			$url_thumbnail = $post['thumbnail_url']; 
			echo '<a href="'.$link.'" target="_blank">';
			echo '<video width="320" height="240" controls><source src="'.$post['media_url'].'" type="video/mp4"><source src="'.$post['media_url'].'" type="video/ogg">Your browser does not support the video tag.
		   </video>';
		   echo "</a>";
		}
		else
		{ 
			$url_thumbnail = $post['media_url']; 
			echo '<a class="insta-link" href="'.$link.'" target="_blank" title="'.$caption.'"><img alt="'.$caption.'" src="'.$url_thumbnail.'" /><div class="insta-overlay"><i class="fab fa-instagram"></i></div></a>';
		}
		?>
		
		</div>
		<?php
		}
		?>

		</div>	

		<?php
		if($num_rows>$per_page)
		{
			echo '<div class="wgt-load-more"><button type="post" type="button" id="wgt-loadmore" class="wgt-load-more btn">Load More</button></div>';
		}
		?>
		</div>

		<div class="wc-video-posts" style="display: none;">
		<div class="row wc-video-posts insta-post-list insta-gallery <?php echo $colunm_class;?>">
		<?php
		if($media_videos)
		{
		foreach ($media_videos['data'] as $post) 
		{
		$link = (isset($post['permalink']))? $post['permalink'] : '';
		$caption = (isset($post['caption']))? $post['caption'] : '';
		?>
		<div class="colunm insta-item">
		<?php
		if($post['media_type']=='VIDEO')
		{ 
			$url_thumbnail = $post['thumbnail_url']; 
			echo '<a href="'.$link.'" target="_blank">';
			echo '<video width="320" height="240" controls><source src="'.$post['media_url'].'" type="video/mp4"><source src="'.$post['media_url'].'" type="video/ogg">Your browser does not support the video tag.
		   </video>';
		   echo "</a>";
		}
		?>
		
		</div>
		<?php
		}
		?>
		</div>

		<?php
		if($media_rows>$per_page)
		{
			echo '<div class="wgt-load-more"><button type="video" type="button" id="wgt-loadmore-video" class="wgt-load-more btn">Load More</button></div>';
		}
		}
		else
		{
			echo 'There is no Videos Available';
		}
		?>
	</div>
	</div>
	<?php
	}
	?>

	</div>

</div>
</div>

<div class="footer-info">
<div class="wgt footer-inner">
	
	<div class="cstm-container">
		<div class="wgt footer-inner-box">
	<?php
		
		if(isset($footer_info))
		{
			
			if($footer_options)
			{
				
				if(array_key_exists('wgt_insta_copyright_info',$footer_options))
				{
					echo '<span style="color:'.$options['wgt_insta_theme_textcolor'].'">'.$footer_options['wgt_insta_copyright_info'].'</span>';
				}
				
				echo '<p style="color:'.$options['wgt_insta_theme_textcolor'].'">'.$footer_info.'</p>';	 
				
				if(array_key_exists('wgt_insta_privacy_info',$footer_options))
				{
					echo '<span style="color:'.$options['wgt_insta_theme_textcolor'].'">'.$footer_options['wgt_insta_privacy_info'].'</span>';
				}
				
			}
			else
			{
				echo 'Footer Info Here';
			}

		} 
		  
	?>
		</div>
	</div>

</div>
</div>

<!-- Style Css -->
<style type="text/css">
	
	
</style>