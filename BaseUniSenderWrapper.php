<?php

namespace omgdef\unisender;


abstract class BaseUniSenderWrapper
{
    /**
     * @var string API key
     */
    public $apiKey = '';
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
     * @var string
     */
    public $encoding = 'UTF8';
    /**
     * @var string
     */
    public $language = 'ru';
    /**
     * @var float request timeout
     */
    public $timeout = 10;
    /**
     * @var int
     */
    public $retryCount = 0;

    /**
     * @param string $methodName
     * @param array $params
     * @return array
     */
    abstract public function sendQuery($methodName, array $params = []);

    /**
     * @return string
     */
    protected function getApiHost()
    {
        return "https://api.unisender.com/{$this->language}/";
    }

    /**
     * @param $params
     */
    protected function convertParamsEncoding(&$params)
    {
        if ($this->encoding != 'UTF8') {
            if (function_exists('iconv')) {
                array_walk_recursive($params, [$this, 'iconv']);
            } else if (function_exists('mb_convert_encoding')) {
                array_walk_recursive($params, [$this, 'mb_convert_encoding']);
            }
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
