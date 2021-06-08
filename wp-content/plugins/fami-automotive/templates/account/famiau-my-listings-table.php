<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $wpdb, $current_user;

if ( ! in_array( 'famiau_user', $current_user->roles ) ) {
	return;
}

$all_my_listings_sql = $wpdb->prepare(
	"SELECT * FROM " . FAMIAU_LISTINGS_TABLE . " WHERE listing_status <> 'deleted' AND author_login = %s",
	array( $current_user->user_login ) );
$rows                = $wpdb->get_results( $all_my_listings_sql );
$car_statuses        = array(
	'new'            => esc_html__( 'New', 'famiau' ),
	'used'           => esc_html__( 'Used', 'famiau' ),
	'certified-used' => esc_html__( 'Certified Used', 'famiau' ),
);

?>

<?php if ( $rows ) { ?>
	<?php
	$all_car_features           = famiau_car_features();
	$all_car_features_meta_keys = array();
	foreach ( $all_car_features as $car_feature ) {
		if ( isset( $car_feature['meta_key'] ) ) {
			$all_car_features_meta_keys[] = $car_feature['meta_key'];
		}
	}
	?>
    <table class="famiau-table famiau-listings-table">
        <thead>
        <tr>
            <th colspan="2"><?php esc_html_e( 'Listing Info', 'famiau' ); ?></th>
            <th><?php esc_html_e( 'Features', 'famiau' ); ?></th>
            <th><?php esc_html_e( 'Status', 'famiau' ); ?></th>
        </tr>
        </thead>
        <tbody>
		<?php
		foreach ( $rows as $row ) {
			$imgs_html         = '';
			$address_html      = '';
			$video_html        = '';
			$seller_notes_html = '';
			$listing_info_html = '';
			$prices_html       = '';
			$feaures_html      = '';
			$status_html       = '<span class="famiau-listing-status listing-status-' . esc_attr( $row->listing_status ) . '">' . esc_attr( $row->listing_status ) . '</span>';
			$actions_html      = '<div class="famiau-actions">';
			$actions_html      .= '<a data-listing_id="' . esc_attr( $row->id ) . '" href="#" class="famiau-delete-listing famiau-delete-my-listing-btn famiau-button button btn" >' . esc_html__( 'Delete', 'famiau' ) . '</a>';
			$actions_html      .= '<a data-listing_id="' . esc_attr( $row->id ) . '" href="#" class="famiau-to-sold-listing famiau-to-sold-btn famiau-button button btn" >' . esc_html__( 'Sold', 'famiau' ) . '</a>';
			$actions_html      .= '</div>';
			
			$attachment_ids = trim( $row->attachment_ids );
			if ( $attachment_ids != '' ) {
				$attachment_ids = explode( ',', $attachment_ids );
				if ( ! empty( $attachment_ids ) ) {
					foreach ( $attachment_ids as $attachment_id ) {
						$img       = famiau_resize_image( $attachment_id, null, 100, 100, true, true, false );
						$imgs_html .= '<img width="' . esc_attr( $img['width'] ) . '" height="' . esc_attr( $img['height'] ) . '" src="' . esc_url( $img['url'] ) . '" class="famiau-listing-thumb" />';
					}
				}
			}
			
			$address = trim( $row->_famiau_car_address );
			if ( $address != '' ) {
				$address_html .= '<p><span>' . esc_html__( 'Car Address', 'famiau' ) . '</span> ' . esc_html( $address ) . '</p>';
			}
			$latitude  = trim( $row->_famiau_car_latitude );
			$longitude = trim( $row->_famiau_car_longitude );
			if ( $latitude != '' && $longitude != '' ) {
				$address_html .= '<p>' .
				                 '<span>' . esc_html__( 'Latitude', 'famiau' ) . '</span> <span class="_famiau_car_latitude">' . esc_html( $latitude ) . '</span>' .
				                 '<span>' . esc_html__( 'Longitude', 'famiau' ) . '</span> <span class="_famiau_car_longitude">' . esc_html( $longitude ) . '</span>' .
				                 '</p>';
			}
			
			$title      = trim( $row->listing_title );
			$title_html = '';
			if ( $title == '' ) {
				$car_status = isset( $car_statuses[ $row->_famiau_car_status ] ) ? $car_statuses[ $row->_famiau_car_status ] : '';
				$title      = $car_status . ' ' . $row->_famiau_make . ' ' . $row->_famiau_model . ' ' . $row->_famiau_year;
			}
			$title_html = '<h4 class="listing-title">' . $title . '</h4>';
			
			// Price
			$price       = $row->_famiau_price;
			$price       = famiau_price( $price );
			$prices_html .= '<p class="famiau-price">' . $price . '</p>';
			
			$listing_info_html .= '<div class="listing-info-wrap">';
			$listing_info_html .= '<div data-item_key="_famiau_body" class="famiau-info-item"><span>' . esc_html__( 'Body', 'famiau' ) . '</span> <span class="info-value">' . esc_html( $row->_famiau_body ) . '</span></div>';
			$listing_info_html .= '<div data-item_key="_famiau_mileage" class="famiau-info-item"><span>' . esc_html__( 'Mileage', 'famiau' ) . '</span> <span class="info-value">' . esc_html( $row->_famiau_mileage ) . '</span></div>';
			$listing_info_html .= '<div data-item_key="_famiau_fuel_type" class="famiau-info-item"><span>' . esc_html__( 'Fuel type', 'famiau' ) . '</span> <span class="info-value">' . esc_html( $row->_famiau_fuel_type ) . '</span></div>';
			$listing_info_html .= '<div data-item_key="_famiau_engine" class="famiau-info-item"><span>' . esc_html__( 'Engine', 'famiau' ) . '</span> <span class="info-value">' . esc_html( $row->_famiau_engine ) . '</span></div>';
			$listing_info_html .= '<div data-item_key="_famiau_gearbox_type" class="famiau-info-item"><span>' . esc_html__( 'Transmission', 'famiau' ) . '</span> <span class="info-value">' . esc_html( $row->_famiau_gearbox_type ) . '</span></div>';
			$listing_info_html .= '<div data-item_key="_famiau_drive" class="famiau-info-item"><span>' . esc_html__( 'Drive', 'famiau' ) . '</span> <span class="info-value">' . esc_html( $row->_famiau_drive ) . '</span></div>';
			$listing_info_html .= '<div data-item_key="_famiau_exterior_color" class="famiau-info-item"><span>' . esc_html__( 'Exterior Color', 'famiau' ) . '</span> <span class="info-value">' . esc_html( $row->_famiau_exterior_color ) . '</span></div>';
			$listing_info_html .= '<div data-item_key="_famiau_interior_color" class="famiau-info-item"><span>' . esc_html__( 'Interior Color', 'famiau' ) . '</span> <span class="info-value">' . esc_html( $row->_famiau_interior_color ) . '</span></div>';
			$listing_info_html .= '<div data-item_key="_famiau_registered_date" class="famiau-info-item"><span>' . esc_html__( 'Registered', 'famiau' ) . '</span> <span class="info-value">' . esc_html( $row->_famiau_registered_date ) . '</span></div>';
			$listing_info_html .= '<div data-item_key="_famiau_vin" class="famiau-info-item"><span>' . esc_html__( 'VIN', 'famiau' ) . '</span> <span class="info-value">' . esc_html( $row->_famiau_vin ) . '</span></div>';
			$listing_info_html .= '<div>';
			
			if ( ! empty( $all_car_features_meta_keys ) ) {
				foreach ( $all_car_features_meta_keys as $car_feature_meta_key ) {
					if ( isset( $row->$car_feature_meta_key ) ) {
						$features = unserialize( $row->$car_feature_meta_key );
						if ( ! empty( $features ) ) {
							$feaures_html .= '<p>';
							foreach ( $features as $feature ) {
								$feaures_html .= '<span class="famiau-car-feature">' . esc_html( $feature ) . '</span>';
							}
							$feaures_html .= '</p>';
						}
					}
				}
			}
			
			// Video
			$video_url = trim( $row->_famiau_video_url );
			if ( $video_url != '' ) {
				$video_html .= '<p>' . sprintf( __( 'Click %s for video', 'famiau' ), '<a href="' . esc_url( $video_url ) . '" target="_blank">' . esc_html__( 'here', 'famiau' ) . '</a>' ) . '</p>';
			} else {
				$video_html .= '<p>' . esc_html__( 'No video', 'famiau' ) . '</p>';
			}
			
			$seller_notes = trim( $row->_famiau_seller_notes_suggestions );
			if ( $seller_notes != '' ) {
				$seller_notes_html .= '<p class="famiau-seller-note">' . esc_html( $seller_notes ) . '</p>';
			}
			
			?>
            <tr data-listing_id="<?php echo esc_attr( $row->id ); ?>">
                <td class="famiau-imgs-td"><?php echo $title_html . $prices_html . $imgs_html . $address_html . $video_html . $seller_notes_html . $actions_html; ?></td>
                <td class="famiau-info-td"><?php echo $listing_info_html; ?></td>
                <td class="famiau-features-td"><?php echo $feaures_html; ?></td>
                <td class="famiau-status-td"><?php echo $status_html; ?></td>
            </tr>
			<?php
		}
		?>
        </tbody>
    </table>
<?php } else { ?>
    <p class="famiau-message famiau-info-msg"><?php esc_html_e( 'You do not have any listings, click on add new button to add listing', 'famiau' ); ?></p>
<?php } ?>
