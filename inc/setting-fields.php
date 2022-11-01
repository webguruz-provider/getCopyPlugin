<?php
/**
 * custom option and settings
*/
function load_plugin() {

    register_setting( 'wgt_insta_auth', 'wgt_insta_options' );
	
    add_settings_section(
        'wgt_insta_section_connect',
        __( 'Connect To Instagram', 'wgt_insta' ), 'wgt_insta_section_connect_instagram',
        'wgt_insta_auth'
    );
    
	add_settings_field(
        'wgt_insta_token', __( 'Access Token', 'wgt_insta' ),
        'wgt_insta_token_cb',
        'wgt_insta_auth',
        'wgt_insta_section_connect',
        array(
            'label_for'         => 'wgt_insta_token',
            'class'             => 'wgt_insta_row',
            'wgt_insta_custom_data' => 'custom',
        )
    );
	
	register_setting( 'wgt_insta_general', 'wgt_insta_general' );
	
	add_settings_section(
        'wgt_insta_section_general',
        __( 'General Setting', 'wgt_insta' ), 'wgt_insta_section_general_setting',
        'wgt_insta_general'
    );
    add_settings_field(
        'wgt_insta_per_page', __( 'Per Page', 'wgt_insta' ),
        'wgt_insta_per_page_cb',
        'wgt_insta_general',
        'wgt_insta_section_general',
        array(
            'label_for'         => 'wgt_insta_per_page',
            'class'             => 'wgt_insta_row',
            'wgt_insta_custom_data' => 'custom',
        )
    );
    add_settings_field(
        'wgt_insta_site_title', __( 'Site Title', 'wgt_insta' ),
        'wgt_insta_site_title_cb',
        'wgt_insta_general',
        'wgt_insta_section_general',
        array(
            'label_for'         => 'wgt_insta_site_title',
            'class'             => 'wgt_insta_row',
            'wgt_insta_custom_data' => 'custom',
        )
    );
	add_settings_field(
        'wgt_insta_per_row', __( 'Per Row', 'wgt_insta' ),
        'wgt_insta_per_row_cb',
        'wgt_insta_general',
        'wgt_insta_section_general',
        array(
            'label_for'         => 'wgt_insta_per_row',
            'class'             => 'wgt_insta_row',
            'wgt_insta_custom_data' => 'custom',
        )
    );
    
    add_settings_field(
        'wgt_insta_design', __( 'Theme Design', 'wgt_insta' ),
        'wgt_insta_design_cb',
        'wgt_insta_general',
        'wgt_insta_section_general',
        array(
            'label_for'         => 'wgt_insta_design',
            'class'             => 'wgt_insta_row',
            'wgt_insta_custom_data' => 'custom',
        )
    );
    
	add_settings_field(
        'wgt_insta_logo', __( 'Logo', 'wgt_insta' ),
        'wgt_insta_logo_cb',
        'wgt_insta_general',
        'wgt_insta_section_general',
        array(
            'label_for'         => 'wgt_insta_logo',
            'class'             => 'wgt_insta_row',
            'wgt_insta_custom_data' => 'custom',
        )
    );
	add_settings_field(
        'wgt_insta_theme_color', __( 'Theme Color', 'wgt_insta' ),
        'wgt_insta_theme_color_cb',
        'wgt_insta_general',
        'wgt_insta_section_general',
        array(
            'label_for'         => 'wgt_insta_theme_color',
            'class'             => 'wgt_insta_row',
            'wgt_insta_custom_data' => 'custom',
        )
    );
	add_settings_field(
        'wgt_insta_theme_textcolor', __( 'Theme Text Color', 'wgt_insta' ),
        'wgt_insta_theme_text_color_cb',
        'wgt_insta_general',
        'wgt_insta_section_general',
        array(
            'label_for'         => 'wgt_insta_theme_textcolor',
            'class'             => 'wgt_insta_row',
            'wgt_insta_custom_data' => 'custom',
        )
    );
	
	register_setting( 'wgt_insta_footer', 'wgt_insta_footer' );
	add_settings_section(
        'wgt_insta_section_general',
        __( 'Footer Setting', 'wgt_insta' ), 'wgt_insta_section_general_setting',
        'wgt_insta_footer'
    );
    add_settings_field(
        'wgt_insta_ft_info', __( 'Footer Info', 'wgt_insta' ),
        'wgt_insta_ft_info_cb',
        'wgt_insta_footer',
        'wgt_insta_section_general',
        array(
            'label_for'         => 'wgt_insta_ft_info',
            'class'             => 'wgt_insta_row',
            'wgt_insta_custom_data' => 'custom',
        )
    );	
    add_settings_field(
        'wgt_insta_copyright_info', __( 'Copyright Info', 'wgt_insta' ),
        'wgt_insta_copyright_info_cb',
        'wgt_insta_footer',
        'wgt_insta_section_general',
        array(
            'label_for'         => 'wgt_insta_copyright_info',
            'class'             => 'wgt_insta_row',
            'wgt_insta_custom_data' => 'custom',
        )
    );
    add_settings_field(
        'wgt_insta_privacy_info', __( 'Privacy Info', 'wgt_insta' ),
        'wgt_insta_privacy_info_cb',
        'wgt_insta_footer',
        'wgt_insta_section_general',
        array(
            'label_for'         => 'wgt_insta_privacy_info',
            'class'             => 'wgt_insta_row',
            'wgt_insta_custom_data' => 'custom',
        )
    );
	register_setting( 'wgt_insta_help', 'wgt_insta_help' );
	add_settings_section(
        'wgt_insta_section_help',
        __( 'Help/Support', 'wgt_insta' ), 'wgt_insta_section_help_setting',
        'wgt_insta_help'
    );
   
	
}
add_action( 'admin_init', 'load_plugin' );
 
/**
 * Wgt insta section callback function.
 *
 * @param array $args  The settings array, defining title, id, callback.
 */
function wgt_insta_section_connect_instagram( $args ) { 
	$url_to_connect = wgt_ig_getOAuthURL();
    ?>
    <p id="<?php echo esc_attr( $args['id'] ); ?>"><?php echo 'Before get started, Please  <a href="https://developers.facebook.com/docs/instagram-basic-display-api/getting-started" target="_blank"> follow this article</a> to get your access token.'; ?></p>
	<?php 
	$connected = get_option( 'wgt_instagram_connect' );
	if($connected=='connected'){
	$admin_url = admin_url('admin.php?page=wgt-getcopy&account=disconnect');
	echo '<div class="connect-button"><button type="button" title="Connect Account" class="button button-primary green pfa-confirm" target="_blank"><span class="dashicons dashicons-instagram"></span> Connected </button></div>';
	echo '<div class=""><a href="'. $admin_url .'" title="Disconnect Account" class="button button-primary pfa-confirm"><span class="dashicons dashicons-instagram"></span> Disconnect Account</a></div>'; 
	}else{
	echo '<div class=""><a href="'. $url_to_connect .'" title="Connect Account" class="button button-primary pfa-confirm" target="_blank"><span class="dashicons dashicons-instagram"></span> Connect Account</a></div>'; 
	} ?>
    <?php
}
function wgt_insta_section_general_setting( $args ) {
    ?>
    <p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'General Setting', 'wgt_insta' ); ?></p>
    <?php
}

function wgt_backup_section_general_setting( $args ) {
    ?>
    <p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'Download All Instagram Image', 'wgt_backup' ); ?></p>
    
    
    <?php
}

function wgt_insta_section_help_setting( $args ){
	?>
    <p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'For support', 'wgt_insta' ); ?> <a href="https://getcopy.io/contact/" target="_blank">click here</a></p>
    <?php
}
/**
 * Show fields callbakc function.
 * Note: you can add custom key value pairs to be used inside your callbacks.
 *
 * @param array $args
 */
function wgt_insta_token_cb( $args ) {
    // Get the value of the setting we've registered with register_setting()
    $options = get_option( 'wgt_insta_options' );
    ?>
    <input type="password" readonly id="<?php echo esc_attr( $args['label_for'] ); ?>" value="<?php echo @$options[ $args['label_for'] ] ; ?>" placeholder="Access Token" name="wgt_insta_options[<?php echo esc_attr( $args['label_for'] ); ?>]">
    <?php
}

function wgt_insta_per_page_cb( $args ) {
    // Get the value of the setting we've registered with register_setting()
    $options = get_option( 'wgt_insta_general' );
	$per_page = (isset($options[ $args['label_for'] ]))?$options[ $args['label_for'] ]:6;
    ?>
    <input type="number" min="1" id="<?php echo esc_attr( $args['label_for'] ); ?>" value="<?php echo $per_page; ?>" placeholder="Per Page" name="wgt_insta_general[<?php echo esc_attr( $args['label_for'] ); ?>]">
    <?php
}

function wgt_insta_site_title_cb( $args ) {
    // Get the value of the setting we've registered with register_setting()
    $options = get_option( 'wgt_insta_general' );
    if(!empty($options['wgt_insta_site_title']))
    {
        $value = $options['wgt_insta_site_title'];    
    }
    else
    {
        $value = '';    
    }
    
    ?>
    <input type="text" id="<?php echo esc_attr( $args['label_for'] ); ?>" value="<?php echo $value; ?>" placeholder="Site Title" name="wgt_insta_general[<?php echo esc_attr( $args['label_for'] ); ?>]">
    <?php
}

function wgt_insta_per_row_cb( $args ) {
    // Get the value of the setting we've registered with register_setting()
    $options = get_option( 'wgt_insta_general' );
	$colunms = array( '1' => 'One', '2' => 'Two', '3' => 'Three', '4' => 'Four' );
    ?>
	<select id="<?php echo esc_attr( $args['label_for'] ); ?>" name="wgt_insta_general[<?php echo esc_attr( $args['label_for'] ); ?>]" class="select form-field">
	<?php $current_col = (isset($options[ $args['label_for'] ]))?$options[ $args['label_for'] ]:3;
	foreach( $colunms as $key => $name ){
        $selected = ( $key == $current_col ) ? ' selected ' : '';
        echo "<option value='". $key ."' ". $selected .">". $name ."</option>";

    }
	?>
	</select>
    <?php
}

function wgt_insta_logo_cb( $args ) {
    // Get the value of the setting we've registered with register_setting()
    $options = get_option( 'wgt_insta_general' );
    ?>
	<input name="wgt_insta_general[<?php echo esc_attr( $args['label_for'] ); ?>]" id="wgt_logo" type="hidden" value="<?php echo @$options[ $args['label_for'] ] ; ?>" />
	<input type="button" class="button upload-image" value="Upload"	data-uploader_title="Select an Image" data-uploader_button_text="Select" />
	<input type="button" class="button remove-image" value="Remove" /><br />
	<div id="logo-preview">
	<?php	if(isset($options[ $args['label_for'] ])){
		$value = $options[ $args['label_for'] ];
		$img = wp_get_attachment_image_src( $value, 'medium' );
		if ( $img ) {
			echo '<img src="' . esc_url( $img[0] ) . '" width="150" height="125" /><br />';
		}
	}
		?>
	</div>
    <?php
}

function wgt_insta_theme_color_cb($args){
	$options = get_option( 'wgt_insta_general' );
	$value = (isset($options[ $args['label_for'] ]))? $options[ $args['label_for'] ]:'#000000';
	?>
	<input id="<?php echo esc_attr( $args['label_for'] ); ?>" name="wgt_insta_general[<?php echo esc_attr( $args['label_for'] ); ?>]" class="wgt-theme-color" type="text" value="<?php echo $value; ?>" data-default-color="#000000" />
	<?php
}

function wgt_insta_theme_text_color_cb($args){
	$options = get_option( 'wgt_insta_general' );
	$value = (isset($options[ $args['label_for'] ]))? $options[ $args['label_for'] ]:'#ffffff';
	?>
	<input id="<?php echo esc_attr( $args['label_for'] ); ?>" name="wgt_insta_general[<?php echo esc_attr( $args['label_for'] ); ?>]" class="wgt-theme-textcolor" type="text" value="<?php echo $value; ?>" data-default-color="#ffffff" />
	<?php
}

function wgt_insta_ft_info_cb( $args ) {
    // Get the value of the setting we've registered with register_setting()
    $ft_options = get_option( 'wgt_insta_footer' );
	$value = (isset($ft_options[ $args['label_for'] ]))? $ft_options[ $args['label_for'] ]:'';
    ?>
    <input type="text" id="<?php echo esc_attr( $args['label_for'] ); ?>" value="<?php echo $value; ?>" placeholder="Footer Info" name="wgt_insta_footer[<?php echo esc_attr( $args['label_for'] ); ?>]">
    <?php
}

function wgt_insta_copyright_info_cb( $args ) {
    // Get the value of the setting we've registered with register_setting()
    $ft_options = get_option( 'wgt_insta_footer' );
    $value = (isset($ft_options[ $args['label_for'] ]))? $ft_options[ $args['label_for'] ]:'';
    ?>
    <input type="text" id="<?php echo esc_attr( $args['label_for'] ); ?>" value="<?php echo $value; ?>" placeholder="Copyright Info" name="wgt_insta_footer[<?php echo esc_attr( $args['label_for'] ); ?>]">
    <?php
}

function wgt_insta_privacy_info_cb( $args ) {
    // Get the value of the setting we've registered with register_setting()
    $ft_options = get_option( 'wgt_insta_footer' );
    $value = (isset($ft_options[ $args['label_for'] ]))? $ft_options[ $args['label_for'] ]:'';
    ?>
    <input type="text" id="<?php echo esc_attr( $args['label_for'] ); ?>" value='<?php echo $value; ?>' placeholder="Privacy Info" name="wgt_insta_footer[<?php echo esc_attr( $args['label_for'] ); ?>]">
    <?php
}

function wgt_insta_design_cb($args){
	$options = get_option( 'wgt_insta_general' );
	$value = (isset($options[ $args['label_for'] ]))? $options[ $args['label_for'] ]:'grid';	
	?>
	<input type="radio" id="<?php echo esc_attr( $args['label_for'] ); ?>" name="wgt_insta_general[<?php echo esc_attr( $args['label_for'] ); ?>]" value="grid" <?php echo ($value== 'grid') ?  "checked" : "" ;  ?>/>
	<label for="grid"><img src="<?php echo PLUGINURL; ?>/images/grid-icon.png"></label><br>
	
	<input type="radio" disabled id="<?php echo esc_attr( $args['label_for'] ); ?>" readonly="readonly" name="sm1" value="" />
	<label for="sml"><img src="<?php echo PLUGINURL; ?>/images/masonary-icon.png"><span class="prolab">Pro</span></label><br>
	<?php
}

?>

