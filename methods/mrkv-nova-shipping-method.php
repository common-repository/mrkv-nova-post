<?php
# Check user access
defined( 'ABSPATH' ) || exit;

# Check if class exist
if (!class_exists('MRKV_NOVA_SHIPPING_METHOD'))
{
	/**
	 * Add new delivery method
	 * */
	class MRKV_NOVA_SHIPPING_METHOD extends WC_Shipping_Method 
	{
		/**
		 * Constructor new shipping method
		 * */
		public function __construct($instance_id = 0) 
		{
			$this->instance_id = absint( $instance_id );
            parent::__construct( $instance_id );

		    # These title description are display on the configuration page
		    $this->id = MRKV_NOVA_DELIVERY_SHIPPING_METHOD_ID;
		    $this->method_title = __('Morkva Nova Post', 'mrkv-nova-post');
		    $this->method_description = __('Morkva Nova Post Shipping', 'mrkv-nova-post');
		    $this->rate = 0.00;

		    # Add support zones
		    $this->supports = array(
                'shipping-zones',
                'instance-settings',
                'instance-settings-modal',
            );
		    
		    # Run the initial method
		    $this->init();

		    # Set title
		    $this->title = $this->get_option( 'title' );

		    # Enabled method
		    $this->enabled = true;
		    $this->enabled = $this->get_option( 'enabled' );
		}

		/**
		 * Load the settings API
		 * */
		public function init() 
		{
	    	# Load the settings API
	    	$this->init_settings();

	    	# Add the form fields
	    	$this->init_form_fields();

	    	# Save settings in admin if you have any defined
	    	add_action('woocommerce_update_options_shipping_' . $this->id, array($this, 'process_admin_options'));
	    }

	    /**
	     * Initialize all shipping fields
	     * */
	    public function init_form_fields() 
	    {
	    	 $this->instance_form_fields = array(
                'title' => array(
                    'title' => $this->method_title,
                    'type' => 'text',
                    'description' => $this->method_description,
                    'default' => $this->method_title
                ),
                'enable_fix_cost' => array(
                    'title' => __('Enable Fixed Price for Delivery', 'mrkv-nova-post'),
                    'label' => __('If checked, fixed price will be set for delivery', 'mrkv-nova-post'),
                    'type' => 'checkbox',
                    'default' => 'no',
                    'description' => '',
                ),
                'fix_cost_total' => array(
                    'title' => __('Fixed shipping price', 'mrkv-nova-post'),
                    'type' => 'text',
                    'placeholder' => __('Enter the amount in numbers', 'mrkv-nova-post'),
                    'description' => '',
                    'default' => 0.00
                ),
                'enable_minimum_cost' => array(
                    'title' => __('Enable Minimum amount for free shipping', 'mrkv-nova-post'),
                    'label' => __('If checked, Minimum amount for free shipping will be set for delivery', 'mrkv-nova-post'),
                    'type' => 'checkbox',
                    'default' => 'no',
                    'description' => '',
                ),
                'minimum_cost_total' => array(
                    'title' => __('Minimum amount for free shipping', 'mrkv-nova-post'),
                    'type' => 'text',
                    'placeholder' => __('Enter the amount in numbers', 'mrkv-nova-post'),
                    'description' => '',
                    'default' => 0.00
                ),
                'free_shipping_text' => array(
                    'title' => __('Text with free delivery', 'mrkv-nova-post'),
                    'type' => 'text',
                    'placeholder' => __('FREE to Nova Post', 'mrkv-nova-post'),
                    'description' => '',
                )
            );
	    }

	    /**
	     * Add rate to delivery
	     * @param array Package
	     * */
	    public function calculate_shipping( $package = array() ) 
	    {
	    	# Create rate
        	$rate = array(
                'id' => $this->id,
                'label' => $this->title,
                'cost' => 0.00,
                'calc_tax' => 'per_item'
            );

            if($this->get_option('enable_fix_cost') && $this->get_option('enable_fix_cost') == 'yes')
            {
                $rate['cost'] = $this->get_option('fix_cost_total');
            }

            if($this->get_option('enable_minimum_cost') && $this->get_option('enable_minimum_cost') == 'yes')
            {
                $woo_cart_total = WC()->cart->get_subtotal();

                if($woo_cart_total >= $this->get_option('minimum_cost_total'))
                {
                    $rate['cost'] = 0.00;

                    if($this->get_option('free_shipping_text'))
                    {
                        $rate['label'] = $this->get_option('free_shipping_text');
                    }
                }
            }

        	# Set rate
            $this->add_rate($rate);
    	}

    	/**
         * Is this method available?
         * @param array $package
         * @return bool
         */
        public function is_available($package)
        {
        	# Check shipping enabled
            return $this->is_enabled();
        }
	}
}