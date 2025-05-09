<?php

namespace common\components;

use yii\base\Component;

class CurlComponent extends Component
{
	/**
	 * 
	 * @param string $url
	 * @param array $fields
	 * @return mixed
	 */
    public static function sendRequest($url, $fields, $typePost = false)
    {  	
    	$ch = curl_init();
    	
    	curl_setopt($ch, CURLOPT_URL, $url);  	
    	
    	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    	if ($typePost) {
    		curl_setopt($ch, CURLOPT_URL, $url);
    		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    	} else {
    		$queryString = http_build_query($fields);
    		curl_setopt($ch, CURLOPT_URL, $url . $queryString);
    	}    	
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    	
    	// Execute post
    	$result = curl_exec($ch);

        Yii::debug([
            'url' => $url,
            'fields' => $fields,
            'response' => $result,
            'curlInfo' => curl_getinfo($ch)
        ]);

    	// Close connection
    	curl_close($ch);
    	 
    	return $result;
    }
    
    /** Отправить сюриализованный запрос
     * @param string $url
     * @param array $headers
     * @param array $fields
     * @return mixed
     */
    public static function sendJsonRequest($url, $headers = null, $fields = null)
    {
    	// Open connection
    	$ch = curl_init();
    	curl_setopt($ch, CURLOPT_URL, $url);
    	
    	if (!empty($headers)) {
    		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    	}
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    	if (!empty($fields)) {
    		curl_setopt($ch, CURLOPT_POST, true);
    		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    	}
    	// Execute post
    	$result = curl_exec($ch);
    	
    	// Close connection
    	curl_close($ch);
    	
    	return $result;
    }
}