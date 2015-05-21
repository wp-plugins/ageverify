<?php
/* Options Page */

// --------------------------------------------------------------------------------------
// CALLBACK FUNCTION FOR: register_uninstall_hook(__FILE__, 'ageverify_delete_plugin_options')
// --------------------------------------------------------------------------------------

// Delete options table entries ONLY when plugin deactivated AND deleted
function ageverify_delete_plugin_options() {
	delete_option('ageverify_options');
}

// ------------------------------------------------------------------------------
// CALLBACK FUNCTION FOR: register_activation_hook(__FILE__, 'ageverify_add_defaults')
// ------------------------------------------------------------------------------

// Define default option settings
function ageverify_add_defaults() {
	$tmp = get_option('ageverify_options');
    if(!is_array($tmp)) {
		delete_option('ageverify_options'); // so we don't have to reset all the 'off' checkboxes too! (dont think this is needed but leave for now)
		$arr = array(	
			"ageverify_on" => 0,
			"ageverify_template" => "opaque",
			"ageverify_language" => "en",
			"ageverify_age" => "18",
			"ageverify_method" => "mdy"

		);
		update_option('ageverify_options', $arr);
	}
}

// ------------------------------------------------------------------------------
// CALLBACK FUNCTION FOR: add_action('admin_init', 'ageverify_init' )
// ------------------------------------------------------------------------------
// THIS FUNCTION RUNS WHEN THE 'admin_init' HOOK FIRES, AND REGISTERS YOUR PLUGIN
// SETTING WITH THE WORDPRESS SETTINGS API. YOU WON'T BE ABLE TO USE THE SETTINGS
// API UNTIL YOU DO.
// ------------------------------------------------------------------------------

// Init plugin options to white list our options
function ageverify_init(){
	register_setting( 'ageverify_plugin_options', 'ageverify_options', 'ageverify_validate_options' );
}

// ------------------------------------------------------------------------------
// CALLBACK FUNCTION FOR: add_action('admin_menu', 'ageverify_add_options_page');
// ------------------------------------------------------------------------------

// Add menu page
function ageverify_add_options_page() {
	add_menu_page( 
		'AgeVerify', 
		'AgeVerify', 
		'manage_options', 
		'/age-verify/options.php', 
		'ageverify_render_options_page', 
		plugin_dir_url( __FILE__ ) . '/includes/AVicon20.png', 
		85.420
	);
}


// ------------------------------------------------------------------------------
// CALLBACK FUNCTION SPECIFIED IN: add_options_page()
// ------------------------------------------------------------------------------
add_action( 'admin_init', 'ageverify_settings_init' );

function ageverify_settings_init(  ) { 

	register_setting( 'pluginPage', 'ageverify_settings' );

	add_settings_section(
		'ageverify_pluginPage_section', 
		__( 'Settings', 'ageverify' ), 
		'ageverify_settings_section_callback', 
		'pluginPage'
	);

	add_settings_field( 
		'ageverify_on', 
		__( 'Enable or Disable AgeVerify', 'ageverify' ), 
		'ageverify_on_render', 
		'pluginPage', 
		'ageverify_pluginPage_section' 
	);

	add_settings_field( 
		'ageverify_template', 
		__( 'Step 1 - Select a Template', 'ageverify' ), 
		'ageverify_template_render', 
		'pluginPage', 
		'ageverify_pluginPage_section' 
	);

	add_settings_field( 
		'ageverify_language', 
		__( 'Step 2 - Select Language', 'ageverify' ), 
		'ageverify_language_render', 
		'pluginPage', 
		'ageverify_pluginPage_section' 
	);

	add_settings_field( 
		'ageverify_age', 
		__( 'Step 3 - Select Age', 'ageverify' ), 
		'ageverify_age_render', 
		'pluginPage', 
		'ageverify_pluginPage_section' 
	);

	add_settings_field( 
		'ageverify_method', 
		__( 'Step 4 - Select Method', 'ageverify' ), 
		'ageverify_method_render', 
		'pluginPage', 
		'ageverify_pluginPage_section' 
	);

}

function ageverify_on_render() {

	$options = get_option( 'ageverify_settings' );
	?>
	<input 	type='checkbox' 
			id='on'
			name='ageverify_settings[ageverify_on]' 
			<?php checked( $options['ageverify_on'], 1 ); ?> 
			value='1'
	>
	<label for='on' id='toggle-on'><?php _e( 'On', 'ageverify' ); ?></label>
	<?php

}

function ageverify_template_render(  ) { 

	$options = get_option( 'ageverify_settings' );
	require_once( plugin_dir_path( __FILE__ ) . 'includes/templates.php' ); ?>
	<div id="ageverify-gallery">
		<div id="galleryButtons">
			<?php _e( 'Sort by: ', 'ageverify' ); ?><br />
			<a onClick="allGallery();" class="galleryButtons" id="btnAll"><?php _e( 'Display All', 'ageverify' ); ?></a>
        	<a onClick="popularGallery();" class="galleryButtons" id="btnPopular"><?php _e( 'Most Popular', 'ageverify' ); ?></a>
        	<a onClick="beerGallery();" class="galleryButtons" id="btnBeer"><?php _e( 'Beer', 'ageverify' ); ?></a>
        	<a onClick="spiritsGallery();" class="galleryButtons" id="btnSpirits"><?php _e( 'Spirits', 'ageverify' ); ?></a>
        	<a onClick="wineGallery();" class="galleryButtons" id="btnWine"><?php _e( 'Wine', 'ageverify' ); ?></a>
        	<a onClick="tobaccoGallery();" class="galleryButtons" id="btnTobacco"><?php _e( 'Tobacco', 'ageverify' ); ?></a>
        	<a onClick="marijuanaGallery();" class="galleryButtons" id="btnMarijuana"><?php _e( 'Marijuana', 'ageverify' ); ?></a>
        	<a onClick="adultGallery();" class="galleryButtons" id="btnAdult"><?php _e( 'Adult', 'ageverify' ); ?></a>
        </div>
		<?php foreach( $templates as $template ) { ?>
			<div class="galleryItem <?php echo $template['tags']; ?>">
				<input 	type='radio' 
						name='ageverify_settings[ageverify_template]' 
						value='<?php echo $template['name']; ?>' 
						id='<?php echo $template['name']; ?>' 
						<?php checked( $options['ageverify_template'], $template['name'], true) ; ?>
				> 
				<label for='<?php echo $template['name']; ?>'>
					<img src='<?php echo plugins_url() . '/ageverify/includes/' . $template['image']; ?>' alt='<?php echo $template['title']; ?>'>
				</label>
			</div>
		<?php }	?>
	</div>
	
<?php }


function ageverify_language_render(  ) { 

	$options = get_option( 'ageverify_settings' );
	$languages = array(
			array(
				"language" => __( 'English', 'ageverify' ),
				"code" => "en"
				),
			array(
				"language" => __( 'French', 'ageverify' ),
				"code" => "fr"
				),
			array(
				"language" => __( 'German', 'ageverify' ),
				"code" => "de"
				),
			array(
				"language" => __( 'Spanish', 'ageverify' ),
				"code" => "sp"
				),
			array(
				"language" => __( 'Czech', 'ageverify' ),
				"code" => "cz"
				),
		);
	foreach( $languages as $language ) { ?>
		<input 	type='radio' 
				name='ageverify_settings[ageverify_language]' 
				class='ageverify-language'
				value='<?php echo $language['code']; ?>'
				id='<?php echo $language['code']; ?>'
				<?php checked( $options['ageverify_language'], $language['code'], true) ; ?>
		> 
		<label for='<?php echo $language['code']; ?>'>
			<?php echo $language['language']; ?>
		</label>
	<?php }
}


function ageverify_age_render(  ) { 

	$options = get_option( 'ageverify_settings' );
	$ages = array( 
		'18',
		'19',
		'21'
		);
	foreach( $ages as $age ) { ?>
		<input 	type='radio' 
				name='ageverify_settings[ageverify_age]' 
				value='<?php echo $age; ?>'
				id='<?php echo $age; ?>'
				<?php checked( $options['ageverify_age'], $age, true) ; ?>
		> 
		<label for='<?php echo $age; ?>'>
			<?php echo $age; ?>
		</label>
	<?php }

}


function ageverify_method_render(  ) { 

	$options = get_option( 'ageverify_settings' );
	$methods = array(
		array( 
			"name" => __( 'Date of Birth Input: (Month / Day / Year)', 'ageverify' ),
			"code" => "mdy",
			),
		array(
			"name" => __( 'Over Age / Under Age (Prompt)', 'ageverify' ),
			"code" => "prompt"
			)
		);
	foreach( $methods as $method ) { ?>
		<input 	type='radio' 
				name='ageverify_settings[ageverify_method]' 
				value='<?php echo $method['code']; ?>'
				id='<?php echo $method['code']; ?>'
				<?php checked( $options['ageverify_method'], $method['code'], true) ; ?>
		> 
		<label for='<?php echo $method['code']; ?>'>
			<?php echo $method['name']; ?>
		</label>
	<?php }

}


function ageverify_settings_section_callback(  ) { 

}


// Render the Plugin options form
function ageverify_render_options_page() {
	?>
	<div class="wrap">
		<h2><?php _e('AgeVerify Configuration', 'ageverify'); ?></h2>
		<?php settings_errors(); ?>
		<div id="ageverify" style="width: 75%; min-width: 350px; float: left;">
			<!-- Beginning of the Plugin Options Form -->
			<form method="post" action="options.php">
				<?php
				settings_fields( 'pluginPage' );
				do_settings_sections( 'pluginPage' );
				submit_button();
				?>
			</form>

		</div><!-- #main -->
		<?php include( plugin_dir_path( __FILE__ ) . '/includes/aside.php' ); ?>
	</div>

	<?php	
}



// Sanitize and validate input. Accepts an array, return a sanitized array.
function ageverify_validate_options($input) {
	$input['sample_field'] =  wp_filter_nohtml_kses($input['sample_field']); 
	// $input['txt_one'] =  wp_filter_nohtml_kses($input['txt_one']); // Sanitize textbox input (strip html tags, and escape characters)
	// $input['textarea_one'] =  wp_filter_nohtml_kses($input['textarea_one']); // Sanitize textarea input (strip html tags, and escape characters)
	return $input;
}



?>