<?php
   /*
   Plugin Name: Beauty Contact Form
   Plugin URI: http://tagwebs.net/beauty-contact-form/
   Description: It is an simple and easy contact form, that doesn’t require any additional settings. All you need is just to activate the plugin and insert the shortcode [show_tagwebs_beauty_form] into the text. This is the plugin for contacting the admin of website that the data are stored in the wordpress data base,and data can be viewed from front end using shortcode.
   Version: 1.0
   Author: Dilip Kumar
   Author URI: http://tagwebs.net
   License: GPL2
   */

/* adding css class file */

	function tagdataweb_plugin_styles() {
		wp_register_style( 'tag_database_style_class', plugins_url('css/style.css', __FILE__) );
		wp_enqueue_style( 'tag_database_style_class' );
	}
	add_action( 'wp_enqueue_scripts', 'tagdataweb_plugin_styles' );
	
/* ending css class file */
	
	
/* Start Creating wpdb*/
		register_activation_hook( __FILE__, 'tag_create_plugin_tables' );
		function tag_create_plugin_tables()
		{
			// enter code to create tables
			global $wpdb;

			$table_name = $wpdb->prefix . 'tagwebs_data';

			$sql = "CREATE TABLE $table_name (
			id int(11) NOT NULL AUTO_INCREMENT,
			name varchar(255) DEFAULT NULL,
			email varchar(255) DEFAULT NULL,
			mobile varchar(255) DEFAULT NULL,
			message varchar(255) DEFAULT NULL,

			UNIQUE KEY id (id)
			);";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
		}
/*End Creating wpdb*/

/*Start shortcode for insert data in the wp database*/
function dataweb($atts){
	extract( shortcode_atts( array(
), $atts, 'show_tagwebs_beauty_form' ) );

if (isset($_POST['yourname']) && isset($_POST['email']) && isset($_POST['mobile']) && isset($_POST['message']) 
&& !empty($_POST['yourname']) && !empty($_POST['email']) && !empty($_POST['mobile']) && !empty($_POST['message'])) {

			/* Checking Email and name , mobile, message */
			$username = ereg_replace("[^A-Za-z]", "", $_POST['yourname']);
			$email = ereg("^[^@ ]+@[^@ ]+\.[^@ ]+$", $_POST['email']);
			$mobile = ereg_replace("[^0-9]", "", $_POST['mobile']);
			$message = ereg_replace("[^A-Za-z0-9]", "", $_POST['message']);
			

			if((!$username) || (!$email)  ||  (!$mobile)  ||  (!$message)  )  {
				$errorMsg = "Error! please enter ";
				if(!$username){
				$errorMsg .= "your name";
				} else if(!$email){
				$errorMsg .= "valid email adress"; 
				} else if(!$mobile){ 
				$errorMsg .= "contact no"; 
				} else if(!$message){ 
				$errorMsg .= "message"; 
				}
				
				
			
	  $list = ' <form method="post" class="tag-beauty">
				<span class="errormsg">* '. $errorMsg.' </span> <br>
				<label>Your Name </label><span class="error">*</span>
				<input type="text" placeholder="Enter your name" name="yourname">
				<label>Email </label><span class="error">*</span>
				<input type="text" placeholder="Enter your email" name="email">
				<label>Contact no </label><span class="error">*</span>
				<input type="text" placeholder="Enter your Contact no" name="mobile">
				<label>Message </label><span class="error">*</span>
				<textarea cols="25%" rows="8" placeholder="Leave your message" name="message"></textarea>
				<input class="tag-button" type="submit" value="Submit">
				</form>
				<br/>
			
';  } else{

					global $wpdb;
					$table = $wpdb->prefix . 'tagwebs_data';
					$data = array(
					'name' => $_POST['yourname'],
					'email'    => $_POST['email'],
					'mobile'    => $_POST['mobile'],
					'message'    => $_POST['message']
					);
					$format = array(
					'%s',
					'%s',
					'%s',
					'%s'
					);
					$success=$wpdb->insert( $table, $data, $format );
					if($success){
					echo '<div class="success_position"><b class="success_tag">Hi!, '.$username.' you have Successfully submitted the details.</b></div><br/>' ; 
	$list = '
				<form method="post" class="tag-beauty">
				<span class="error"> '. $errorMsg.' </span><br>
				<label>Your Name </label><span class="error">*</span>
				<input type="text" placeholder="Enter your name" name="yourname">
				<label>Email </label><span class="error">*</span>
				<input type="text" placeholder="Enter your email" name="email">
				<label>Contact no </label><span class="error">*</span>
				<input type="text" placeholder="Enter your contact no" name="mobile">
				<label>Message </label><span class="error">*</span>
				<textarea cols="25%" rows="8" placeholder="Leave Your Message" name="message"></textarea>
				<input type="submit" value="Submit" class="tag-button">
				</form>
				<br/>
			
'; 
					}}
} else{   	$list = ' 
				<form method="post" class="tag-beauty">
				<span class="error"> '. $errorMsg.' </span><br>
				<label>Your Name </label><span class="error">*</span>
				<input type="text" placeholder="Enter your name" name="yourname">
				<label>Email </label><span class="error">*</span>
				<input type="text" placeholder="Enter your email" name="email">
				<label>Contact no </label><span class="error">*</span>
				<input type="text" placeholder="Enter your Contact no" name="mobile">
				<label>Message </label><span class="error">*</span>
				<textarea cols="25%" rows="8" placeholder="Leave your message" name="message"></textarea>
				<input type="submit" value="Submit" class="tag-button">
				</form>
				<br/>
			
';        
}
return $list;
}
add_shortcode('show_tagwebs_beauty_form', 'dataweb');

/*End shortcode for insert data in the wp database*/

/*Start displaying shortcode*/
function show_tag_shortcode($atts){
	extract( shortcode_atts( array(
), $atts, 'display_tagwebs_submited_data' ) );
//contents

		$output='<div class="output_class">';
						$output .= ' <table class="tag_beauty_header">
						<tr>
						<th class="tag_beauty_header_inner" width="25%">Name</th>
						<th class="tag_beauty_header_inner" width="25%">Email</th>
						<th class="tag_beauty_header_inner" width="25%">Mobile</th>
						<th class="tag_beauty_header_inner" width="25%">Message</th>
						</tr>
						';
		global $wpdb;	 	 
		$table_search = $wpdb->prefix . 'tagwebs_data';
		$results = $wpdb->get_results("SELECT * FROM $table_search  ");	 
		
		foreach($results as $r) {	 
		$output_name=  $r->name; 
		$output_email=  $r->email; 
		$output_mobile=  $r->mobile; 
		$output_message=  $r->message; 
		$output .= '
						
						<tr>
						<td width="25%" scope="row"><div align="justify">'.$output_name.'</div></td>
						<td width="25%"><div align="justify">'.$output_email.'</div></td>
						<td width="25%"><div align="justify">'.$output_mobile.'</div></td>
						<td width="25%"><div align="justify">'.$output_message.'</div></td>
						</tr >
						
						

		';        } 
		$output .= '</table>
		'; 
		$output .='</div>';
return $output;
}

add_shortcode('display_tagwebs_submited_data', 'show_tag_shortcode');
/*End displaying shortcode*/

/*start admin panel options*/

// create custom settings menu
add_action('admin_menu', 'tagbeauty_create_menu');

function tagbeauty_create_menu() {

	//create new top-level menu
	add_menu_page('Tagwebs Beauty Contact form', 'B Contact form', 'administrator', __FILE__, 'tag_settings_page',plugins_url('/images/icon.png', __FILE__));

	//call register settings function
	add_action( 'admin_init', 'register_mysettings' );
}


function register_mysettings() {
	//register settings
	register_setting( 'tag-beauty-setting', 'tag_beautyz_option' );
}

function tag_settings_page() {
?>
<div class="wrap">
<h2>Tagwebs Beauty Form</h2>
<!-- starting admin panel style css -->
<style>
span {
float: left;
}
td {
border-top: 0px !important;
border-left: 1px solid #555555 !important;
border-right: 1px solid #555555 !important;
padding: 10px !important;
width: 25%;
}
th {
background: #555555 !important;
border-top: 0px !important;
border-left: 1px solid #555555 !important;
border-right: 1px solid #555555 !important;
padding: 10px !important;
width: 25%;
}
table {
border:none;
border-collapse: collapse;
}
table td {
border-left: 1px solid #555555;
border-right: 1px solid #555555;
}
table td:first-child {
border-left: none;
}
table td:last-child {
border-right: none;
}
tr:nth-child(even) {
background: #ffffff;
}
tr:nth-child(odd) {
background: #f7f7f7;
}
.tag_beauty_header {
height:30px !important;
width: 100%;
background-color: black;
float: left;
height: 30px;
margin: 0px !important;
}
.tag_beauty_header_inner {
color: white !important;
text-align: center;
font-size: 12px !important;
margin: 0px !important;
}
</style>
<!--Ending admin panel style css -->
<div class="wrap">
    <h2>Contact Information</h2>
	<h4><ul><li>>>> <i>To Display Beauty contact form use this Shortcode:</i> [show_tagwebs_beauty_form] </li>
	<li>>>> <i>To Display Stored Contact database in page / post use this Shortcode:</i> [display_tagwebs_submited_data]</li></ul></h4>
	<h3>Do you like this Plugin?</h3>
	<p>This plugin is developed, maintained, supported and contributed by <a href="http://www.tagwebs.net/" target="_blank">Tagwebs</a> with a lot of love. Any kind of contribution or suggestion would be highly appreciated. Thanks!</p><ul><li><a href="http://www.tagwebs.net" target="_blank">Visit the plugin homepage</a></li></ul>
<?php echo do_shortcode('[display_tagwebs_submited_data]'); ?> 
</div>
</div>
</div>
<?php } ?>
