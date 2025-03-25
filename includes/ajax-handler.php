<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Handle AJAX request securely
 */
function my_custom_ajax_handler() {
    // Verify nonce for security
    if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'my_ajax_nonce')) {
        wp_send_json_error(array('message' => 'Invalid security token.'));
        exit;
    }

    // Check user capability
    if (!current_user_can('manage_options')) {
        wp_send_json_error(array('message' => 'Unauthorized access.'));
        exit;
    }
	if ( function_exists( 'gravity_form' ) ) {
				// Security check
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( array( 'message' => 'You do not have permission to create a form.' ) );
			}
			// Check if the custom form already exists by looking for its title
			$form_title = 'List your horse';
		
			$forms = GFAPI::get_forms( false ); // Retrieve all forms

			$form_found = false;
			foreach ( $forms as $form ) {
				if ( $form['title'] === $form_title ) {
					$form_found = true;
					break;
				}
			}

			if ( $form_found ) {
				update_option( 'custom_gravity_form_created', true );
				wp_send_json_success( array( 'message' => 'Form already exists.' ) );
				return;
			}

		   // Define the custom template with the requested fields
			$form_data = array(
				'title'   => $form_title,
				'fields'  => array(
					array(
						'label'    => 'Your Name',
						'type'     => 'text',
						'inputName' => 'your_name',
						'isRequired' => true,
						'placeholder' => 'Full Name',
					),
					 // Address Field (Fixed)
					 array(
						'label'       => 'Address*',
						'type'        => 'address', // Use 'address' field type
						'inputName'   => 'address',
						'isRequired'    => true,
						'addressType' => 'international', // Options: 'us' or 'international'
						'visibility'  => 'visible',
						'inputs'      => array(
							array( 'id' => '2.1', 'label' => 'Street Address' ),  
							array( 'id' => '2.2', 'label' => 'Street Address Line 2' ),  
							array( 'id' => '2.3', 'label' => 'City' ),  
							array( 'id' => '2.4', 'label' => 'State / Province / Region' ),  
							array( 'id' => '2.5', 'label' => 'Zip / Postal Code' ),  
							array( 'id' => '2.6', 'label' => 'Country' ),  
						),
					),

					// Email
					array(
						'label'    => 'Email',
						'type'     => 'email',
						'inputName' => 'email',
						'isRequired' => true,
						'placeholder' => 'Email Address',
					),
					// Phone
					array(
						'label'    => 'Phone',
						'type'     => 'text',
						'inputName' => 'phone',
						'isRequired' => true,
						'placeholder' => 'Phone Number',
					),
					// Do you already have Seller's Profile?
					array(
						'label'    => "Do you already have Seller's Profile?",
						'type'     => 'radio',
						'inputName' => 'seller_profile',
						'choices'  => array(
							array( 'text' => 'Yes', 'value' => 'yes' ),
							array( 'text' => 'No', 'value' => 'no' ),
						),
						'isRequired' => false,
					),
					// Horse Name
					array(
						'label'    => 'Horse Name',
						'type'     => 'text',
						'inputName' => 'horse_name',
						'isRequired' => true,
						'placeholder' => 'Horse Name',
					),
					// Description
					array(
						'label'    => 'Description',
						'type'     => 'textarea',
						'inputName' => 'description',
						'isRequired' => false,
						'placeholder' => 'Horse Description',
					),
					// Horse Image File Upload (Allow multiple files)
					array(
						'label'            => 'Horse Images',          
						'type'             => 'fileupload',           
						'inputName'        => 'horse_image',  // Field name for reference
						'isRequired'       => true,
						'multipleFiles'    => true,  // Enable multi-file upload
						'fileExtensions'   => 'jpg,jpeg,png,gif',  // Allowed file types
						'cssClass'         => 'multi-file-upload', // Custom CSS class
						'description'      => 'Please upload your horse images. You can select multiple files at once.',
					),
					// Breed
					array(
						'label'    => 'Breed',
						'type'     => 'text',
						'inputName' => 'breed',
						'isRequired' => true,
						'placeholder' => 'Breed',
					),
					// Location
					array(
						'label'    => 'Location',
						'type'     => 'text',
						'inputName' => 'location',
						'isRequired' => true,
						'placeholder' => 'Location',
					),
					// Is Horse Registered?
					array(
						'label'    => 'Is Horse Registered?',
						'type'     => 'radio',
						'inputName' => 'is_registered',
						'choices'  => array(
							array( 'text' => 'Yes', 'value' => 'yes' ),
							array( 'text' => 'No', 'value' => 'no' ),
						),
						'isRequired' => true,
					),
					// Horse Color
					array(
						'label'    => 'Horse Color',
						'type'     => 'text',
						'inputName' => 'horse_color',
						'isRequired' => false,
						'placeholder' => 'Horse Color',
					),
					// Sex (Radio)
					array(
						'label'    => 'Sex',
						'type'     => 'radio',
						'inputName' => 'sex',
						'choices'  => array(
							array( 'text' => 'Mare', 'value' => 'mare' ),
							array( 'text' => 'Gelding', 'value' => 'gelding' ),
							array( 'text' => 'Stallion', 'value' => 'stallion' ),
							array( 'text' => 'John', 'value' => 'john' ),
							array( 'text' => 'Jack', 'value' => 'jack' ),
							array( 'text' => 'Molly', 'value' => 'molly' ),
						),
						'isRequired' => true,
					),
					// Foal Date
					array(
						'label'    => 'Foal Date',   // Main label for the date field
						'type'     => 'date',        // Date field type
						'inputName' => 'foal_date',  // Field input name
						'isRequired' => true,        // Make the field required
						'placeholder' => 'Foal Date',// Placeholder text
						'dateType' => 'datefield',// Placeholder text
						'inputs' => array(          // Separate inputs for Month, Day, Year
							array(
								'id' => '14.1',       // Unique ID for the Month input
								'label' => 'Month',   // Label for the Month input
								'name' => 'month',    // Name for the Month input (if you need it for validation)
								'defaultValue' => '', // Default value (empty or default month value)
							),
							array(
								'id' => '14.2',       // Unique ID for the Day input
								'label' => 'Day',     // Label for the Day input
								'name' => 'day',      // Name for the Day input
								'defaultValue' => '', // Default value (empty or default day value)
							),
							array(
								'id' => '14.3',       // Unique ID for the Year input
								'label' => 'Year',    // Label for the Year input
								'name' => 'year',     // Name for the Year input
								'defaultValue' => '', // Default value (empty or default year value)
							),
						),
					),
					// Height
					array(
						'label'    => 'Height',
						'type'     => 'text',
						'inputName' => 'height',
						'isRequired' => true,
						'placeholder' => 'Height',
					),
					// Markings
					array(
						'label'    => 'Markings',
						'type'     => 'text',
						'inputName' => 'markings',
						'isRequired' => true,
						'placeholder' => 'Star, Strip, Snip, Four Stockings',
						'cssClass'   => 'custom-markings-class',
					),
					// Skills
				   array(
						'label'       => 'Skills',
						'type'        => 'checkbox',
						'inputName'   => 'skills',
						'choices'     => array(
							array( 'text' => 'All Around', 'value' => 'all_around' ),
							array( 'text' => 'Athletic', 'value' => 'athletic' ),
							array( 'text' => 'Barrel', 'value' => 'barrel' ),
							array( 'text' => 'Barrel Racing', 'value' => 'barrel_racing' ),
							array( 'text' => 'Beginner Safe', 'value' => 'beginner_safe' ),
							array( 'text' => 'Blue Eyed', 'value' => 'blue_eyed' ),
							array( 'text' => 'Breeders Trust', 'value' => 'breeders_trust' ),
							array( 'text' => 'Breeding', 'value' => 'breeding' ),
							array( 'text' => 'Calf Roping', 'value' => 'calf_roping' ),
							array( 'text' => 'Cowboy Mounted Shooting', 'value' => 'cowboy_mounted_shooting' ),
							array( 'text' => 'Champion', 'value' => 'champion' ),
							array( 'text' => 'Classical', 'value' => 'classical' ),
							array( 'text' => 'Companion', 'value' => 'companion' ),
							array( 'text' => 'Crossbred', 'value' => 'crossbred' ),
							array( 'text' => 'Color Producer', 'value' => 'color_producer' ),
							array( 'text' => 'Cutting', 'value' => 'cutting' ),
							array( 'text' => 'Cutting Prospect', 'value' => 'cutting_prospect' ),
							array( 'text' => 'Dappled', 'value' => 'dappled' ),
							array( 'text' => 'Double Registered', 'value' => 'double-registered' ),
							array( 'text' => 'Dressage', 'value' => 'dressage' ),
							array( 'text' => 'Drill Team', 'value' => 'drill_team' ),
							array( 'text' => 'Driving', 'value' => 'driving' ),
							array( 'text' => 'Endurance', 'value' => 'endurance' ),
							array( 'text' => 'Equitation', 'value' => 'equitation' ),
							array( 'text' => 'Eventing', 'value' => 'eventing' ),
							array( 'text' => 'Experienced', 'value' => 'experienced' ),
							array( 'text' => 'Field Trial', 'value' => 'field_trial' ),
							array( 'text' => 'Futurity Eligible', 'value' => 'futurity_eligible' ),
							array( 'text' => 'Gaited', 'value' => 'gaited' ),
							array( 'text' => 'Gymkhana', 'value' => 'gymkhana' ),
							array( 'text' => 'Halter', 'value' => 'halter' ),
							array( 'text' => 'Harness', 'value' => 'harness' ),
							array( 'text' => 'Heading', 'value' => 'heading' ),
							array( 'text' => 'Heel', 'value' => 'heel' ),
							array( 'text' => 'Hunting', 'value' => 'hunting' ),
							array( 'text' => 'Homozygous', 'value' => 'homozygous' ),
							array( 'text' => 'Horsemanship', 'value' => 'horsemanship' ),
							array( 'text' => 'Hunter Jumper', 'value' => 'hunter_jumper' ),
							array( 'text' => 'Hunt Seat Equitation', 'value' => 'hunt_seat_equitation' ),
							array( 'text' => 'Hunter Under Saddle', 'value' => 'hunter_under_saddle' ),
							array( 'text' => 'Husband Safe', 'value' => 'husband_safe' ),
							array( 'text' => 'Incentive Fund', 'value' => 'incentive_fund' ),
							array( 'text' => 'Jumper', 'value' => 'jumper' ),
							array( 'text' => 'Jumping', 'value' => 'jumping' ),
							array( 'text' => 'Kid Friendly', 'value' => 'kid_friendly' ),
							array( 'text' => 'Lesson', 'value' => 'lesson' ),
							array( 'text' => 'Money Winner', 'value' => 'money_winner' ),
							array( 'text' => 'Mounted Patrol', 'value' => 'mounted_patrol' ),
							array( 'text' => 'Natural Horsemanship Training', 'value' => 'natural_horsemanship_training' ),
							array( 'text' => 'Pack', 'value' => 'pack' ),
							array( 'text' => 'Parade', 'value' => 'parade' ),
							array( 'text' => 'Penning', 'value' => 'penning' ),
							array( 'text' => 'Performance', 'value' => 'performance' ),
							array( 'text' => 'Playday', 'value' => 'playday' ),
							array( 'text' => 'Pleasure Driving', 'value' => 'pleasure_driving' ),
							array( 'text' => 'Pole Bending', 'value' => 'pole_bending' ),
							array( 'text' => 'Pony Club', 'value' => 'pony_club' ),
							array( 'text' => 'Racing', 'value' => 'racing' ),
							array( 'text' => 'Racehorse', 'value' => 'racehorse' ),
							array( 'text' => 'Ranch Pleasure', 'value' => 'ranch_pleasure' ),
							array( 'text' => 'Ranch Versatility', 'value' => 'ranch_versatility' ),
							array( 'text' => 'Ranch Work', 'value' => 'ranch_work' ),
							array( 'text' => 'Reining', 'value' => 'reining' ),
							array( 'text' => 'Ridden English', 'value' => 'ridden_english' ),
							array( 'text' => 'Ridden Western', 'value' => 'ridden_western' ),
							array( 'text' => 'Rodeo', 'value' => 'rodeo' ),
							array( 'text' => 'Rodeo Pickup', 'value' => 'rodeo_pickup' ),
							array( 'text' => 'Roping', 'value' => 'roping' ),
							array( 'text' => 'Show', 'value' => 'show' ),
							array( 'text' => 'Show Experience', 'value' => 'show_experience' ),
							array( 'text' => 'Show Hack', 'value' => 'show_hack' ),
							array( 'text' => 'Show Jumping', 'value' => 'show_jumping' ),
							array( 'text' => 'Show Winner', 'value' => 'show_winner' ),
							array( 'text' => 'Showmanship', 'value' => 'showmanship' ),
							array( 'text' => 'Sporthorse', 'value' => 'sporthorse' ),
							array( 'text' => 'Sport', 'value' => 'sport' ),
							array( 'text' => 'Steer Roping', 'value' => 'steer_roping' ),
							array( 'text' => 'Steer Wrestling', 'value' => 'steer_wrestling' ),
							array( 'text' => 'Team Driving', 'value' => 'team_driving' ),
							array( 'text' => 'Team Penning', 'value' => 'team_penning' ),
							array( 'text' => 'Team Roping', 'value' => 'team_roping' ),
							array( 'text' => 'Therapy', 'value' => 'therapy' ),
							array( 'text' => 'Trail Riding', 'value' => 'trail_riding' ),
							array( 'text' => 'Trained', 'value' => 'trained' ),
							array( 'text' => 'Trick', 'value' => 'trick' ),
							array( 'text' => 'Trail Class Competition', 'value' => 'trail_class_competition' ),
							array( 'text' => 'Triple Registered', 'value' => 'triple_registered' ),
							array( 'text' => 'Western Dressage', 'value' => 'western_dressage' ),
							array( 'text' => 'Western Pleasure', 'value' => 'western_pleasure' ),
							array( 'text' => 'Western Riding', 'value' => 'western_riding' ),
							array( 'text' => 'Working Cattle', 'value' => 'working_cattle' ),
							array( 'text' => 'Youth', 'value' => 'youth' ),
						),
						'placeholder' => 'Select skills',
						'cssClass'   => 'ap-skills-class',
					),
					//Video URL
					array(
						'label'    => 'Video URL',
						'type'     => 'text',
						'inputName' => 'video_url',
						'isRequired' => true,
						'placeholder' => 'ex: https://youtu.be/3KqTQ26tR6w, https://youtu.be/hz21qKvFsks',
						'cssClass'   => 'custom-markings-class',
					),
					// Copy of Registration Papers
					array(
						'label'       => 'Copy of Registration Papers',
						'type'        => 'fileupload',
						'inputName'   => 'registration_papers',
						'isRequired'    => false,
						'placeholder' => 'Upload the registration papers',
					),
					// Copy of Coggins
					array(
						'label'       => 'Copy of Coggins',
						'type'        => 'fileupload',
						'inputName'   => 'coggins',
						'isRequired'    => false,
						'placeholder' => 'Upload the Coggins test document',
					),
					// Copy of Vet Inspection
					array(
						'label'       => 'Copy of Vet Inspection from a licensed vet',
						'type'        => 'fileupload',
						'inputName'   => 'vet_inspection',
						'isRequired'    => false,
						'placeholder' => 'Upload the vet inspection document',
					),
					// Auction Reserve Price
					array(
						'label'       => 'Auction Reserve Price ($)',
						'type'        => 'number',
						'inputName'   => 'reserve_price',
						'isRequired'    => true,
						'placeholder' => 'Auction Reserve Price',
					),
					// Listing Length (Radio)
					array(
						'label'       => 'Listing Length',
						'type'        => 'radio',
						'inputName'   => 'listing_length',
						'isRequired'    => true,
						'choices'     => array(
							array( 'text' => '2 Weeks', 'value' => '2_weeks' ),
							array( 'text' => '3 Weeks', 'value' => '3_weeks' ),
						),
						'placeholder' => 'Select the listing length', // Optional placeholder text
					),
					// Sponsored Post Option (Radio Buttons)
					array(
						'label'       => 'Would you like to run an optional sponsored post on social media for maximum exposure? We automatically run a free post for you, but if you’d like to boost it further, select the amount you’d like to spend for a sponsored post.',
						'type'        => 'radio',
						'inputName'   => 'sponsored_post',
						'isRequired'    => false, // Optional field
						'choices'     => array(
							array( 'text' => '$50 Towards Sponsored Post', 'value' => '50' ),
							array( 'text' => '$100 Towards Sponsored Post', 'value' => '100' ),
							array( 'text' => '$100+ Custom Sponsored Post', 'value' => 'custom' ),
						),
						'placeholder' => 'Select an amount for a sponsored post', // Optional placeholder
					),
					// Seller Agreement (Checkbox)
					array(
						'label'       => 'Seller Agreement',
						'type'        => 'checkbox',
						'inputName'   => 'seller_agreement',
						//'isRequired'    => true, // Make it required
						'choices'     => array(
							array( 'text' => 'Seller agrees to provide all shipping documents to purchaser after the closing of auction.', 'value' => 'yes' ),
						),
						'placeholder' => 'Please confirm the seller agreement', // Optional placeholder
					),
					// Signature Field (Custom Signature Field)
					array(
						'label'      => 'Signature', // Label for Signature field
						'type'       => 'signature', // Custom field type for signature
						'inputName'  => 'signature', // Field Name
						'placeholder'=> 'Please sign here', // Optional placeholder
						'size'       => array( 'width' => 400, 'height' => 150 ), // Optional: specify size of signature box
					),
				),
				'button' => array(
					'type'    => 'submit',
					'text'    => 'Submit',
					'class'   => 'gform_button',
					'location' => 'bottom'
				),
			);

			// Ensure that the 'button' key is set before accessing it to prevent "undefined array key" warning
			$button_location = isset( $form_data['button'] ) && isset( $form_data['button']['location'] ) ? $form_data['button']['location'] : 'bottom';
			$form_data['button']['location'] = $button_location;

			// Create the form using the Gravity Forms API
			$form = GFAPI::add_form( $form_data );

			if ( is_wp_error( $form ) ) {
				wp_send_json_error( array( 'message' => 'Error creating custom Gravity Form template: ' . $form->get_error_message() ) );
			} else {
				update_option( 'custom_gravity_form_created', true );
				wp_send_json_success( array( 'message' => 'Form created successfully!' ) );
			}
	}
		exit;
}

// Register AJAX actions
add_action('wp_ajax_my_custom_ajax_action', 'my_custom_ajax_handler');
