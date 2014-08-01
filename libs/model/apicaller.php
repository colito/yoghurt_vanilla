<?php
class ApiCaller
{
    # some variables for the object
    private $_api_url;

    public function __construct($api_url)
    {
        $this->_api_url = $api_url;
    }

    # Note: cURL has a reaction towards uncommented or unremoved var_dump() in api
    public function curl_post_async($request_params)
    {
        foreach ($request_params as $key => &$val) {
            if (is_array($val)) $val = implode(',', $val);
            $post_params[] = $key.'='.urlencode($val);
        }

        $post_string = implode('&', $post_params);

        $parts=parse_url($this->_api_url);

        $headers = array(
            "POST HTTP/1.1",
            "Host: ".$parts['host'],
            "Content-Type: application/x-www-form-urlencoded"
        );

        $api_output = array();

        #Initialise cURL handler and set options
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->_api_url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'curl');
        curl_setopt($ch, CURLOPT_TIMEOUT, 1);

        # Save cURL result
        $api_output['result'] = curl_exec($ch);
        $api_output['header_out'] = curl_getinfo($ch);

        #Close cURL handler
        curl_close($ch);

        return $api_output;
    }
}