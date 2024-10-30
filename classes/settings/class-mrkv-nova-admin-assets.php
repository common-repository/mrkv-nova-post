<?php
# Check if class exist
if (!class_exists('MRKV_NOVA_ADMIN_ASSETS'))
{
	/**
	 * Class for setup plugin admin assets
	 */
	class MRKV_NOVA_ADMIN_ASSETS
	{
		/**
		 * Constructor for plugin admin assets
		 * */
		function __construct()
		{
			# Load admin styles
			add_action( 'admin_enqueue_scripts', array($this, 'mrkv_nova_load_admin_style'));
		}

		/**
		 * Include admin styles and scripts
		 * @var string Hook
		 * */
		public function mrkv_nova_load_admin_style($hook)
		{
			# Check page
			if ('toplevel_page_mrkv_nova_settings' != $hook) 
			{
				# Stop job
	            return;
	        }

	        # Include all styles and script for admin
	        wp_enqueue_style('morkva-nova-admin', MRKV_NOVA_PLUGIN_URL . 'assets/css/mrkv-nova-post-admin.css', array());
	        wp_enqueue_script('morkva-nova-admin', MRKV_NOVA_PLUGIN_URL . 'assets/js/mrkv-nova-post-admin.js', array('jquery'), '0.0.1', true);
		}
	}
}