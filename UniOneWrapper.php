<?php

namespace omgdef\unisender;


class UniOneWrapper extends BaseUniSenderWrapper
{
    /**
     * @var string user name in the UniOne service
     */
    public $userName;

    /**
     * @param array $params
     * @return mixed
     */
    public function send($params)
    {
        return $this->sendQuery("transactional/api/v1/email/send.json", $params);
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function setTemplate($params)
    {
        return $this->sendQuery("transactional/api/v1/template/set.json", $params);
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function getTemplate($params)
    {
        return $this->sendQuery("transactional/api/v1/template/get.json", $params);
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function listTemplates($params = [])
    {
        return $this->sendQuery("transactional/api/v1/template/list.json", $params);
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function deleteTemplate($params)
    {
        return $this->sendQuery("transactional/api/v1/template/delete.json", $params);
    }

    /**
     * @return mixed
     */
    public function balance()
    {
        return $this->sendQuery("transactional/api/v1/balance.json");
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function checkUnSubscribed($params)
    {
        return $this->sendQuery("transactional/api/v1/unsubscribed/check.json", $params);
    }

    /**
     * @return mixed
     */
    public function listUnSubscribed()
    {
        return $this->sendQuery("transactional/api/v1/unsubscribed/list.json");
    }

    /**
     * @param $params
     * @return array
     */
    public function setWebHook($params)
    {
        return $this->sendQuery("transactional/api/v1/webhook/set.json", $params);
    }

    /**
     * @param $params
     * @return array
     */
    public function getWebHook($params)
    {
        return $this->sendQuery("transactional/api/v1/webhook/get.json", $params);
    }

    /**
     * @param $params
     * @return array
     */
    public function deleteWebHook($params)
    {
        return $this->sendQuery("transactional/api/v1/webhook/delete.json", $params);
    }

    /**
     * @param $params
     * @return array
     */
    public function getCheckedEmail($params = [])
    {
        $params['login'] = $this->userName;
        return $this->sendQuery("api/getCheckedEmail", $params);
    }

    /**
     * @param string $methodName
     * @param array $params
     * @return array
     */
    public function sendQuery($methodName, array $params = [])
    {
        $this->convertParamsEncoding($params);
        $params['api_key'] = $this->apiKey;
        $params['username'] = $this->userName;

        $body = json_encode($params);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->timeout ?: 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout ?: 10);

        $retryCount = 0;
        do {
            curl_setopt($ch, CURLOPT_URL, $this->getApiHost() . $methodName);
            $result = curl_exec($ch);
            $retryCount++;
        } while ($result === false && $retryCount < $this->retryCount);

        curl_close($ch);

        return $result !== false ? json_decode($result, true) : null;
    }
    
    /**
     * @return string
     */
    protected function getApiHost()
    {
        return "https://one.unisender.com/{$this->language}/";
    }
}
