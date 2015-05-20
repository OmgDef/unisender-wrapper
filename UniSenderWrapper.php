<?php

namespace omgdef\unisender;


class UniSenderWrapper
{
    /**
     * @var string API key
     */
    public $apiKey = '';
    /**
     * @var string sender phone number
     */
    public $senderPhone = '+380999999999';
    /**
     * @var string sender name for sms
     */
    public $senderName = 'Hamster';
    /**
     * @var string email sender
     */
    public $senderEmail = 'xxxxxx@gmail.com';
    /**
     * @var boolean enable test mode
     */
    public $testMode = false;
    /**
     * @var bool enable compression
     */
    public $compression = false;
    /**
     * @var string
     */
    public $encoding = 'UTF8';
    /**
     * @var float request timeout
     */
    public $timeout = 10;
    /**
     * @var int
     */
    public $retryCount = 0;

    /**
     * @param string $name
     * @param array $arguments
     * @return string
     */
    public function __call($name, $arguments)
    {
        if (!is_array($arguments) || empty($arguments)) {
            $params = [];
        } else {
            $params = $arguments[0];
        }

        return $this->sendQuery($name, $params);
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function sendSms($params)
    {
        $params['sender'] = $this->senderName ?: $this->senderPhone;
        return $this->sendQuery('sendSms', $params);
    }

    /**
     * @param string $methodName
     * @param array $params
     * @return array
     */
    protected function sendQuery($methodName, array $params = [])
    {
        if ($this->encoding != 'UTF8') {
            if (function_exists('iconv')) {
                array_walk_recursive($params, array($this, 'iconv'));
            } else if (function_exists('mb_convert_encoding')) {
                array_walk_recursive($params, array($this, 'mb_convert_encoding'));
            }
        }

        $params['api_key'] = $this->apiKey;
        $body = http_build_query($params);

        if ($this->compression) {
            $body = bzcompress($body);
            $getParams['request_compression'] = 'bzip2';
        }

        $getParams = http_build_query(
            [
                'format' => 'json',
                'test_mode' => (int)$this->testMode
            ]
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout ?: 10);

        $retryCount = 0;
        do {
            curl_setopt($ch, CURLOPT_URL, $this->getApiHost($retryCount) . $methodName . '?' . $getParams);
            $result = curl_exec($ch);
            $retryCount++;
        } while ($result === false && $retryCount < $this->retryCount);

        curl_close($ch);
        return $result !== false ? json_decode($result, true) : null;
    }

    /**
     * @param int $retryCount
     * @return string
     */
    protected function getApiHost($retryCount = 0)
    {
        if ($retryCount % 2 == 0) {
            return 'https://api.unisender.com/ru/api/';
        } else {
            return 'https://www.api.unisender.com/ru/api/';
        }
    }

    /**
     * @param string $value
     * @param string $key
     */
    protected function iconv(&$value, $key)
    {
        $value = iconv($this->encoding, 'UTF8//IGNORE', $value);
    }

    /**
     * @param string $value
     * @param string $key
     */
    protected function mb_convert_encoding(&$value, $key)
    {
        $value = mb_convert_encoding($value, 'UTF8', $this->encoding);
    }
}
