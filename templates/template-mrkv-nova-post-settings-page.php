<?php 
	# Exit if accessed directly
	if ( ! defined( 'ABSPATH' ) ) exit; 

	# Include template
	include MRKV_NOVA_PLUGIN_PATH . 'templates/template-mrkv-nova-post-admin-header.php'; 
?>
<div class="mrkv-nova__section-tab">
	<div class="admin_mrkv_ua_shipping__tabs_main mrkv_block_rounded">
		<h2>
			<?php echo __('Settings Nova Post', 'mrkv-nova-post'); ?>
				<img src="<?php echo MRKV_NOVA_PLUGIN_URL . '/assets/img/logo-settings.svg' ?>" alt="<?php echo __('Settings Nova Post', 'mrkv-nova-post'); ?>" title="<?php echo __('Settings Nova Post', 'mrkv-nova-post'); ?>">
			</h2>
	</div>
</div>
<?php
	$api_works = 'empty';

	$apikey = get_option('mrkv_nova_api_token');
	$apiurl_type = get_option('mrkv_nova_api_server');
	$apiurl = '';
	
	if($apiurl_type == 'production')
	{
		$apiurl = 'https://api.novapost.com/v.1.0/';
	}
	else
	{
		$apiurl = 'https://api-stage.novapost.pl/v.1.0/';
	}

	if($apikey && $apiurl)
	{
		$country = 'PL';
		$novapost_term_suggestion = 'War';

		# Send request
		$token_json = wp_remote_get($apiurl . 'clients/authorization?apiKey=' . $apikey, [
		    'headers' => [
		      
		    ],
		  'timeout' => 30
		]);

		$token = json_decode($token_json['body'], true);

		# Send request
		$cities = wp_remote_get($apiurl . 'divisions?countryCodes[]=' . $country . '&limit=100&textSearch=' . $novapost_term_suggestion, [
		    'headers' => [
		      'Authorization' => $token['jwt']
		    ],
		  'timeout' => 30
		]);

		$city_body = json_decode($cities['body'], true);

		if(isset($city_body['items']) && !empty($city_body['items']))
		{
			$api_works = 'success';
		}
		else{
			$api_works = 'error';
		}
	}
	
?>
<div class="mrkv-nova__section-tab">
	<?php settings_errors(); ?>
</div>
<div class="mrkv-nova__section">
	<div class="mrkv-nova__section__inner mrkv-col-7">
		<div class="mrkv-nova__inner__body">
			<form class="mrkv-nova__inner__body__form" method="post" action="options.php">
                <?php settings_fields('mrkv-nova-settings-group'); ?>

                <div class="mrkv-nova__form__row">
                	<div class="mrkv-nova__form__col">
                		<div class="mrkv-nova__form__line">
                			<h2><img src="<?php echo MRKV_NOVA_PLUGIN_URL . '/assets/img/settings-icon.svg'; ?>" alt="Basic settings" title="Basic settings"><?php echo __('Basic settings', 'mrkv-nova-post'); ?></h2>
                			<p><?php echo esc_html__('Integrate the Nova Post API into your website to send orders to customers in a convenient way. Deliver from Europe to Ukraine or from Ukraine to Europe with the help of the largest postal operator network in Ukraine, Nova Post.','mrkv-nova-post'); ?></p>
                			<hr>
                			<h4><?php echo esc_html__('Api Server','mrkv-nova-post'); ?></h4>
                			<div class="mrkv-nova__form__line__content mrkv-nova__form__line__content__radio">
                				<?php 
                					$servers = array(
                						'production' => __('Production', 'mrkv-nova-post'),
                						'sandbox' => __('Sandbox', 'mrkv-nova-post')
                					);

                					$mrkv_nova_api_server = get_option('mrkv_nova_api_server');

                					if(!$mrkv_nova_api_server)
                					{
                						$mrkv_nova_api_server = '';
                					}

                					foreach($servers as $server => $name)
                					{
                						$checked = '';

                						if($mrkv_nova_api_server == $server)
                						{
                							$checked = 'checked';
                						}
                						?>
                							<div class="mrkv-nova__form__line__radio_val">
                								<input id="mrkv_nova_api_server-<?php echo esc_html($server); ?>" type="radio" name="mrkv_nova_api_server" value="<?php echo esc_html($server); ?>" <?php echo esc_html($checked); ?>>
                								<label for="mrkv_nova_api_server-<?php echo esc_html($server); ?>"><?php echo esc_html($name); ?></label>
                							</div>
                						<?php
                					}
                				?>
                			</div>
                			<h4><?php echo esc_html__('Api Token','mrkv-nova-post'); ?>
                				<?php
                					if($api_works == 'success')
            						{
            							?>
            								<div class="admin_ua_ship_morkva__notification mrkv-notification-green"><?php echo __('API key correct','mrkv-nova-post'); ?></div>
            							<?php
            						}
            						elseif($api_works == 'error')
            						{
            							?>
            								<div class="admin_ua_ship_morkva__notification mrkv-notification-red"><?php echo __('API key incorrect','mrkv-nova-post'); ?></div>
            							<?php
            						}
                				?>
                			</h4>
                			<div class="mrkv-nova__form__line__content">
                				<div class="mrkv-nova__form__line__text_val">
                					
                					<?php
                						$mrkv_nova_api_token = get_option('mrkv_nova_api_token');

                						if(!$mrkv_nova_api_token)
                						{
                							$mrkv_nova_api_token = '';
                						}
                					?>
                					<input type="text" name="mrkv_nova_api_token" value="<?php echo esc_html($mrkv_nova_api_token); ?>">
                					<p><?php echo esc_html__('During the registration process, you will receive an API access key. Make sure to store this information in a secure place.', 'mrkv-nova-post'); ?></p>
                				</div>
                			</div>
                		</div>
                		<?php echo esc_html(submit_button(esc_html__('Save Settings', 'mrkv-nova-post'))); ?>
                	</div>
                	<!--<div class="mrkv-nova__form__col">
                		<h3><?php echo esc_html__('Parcels Default Settings','mrkv-nova-post'); ?></h3>
            			<hr>
            			<h4><?php echo esc_html__('Cargo Category','mrkv-nova-post'); ?></h4>
            			<div class="mrkv-nova__form__line__content">
            				<?php 
            					$mrkv_nova_parcels_data = get_option('mrkv_nova_parcels_data');

            					$cargo_category = array(
        							'parcel' => 'Parcel'
            					);

            					$mrkv_nova_parcels_data_cargo = '';

            					if(is_array($mrkv_nova_parcels_data) && isset($mrkv_nova_parcels_data['cargo']))
            					{
            						$mrkv_nova_parcels_data_cargo = $mrkv_nova_parcels_data['cargo'];
            					}

            					foreach($cargo_category as $category => $name)
            					{
            						$checked = '';

            						if($mrkv_nova_parcels_data_cargo == $category)
            						{
            							$checked = 'checked';
            						}
            						?>
            							<div class="mrkv-nova__form__line__radio_val">
            								<input id="mrkv_nova_parcels_data_cargo-<?php echo esc_html($category); ?>" type="radio" name="mrkv_nova_parcels_data[cargo]" value="<?php echo esc_html($category); ?>" <?php echo esc_html($checked); ?>>
            								<label for="mrkv_nova_parcels_data_cargo-<?php echo esc_html($category); ?>"><?php echo esc_html($name); ?></label>
            							</div>
            						<?php
            					}
            				?>
            			</div>
            			<h4><?php echo esc_html__('Shipping dimensions','mrkv-nova-post'); ?></h4>
            			<div class="mrkv-nova__form__line__content">
            				<div class="mrkv-nova__form__content__sizes">
            					<div class="mrkv-nova__form__sizes__col">
	            					<span><?php echo esc_html__('Width','mrkv-nova-post') . ' ' . esc_html__('(mm)','mrkv-nova-post'); ?></span>
	            					<?php
	            						$mrkv_nova_parcels_data_width = '';
	            						if(is_array($mrkv_nova_parcels_data) && isset($mrkv_nova_parcels_data['width']))
		            					{
		            						$mrkv_nova_parcels_data_width = $mrkv_nova_parcels_data['width'];
		            					}
	            					?>
	            					<input name="mrkv_nova_parcels_data[width]" type="number" value="<?php echo esc_html($mrkv_nova_parcels_data_width); ?>">
	            				</div>
	            				<div class="mrkv-nova__form__sizes__col">
	            					<span><?php echo esc_html__('Length','mrkv-nova-post') . ' ' . esc_html__('(mm)','mrkv-nova-post'); ?></span>
	            					<?php
	            						$mrkv_nova_parcels_data_length = '';
	            						if(is_array($mrkv_nova_parcels_data) && isset($mrkv_nova_parcels_data['length']))
		            					{
		            						$mrkv_nova_parcels_data_length = $mrkv_nova_parcels_data['length'];
		            					}
	            					?>
	            					<input name="mrkv_nova_parcels_data[length]" type="number" value="<?php echo esc_html($mrkv_nova_parcels_data_length); ?>">
	            				</div>
	            				<div class="mrkv-nova__form__sizes__col">
	            					<span><?php echo esc_html__('Height','mrkv-nova-post') . ' ' . esc_html__('(mm)','mrkv-nova-post'); ?></span>
	            					<?php
	            						$mrkv_nova_parcels_data_height = '';
	            						if(is_array($mrkv_nova_parcels_data) && isset($mrkv_nova_parcels_data['height']))
		            					{
		            						$mrkv_nova_parcels_data_height = $mrkv_nova_parcels_data['height'];
		            					}
	            					?>
	            					<input name="mrkv_nova_parcels_data[height]" type="number" value="<?php echo esc_html($mrkv_nova_parcels_data_height); ?>">
	            				</div>
            				</div>
            				<div class="mrkv-nova__form__content__weight">
            					<div class="mrkv-nova__form__weight__col">
	            					<span><?php echo esc_html__('Weight','mrkv-nova-post') . ' ' . esc_html__('(g)','mrkv-nova-post'); ?></span>
	            					<?php
	            						$mrkv_nova_parcels_data_weight = '';
	            						if(is_array($mrkv_nova_parcels_data) && isset($mrkv_nova_parcels_data['weight']))
		            					{
		            						$mrkv_nova_parcels_data_weight = $mrkv_nova_parcels_data['weight'];
		            					}
	            					?>
	            					<input name="mrkv_nova_parcels_data[weight]" type="number" value="<?php echo esc_html($mrkv_nova_parcels_data_weight); ?>">
	            				</div>
	            				<div class="mrkv-nova__form__weight__col">
	            					<span><?php echo esc_html__('Row number','mrkv-nova-post') . ' ' . esc_html__('(rows)','mrkv-nova-post'); ?></span>
	            					<?php
	            						$mrkv_nova_parcels_data_row_number = '';
	            						if(is_array($mrkv_nova_parcels_data) && isset($mrkv_nova_parcels_data['row_number']))
		            					{
		            						$mrkv_nova_parcels_data_row_number = $mrkv_nova_parcels_data['row_number'];
		            					}
	            					?>
	            					<input name="mrkv_nova_parcels_data[row_number]" type="number" value="<?php echo esc_html($mrkv_nova_parcels_data_row_number); ?>">
	            				</div>
            				</div>
        				</div>
                	</div>-->
                </div>
            </form>
		</div>
	</div>
	<div class="mrkv-nova__section__inner mrkv-col-3">
		<div class="admin_mrkv_ua_shipping__plugin__support">
		<h2><img src="<?php echo MRKV_NOVA_PLUGIN_URL . '/assets/img/question-icon.svg'; ?>" alt="Statistics of shipments" title="Statistics of shipments"><?php echo __('Support', 'mrkv-nova-post'); ?></h2>
		<p><?php echo __('Having trouble creating a shipment? Feel free to contact our support team.', 'mrkv-nova-post'); ?></p>
		<a href="mailto:support@morkva.co.ua" class="button button-primary admin_mrkv_ua_shipping__btn" target="_blank"><?php echo __('E-mail', 'mrkv-nova-post'); ?></a>
		<a href="https://t.me/morkva_support_bot" class="admin_mrkv_ua_shipping__btn admin_mrkv_ua_shipping__btn_icon button button-primary" target="_blank"><?php echo __('Telegram', 'mrkv-nova-post'); ?><img src="<?php echo MRKV_NOVA_PLUGIN_URL . '/assets/img/telegram-logo.svg'; ?>"></a>
	</div>
	</div>
</div>