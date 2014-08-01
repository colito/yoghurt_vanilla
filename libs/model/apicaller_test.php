<?php
class ApiCaller
{
    # some variables for the object
    private $_api_url;

    public function __construct($api_url)
    {
        $this->_api_url = $api_url;
    }

    public function sendRequest($request_params)
    {
        #encrypt the request parameters
        $enc_request = urlencode(json_encode($request_params));

        #create the params array, which will
        #be the POST parameters
        $params = array();
        $params['enc_request'] = $enc_request;

        #initialize and setup the curl handler
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->_api_url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);

        #execute the request
        $curl_result = curl_exec($ch);

        #save the info
        $info = curl_getinfo($ch);

        var_dump($info);

        #json_decode the result
        $result = json_decode(urldecode($curl_result));


        #check if we're able to json_decode the result correctly
        if( $result == false || isset($result->success) == false ) {
            die('Request was not correct');
        }

        #if there was an error in the request, throw an exception
        if( $result->success == false ) {
            die('there was an error in the request');
        }

        #if everything went great, return the data
        return $result->data;
    }

    # Note: cURL has a reaction towards uncommented or unremoved var_dump() in api
    public function curl_post_async($request_params)
    {
        foreach ($request_params as $key => &$val) {
            if (is_array($val)) $val = implode(',', $val);
            $post_params[] = $key.'='.urlencode($val);
        }

        $post_string = implode('&', $post_params);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->_api_url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'curl');
        curl_setopt($ch, CURLOPT_TIMEOUT, 1);
        $result = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);

        var_dump($info);

        return $result;
    }

    public function curl_post_async2($params)
    {
        if (is_array($params))
        {
            foreach ($params as $key => &$val) {
                if (is_array($val)) $val = implode(',', $val);
                $post_params[] = $key.'='.urlencode($val);
            }
        }

        if (empty($post_params))
        {
            $post_string = $params;
        } else {
            $post_string = implode('&', $post_params);
        }

        $parts=parse_url($this->_api_url);

        $parts['query'] = $post_string;

        $fp = fsockopen($parts['host'],
            isset($parts['port'])?$parts['port']:80,
            $errno, $errstr, 30);


        $out = "POST ".$parts['path'] . '?' . $parts['query']." HTTP/1.1\r\n";
        $out.= "Host: ".$parts['host']."\r\n";
        $out.= "Content-Type: application/x-www-form-urlencoded\r\n";
        //$out.= "Content-Length: ".strlen($post_string)."\r\n";
        //$out.= "Connection: Close\r\n\r\n";
        $out.= "\r\n\r\n";

        if (isset($post_string)) $out.= $post_string;

        fwrite($fp, $out);
        $response = fread($fp, 1024);

        var_dump($response);

        preg_match('/\{.*/',$response, $matches);
        $response = $matches[0];

        return $response;
    }

    public function curl_post_async3($request_params)
    {
        foreach ($request_params as $key => &$val) {
            if (is_array($val)) $val = implode(',', $val);
            $post_params[] = $key.'='.urlencode($val);
        }

        $post_string = implode('&', $post_params);

        $parts=parse_url($this->_api_url);

        $parts['query'] = $post_string;

        $headers = array(
            "GET ".$parts['path'] . '?' . $parts['query']." HTTP/1.1",
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
        $api_output['header_info'] = curl_getinfo($ch);

        #Close cURL handler
        curl_close($ch);

        return $api_output;
    }
}