jQuery(document).ready(function($)
{
	$('html').addClass('wgt_html');
	var paged=2,fetching=false;
	$('#wgt-loadmore').on('click',function()
	{ 
	
		var type = jQuery(this).attr( "type");
		if(fetching===false)
		{
			var data = {
				'action': 'wgt_loadmore_action',
				'type': type,
				'paged': paged
			};
			fetching=true;
			jQuery.post(wgt_ajax.ajax_url, data, function(response) 
			{
				paged++;
				fetching=false;
				const obj = JSON.parse(response);
				//console.log(obj.type);
				if(obj.nextpage == 'false')
				{ 
					$('#wgt-loadmore').hide(500);
				}

				if(obj.type = 'post')
				{
					$('.wc-all-posts .insta-post-list').append(obj.data);	
				}
				else
				{
					$('.wc-video-posts .insta-post-list').append(obj.data);	
				}
				
			});
		}
	});


	var video_paged=2,video_fetching=false;
	$('#wgt-loadmore-video').on('click',function()
	{ 
		var type = jQuery(this).attr( "type");
		if(fetching===false)
		{
			var data = {
				'action': 'wgt_loadmore_action',
				'type': type,
				'paged': video_paged
			};
			video_fetching=true;
			jQuery.post(wgt_ajax.ajax_url, data, function(response) 
			{
				video_paged++;
				video_fetching=false;
				const obj = JSON.parse(response);
				//console.log(obj.type);
				if(obj.nextpage == 'false')
				{ 
					$('#wgt-loadmore-video').hide(500);
				}

				if(obj.type = 'video')
				{
					$('.wc-video-posts .insta-post-list').append(obj.data);	
				}
				
			});
		}
	});

	jQuery( ".tabbing-list li a" ).click(function() 
	{
  		jQuery('.tabbing-list li a').removeClass('wc-active');
  		jQuery(this).addClass('wc-active');
	});

	jQuery( ".video-tab" ).click(function() 
	{
  		jQuery('.wc-video-posts').show();
  		jQuery('.wc-all-posts').hide();
	});

	jQuery( ".posts-tab" ).click(function() 
	{
  		jQuery('.wc-all-posts').show();
  		jQuery('.wc-video-posts').hide();
	});

});