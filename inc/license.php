<?php

class Marketpress_License
{
    /**
     * license id
     */
    private $id = 'ff7zHFxOg4Th';

    private $server = 'https://user.brandmarketers.id/';

    private $code = '';

    public $data = '';

    /**
     * construction
     */
    public function __construct()
    {
        $this->code = get_option($this->id);
        $this->data = get_option('edd_marketpress_license');
    }

    public function __get($name)
    {
        $data = $this->data ? get_object_vars($this->data) : array();

        if (array_key_exists($name, $data))
            return maybe_unserialize($data[$name]);

        return NULL;
    }



    public function activate($code)
    {
        $message = '';

        $api_params = array(
            'edd_action' => 'activate_license',
            'license'    => $code,
            'item_name'  => 'MarketPress', // the name of our product in EDD
            'url'        => home_url()
        );

        // Call the custom API.
        $response = wp_remote_post($this->server, array('timeout' => 15, 'sslverify' => false, 'body' => $api_params));

        // make sure the response came back okay
        if (is_wp_error($response) || 200 !== wp_remote_retrieve_response_code($response)) {

            $message =  (is_wp_error($response) && !empty($response->get_error_message())) ? $response->get_error_message() : __('An error occurred, please try again.');
        } else {

            $license_data = (object)json_decode(wp_remote_retrieve_body($response));

            if (false === $license_data->success) {

                switch ($license_data->error) {

                    case 'expired':

                        $message = sprintf(
                            __('Your license key expired on %s.'),
                            date_i18n(get_option('date_format'), strtotime($license_data->expires, current_time('timestamp')))
                        );
                        break;

                    case 'revoked':

                        $message = __('Your license key has been disabled.');
                        break;

                    case 'missing':

                        $message = __('Invalid license.');
                        break;

                    case 'invalid':
                    case 'site_inactive':

                        $message = __('Your license is not active for this URL.');
                        break;

                    case 'item_name_mismatch':

                        $message = sprintf(__('This appears to be an invalid license key for %s.'), 'MarketPress');
                        break;

                    case 'no_activations_left':

                        $message = __('Your license key has reached its activation limit.');
                        break;

                    default:

                        $message = __('An error occurred, please try again.');
                        break;
                }
            }
        }

        // Check if anything passed on a message constituting a failure
        if (!empty($message)) {
            $base_url = admin_url('plugins.php?page=marketpress');
            $redirect = add_query_arg(array('sl_activation' => 'false', 'msg' => urlencode($message)), $base_url);

            wp_redirect($redirect);
            exit();
        }

        update_option('edd_marketpress_license', $license_data);
        update_option($this->id, $code);
        wp_redirect(admin_url('plugins.php?page=marketpress'));
        set_transient('marketpress_license_check', 1, 7 * DAY_IN_SECONDS);
        exit();
    }

    public function check()
    {
        if (empty($this->code)) return false;

        $transient = get_transient('marketpress_license_check');

        if ($transient) return false;

        $api_params = array(
            'edd_action' => 'check_license',
            'license' => $this->code,
            'item_name' => 'MarketPress',
            'url' => home_url()
        );
        $response = wp_remote_post($this->server, array('body' => $api_params, 'timeout' => 15, 'sslverify' => false));
        if (is_wp_error($response)) {
            return false;
        }

        $license_data = (object)json_decode(wp_remote_retrieve_body($response));

        if ($license_data->license == 'valid') {
            update_option('edd_marketpress_license', $license_data);
            set_transient('marketpress_license_check', 1, 7 * DAY_IN_SECONDS);
        } else {
            update_option('edd_marketpress_license', $license_data);
            update_option($this->id, '');
            set_transient('marketpress_license_check', 1, 1 * DAY_IN_SECONDS);
        }

        return true;
    }

    public function deactivate()
    {
        if (empty($this->code)) return false;

        $message = '';

        $api_params = array(
            'edd_action' => 'check_license',
            'license' => $this->code,
            'item_name' => 'MarketPress',
            'url' => home_url()
        );
        // Send the remote request
        $response = wp_remote_post($this->server, array('body' => $api_params, 'timeout' => 15, 'sslverify' => false));

        if (is_wp_error($response)) {
            $message = $response->get_error_message();
        } else {
            $license_data = (object)json_decode(wp_remote_retrieve_body($response));
            if (true == $license_data->success) {

                delete_option('edd_marketpress_license');
                delete_option($this->id);
                delete_transient('marketpress_license_check');

                $message = __('Your license was deactivate and deleted', 'marketpress');
            } else {
                $message = __('Error for some reason, please try again', 'marketpress');
            }
        }

        if (!empty($message)) {
            $base_url = admin_url('plugins.php?page=marketpress');
            $redirect = add_query_arg(array('sl_deactivation' => 'false', 'msg' => urlencode($message)), $base_url);

            wp_redirect($redirect);
            exit();
        }
    }
}
