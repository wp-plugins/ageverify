<?php 
// sidebar for settings page
?>

<div id="ageverify-sidebar" style="width: 23%; float: right; min-width: 150px;">
	
	<div class="box go-pro">
		<header>
			<?php _e( 'Ready to go Pro?', 'ageverify' ); ?>
		</header>
		<p><?php _e( 'Additional perks you\'ll get with AgeVerify Pro', 'ageverify' ); ?></p>
		<ul id="pro-perks">
			<li><?php _e( 'No Ads! Woo Hoo!', 'ageverify' ); ?></li>
			<li><?php _e( 'Longer Cookie Duration', 'ageverify' ); ?></li>
			<li><?php _e( 'Underage redirects to Google', 'ageverify' ); ?></li>
		</ul>
		<p><a href="https://ageverify.co/get-started/" target="_blank" class="button submit-button button-primary">
			<?php _e( 'Upgrade to Pro Now', 'ageverify' ); ?>
		</a></p>
	</div>

	<div class="box resources">
		<header>
			<?php _e( 'Resources', 'ageverify' ); ?>
		</header>
		<p style="text-align:center;"><a href="https://ageverify.co/contact-us/" target="_blank"class="button submit-button button-primary"><?php _e( 'Contact Us', 'ageverify' ); ?></a>
		</p>
	</div>

	<div class="box">
    <header>
			<?php _e( 'Feedback', 'ageverify' ); ?>
		</header>
		<p style="text-align:center;"><?php _e( 'We would love your feedback!  It only takes seconds and it means a lot.', 'ageverify' ); ?></p>
		<p style="text-align:center;"><a href="https://wordpress.org/support/view/plugin-reviews/ageverify" target="_blank" class="button submit-button button-primary">
			<?php _e( 'Rate This Plugin Now', 'ageverify' ); ?>
		</a></p>

	<div class="logo"><br />
		<a href="http://imbibedigital.co/" target="_blank">
			<img src="<?php echo plugins_url() . '/ageverify/includes/imbibe_logo.png'; ?>">
		</a>
	</div>

</div>


<?php

?>