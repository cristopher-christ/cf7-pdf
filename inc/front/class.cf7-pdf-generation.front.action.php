<?php
/**
 * Cf7_Pdf_Generation_Front_Action Class
 *
 * Handles the Frontend Actions.
 *
 * @package WordPress
 * @subpackage
 * @since 2.4
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

if ( !class_exists( 'Cf7_Pdf_Generation_Front_Action' ) ){

	/**
	* The Cf7_Pdf_Generation_Front_Action Class
	*/

	class Cf7_Pdf_Generation_Front_Action {

		function __construct()  {
			add_action( 'wp_enqueue_scripts',  array( $this, 'enqueue_styles' ));
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ));
			add_action( 'wpcf7_before_send_mail', array( $this, 'wpcf7_pdf_attachment_script' ));
		}

		function wpcf7_pdf_create_attachment($filename)
		{
			// Check the type of file. We'll use this as the 'post_mime_type'.
			$filetype = wp_check_filetype(basename($filename), null);
			$filetype['type'] = 'application/pdf';

			// Get the path to the upload directory.
			$wp_upload_dir = wp_upload_dir();

			$attachFileName = $wp_upload_dir['path'] . '/' . basename($filename);
			copy($filename, $attachFileName);
			// Prepare an array of post data for the attachment.
			$attachment = array(
				'guid'           => $attachFileName,
				'post_mime_type' => $filetype['type'],
				'post_title'     => preg_replace('/\.[^.]+$/', '', basename($filename)),
				'post_content'   => '',
				'post_status'    => 'inherit'
			);

			// Insert the attachment.
			$attach_id = wp_insert_attachment($attachment, $attachFileName);
			$attach_url = wp_get_attachment_url( $attach_id );

			// Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
			require_once(ABSPATH . 'wp-admin/includes/image.php');

			// Generate the metadata for the attachment, and update the database record.
			$attach_data = wp_generate_attachment_metadata($attach_id, $attachFileName);

			wp_update_attachment_metadata($attach_id, $attach_data);
			return $attach_url;
		}

		/**
		* Function for generate PDF file
		*/
		function wpcf7_pdf_attachment_script( $wpcf7 ){

			$wpcf = WPCF7_ContactForm::get_current();

		    $submission = WPCF7_Submission :: get_instance();
			$posted_data = $submission->get_posted_data();
			$uploaded_files = $submission->uploaded_files();
			$contact_id = $wpcf->id;
		    $setting_data = get_post_meta( $contact_id, 'cf7_pdf', true );
		    $attach_image = $setting_data['cf7_opt_attach_pdf_image'] ? $setting_data['cf7_opt_attach_pdf_image'] : "";
		    $cf7_pdf_link_is_enable = $setting_data['cf7_pdf_link_is_enable'] ? $setting_data['cf7_pdf_link_is_enable'] : "";
			$attdata = array();
		    $date = date_i18n( get_option('date_format') );
			$time = date_i18n( get_option('time_format') );

			$setting_data['cf7_opt_is_attach_enable'];

			if( isset($setting_data['cf7_opt_is_enable']) && $setting_data['cf7_opt_is_enable'] == 'true'  )
			{

				if( isset($setting_data['cf7_opt_is_attach_enable']) && $setting_data['cf7_opt_is_attach_enable'] == 'true'  )
	 			{
					if($attach_image)
	 				{
	 					$pdf_file_path1 = WP_CONTENT_DIR .'/uploads/wpcf7_uploads/'.$attach_image;

	 					$pdf_file_path = WP_CF7_PDF_DIR .'attachments/'.$attach_image;
	 					$pdf_url_path = WP_CF7_PDF_URL.'attachments/'.$attach_image;

	 					$temp_name = sanitize_text_field(rand());
						copy($pdf_file_path, $pdf_file_path1);
						$attdataurl = $this->wpcf7_pdf_create_attachment($pdf_url_path);
	 					$cookie_name = "pdf_path";
						$cookie_value = $attdataurl;
						setcookie( $cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day
						setcookie( 'enable_pdf_link', $cf7_pdf_link_is_enable, time() + (86400 * 30), "/"); // 86400 = 1 day
						$mail = $wpcf7->prop('mail');
						$attachments_main = array();

						$attachments_main[] = $pdf_file_path;
						foreach ( (array) $uploaded_files as $name => $path ) {
							if (! empty( $path ) ) {
								$attachments_main[] = $path;
							}
						}
						$others_file_data = implode( PHP_EOL, $attachments_main);

						$submission->add_uploaded_file('pdf', $pdf_file_path1);

						if( $mail['attachments']!='' ){
							$mail['attachments'] = $mail['attachments'] . PHP_EOL . $others_file_data;
						} else {
							$mail['attachments'] = $others_file_data;
						}

						$wpcf7->set_properties(array(
							"mail" => $mail
						));

						$mail_2 = $wpcf7->prop('mail_2');
						if( $mail_2['attachments']!='' ){
							$mail_2['attachments'] = $mail_2['attachments'] . PHP_EOL . $others_file_data;
						} else {
							$mail_2['attachments'] = $others_file_data;
						}
						$wpcf7->set_properties(array(
							"mail_2" => $mail_2
						));
	 				}
	 			}
	 			else
	 			{

	 				/*
	 				* Code of generate PDF
	 				*/
	 				if (!class_exists('\Mpdf\Mpdf')) {

	 				require  WP_CF7_PDF_DIR . 'inc/lib/mpdf/vendor/autoload.php';

						$cf7_opt_margin_header = $setting_data['cf7_opt_margin_header'];
						$cf7_opt_margin_footer = $setting_data['cf7_opt_margin_footer'];
						$cf7_opt_margin_top = $setting_data['cf7_opt_margin_top'];
						$cf7_opt_margin_bottom = $setting_data['cf7_opt_margin_bottom'];
						if(!$cf7_opt_margin_header){$cf7_opt_margin_header = '10';}
						if(!$cf7_opt_margin_footer){$cf7_opt_margin_footer = '10';}
						if(!$cf7_opt_margin_top){$cf7_opt_margin_top = '40';}
						if(!$cf7_opt_margin_bottom){$cf7_opt_margin_bottom = '40';}
	 					$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4', 'margin_header' => $cf7_opt_margin_header, 'margin_top' => $cf7_opt_margin_top,'margin_footer' => $cf7_opt_margin_footer, 'margin_bottom' => $cf7_opt_margin_bottom,'default_font' => 'FreeSans']);
	 				}

					$mpdf->autoScriptToLang = true;
					$mpdf->baseScript = 1;
					$mpdf->autoVietnamese = true;
					$mpdf->autoArabic = true;
					$mpdf->autoLangToFont = true;
					$mpdf->SetTitle(get_bloginfo( 'name' ));
					$mpdf->SetCreator(get_bloginfo('name'));
					$mpdf->ignore_invalid_utf8 = true;
					$msg_body = $setting_data['cf7_pdf_msg_body'] ? $setting_data['cf7_pdf_msg_body'] : '';
					$cf7_opt_header_pdf_image = $setting_data['cf7_opt_header_pdf_image'] ? $setting_data['cf7_opt_header_pdf_image'] : '';
					$cf7_opt_max_width_logo = $setting_data['cf7_opt_max_width_logo'] ? $setting_data['cf7_opt_max_width_logo'] : '160px';
					$cf7_opt_min_width_logo = $setting_data['cf7_opt_min_width_logo'] ? $setting_data['cf7_opt_min_width_logo'] : '85px';
					$cf7_opt_header_text = $setting_data['cf7_opt_header_text'] ? $setting_data['cf7_opt_header_text'] : '';
					$cf7_opt_footer_text = $setting_data['cf7_opt_footer_text'] ? $setting_data['cf7_opt_footer_text'] : '';
					
					if( isset($setting_data['cf7_pdf_filename_prefix']) ) {
						$setting_data['cf7_pdf_filename_prefix'] = trim($setting_data['cf7_pdf_filename_prefix']);
						$cf7_pdf_filename_prefix = $setting_data['cf7_pdf_filename_prefix'] ? $setting_data['cf7_pdf_filename_prefix'] : 'CF7';
					} else {
						$cf7_pdf_filename_prefix = 'CF7';
					} 

					foreach ($posted_data as $key => $value) {
						if ( strstr( $msg_body, $key ) ) {

							if(is_array($value)) {
								$value = implode(',', $value);
							}
							else {
								$value = $value;
							} if (strpos($msg_body, '[date]') !== false) {
							    $msg_body = str_replace('[date]',$date,$msg_body);
							} if (strpos($msg_body, '[time]') !== false) {
							    $msg_body = str_replace('[time]',$time,$msg_body);
							} if (strpos($msg_body, '[remote_ip]') !== false) {
							    $msg_body = str_replace('[remote_ip]',$submission->get_meta('remote_ip'),$msg_body);
					 		}
							$msg_body = str_replace('['.$key.']',$value,$msg_body);
							if($uploaded_files){
								foreach ( (array) $uploaded_files as $name => $path ) {
									if (! empty( $path ) ) {
										$file_name = basename($path);
										$msg_body = str_replace('['.$name.']',$file_name,$msg_body);
									}
								}
							}
						}
					}

					$html = $msg_body;
					$html = nl2br($html);

					/*
					* Require PDF HTML file.
					*/
					require  WP_CF7_PDF_DIR . 'inc/templates/cf7-pdf-generation.public.html.php';

					$mpdf->SetHTMLHeader( $headerContent );
					$mpdf->SetHTMLFooter( $footerContent );

					$mpdf->WriteHTML($html);

					if( $cf7_pdf_filename_prefix!='' ) {
						$pdf_file_name = $cf7_pdf_filename_prefix.'-'.time().'.pdf';
					} else {
						$pdf_file_name = 'cf7-'.$contact_id.'-'.time().'.pdf';
					} 

					$path_dir_cf7 = '';
					foreach ( (array) $uploaded_files as $name => $path ) {

						if (! empty( $path ) ) {
							$xmlFile = pathinfo($path);
							$path_dir_cf7 =  $xmlFile['dirname'];
						}
					}

					$pdf_file_path = WP_CF7_PDF_DIR .'attachments/'.$pdf_file_name;
					$pdf_file_path1 = $path_dir_cf7.'/'.$pdf_file_name;

					$pdf_url_path = WP_CF7_PDF_URL.'attachments/'.$pdf_file_name;

					if (file_exists($_SERVER['DOCUMENT_ROOT'] . $pdf_file_path1)) {

						$mpdf->Output( $pdf_file_path , 'F');
						$mpdf->Output( $pdf_file_path1 , 'F');

					}
					else{
						$mpdf->Output( $pdf_file_path , 'F');
					}

					//till this file upload in attachment folder
					$attdataurl = $this->wpcf7_pdf_create_attachment($pdf_url_path);
					$cookie_name = "pdf_path";
					$cookie_value = $attdataurl;
					setcookie( $cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day
					setcookie( 'enable_pdf_link', $cf7_pdf_link_is_enable, time() + (86400 * 30), "/"); // 86400 = 1 day

					$attachments_main = array();
					if($uploaded_files){
						$attachments_main[] = $pdf_file_path1;
						foreach ( (array) $uploaded_files as $name => $path ) {
							$xmlFile = pathinfo($path);
							$path_dir_cf7 =  $xmlFile['dirname'];
							if (! empty( $path ) ) {
								$attachments_main[] = $path;
							}
						}
					}
					else{
						$attachments_main[] = $pdf_file_path;
					}

					$mail = $wpcf7->prop('mail');

					$submission->add_uploaded_file('pdf', $pdf_file_path);
					$others_file_data = implode( PHP_EOL, $attachments_main);

					if( $mail['attachments']!='' ){
						$mail['attachments'] = $mail['attachments'] . PHP_EOL . $others_file_data;
					} else {
						$mail['attachments'] = $others_file_data;
					}

					$wpcf7->set_properties(array(
						"mail" => $mail
					));

					$mail_2 = $wpcf7->prop('mail_2');
					if( $mail_2['attachments']!='' ){
						$mail_2['attachments'] = $mail_2['attachments'] . PHP_EOL . $others_file_data;
					} else {
						$mail_2['attachments'] = $others_file_data;
					}

					$wpcf7->set_properties(array(
						"mail_2" => $mail_2
					));

				}
			}

			return $wpcf;

		}
		/*
		   ###     ######  ######## ####  #######  ##    ##  ######
		  ## ##   ##    ##    ##     ##  ##     ## ###   ## ##    ##
		 ##   ##  ##          ##     ##  ##     ## ####  ## ##
		##     ## ##          ##     ##  ##     ## ## ## ##  ######
		######### ##          ##     ##  ##     ## ##  ####       ##
		##     ## ##    ##    ##     ##  ##     ## ##   ### ##    ##
		##     ##  ######     ##    ####  #######  ##    ##  ######
		*/

		/**
		* WP Enqueue style for public CSS
		*/
		public function enqueue_styles() {
			wp_enqueue_style( 'cf7-pdf-generation-public-css', WP_CF7_PDF_URL . 'assets/css/cf7-pdf-generation-public-min.css', array(), 1.1, 'all' );
		}

		/**
		* WP Enqueue scripts for public JS
		*/
		public function enqueue_scripts() {
			wp_enqueue_script( 'cf7-pdf-generation-public-js', WP_CF7_PDF_URL . 'assets/js/cf7-pdf-generation-public-min.js', array( 'jquery' ), 1.1, false );
		}

		/*
		######## ##     ## ##    ##  ######  ######## ####  #######  ##    ##  ######
		##       ##     ## ###   ## ##    ##    ##     ##  ##     ## ###   ## ##    ##
		##       ##     ## ####  ## ##          ##     ##  ##     ## ####  ## ##
		######   ##     ## ## ## ## ##          ##     ##  ##     ## ## ## ##  ######
		##       ##     ## ##  #### ##          ##     ##  ##     ## ##  ####       ##
		##       ##     ## ##   ### ##    ##    ##     ##  ##     ## ##   ### ##    ##
		##        #######  ##    ##  ######     ##    ####  #######  ##    ##  ######
		*/

	}

	/**
	* Run plugins loaded
	*/
	add_action( 'plugins_loaded' , function() {
		Cf7_Pdf_Generation()->front->action = new Cf7_Pdf_Generation_Front_Action;
	} );
}