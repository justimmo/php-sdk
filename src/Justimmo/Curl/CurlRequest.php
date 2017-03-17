<?php
namespace Justimmo\Curl;

/**
 * Class CurlRequest
 *
 * Class represents a CurlRequest from Creation to configuring, requesting and info
 *
 * Usage :
 * $request = new CurlRequest('http:…');
 * $request->setOption(CURLOPT_TIMEOUT, 2);
 * …
 * …
 * $content = $request->get();
 * or
 * $content = $request->post(array('username' => 'test));
 * (supports put too)
 *
 * after that you can ask the lass about various information like
 * $request->getStatusCode()
 * $request->getContentType()
 * $request->getError()
 * (supports all curl constants, but not all have a shortcut method like getStatusCode)
 *
 * @package Justimmo\Curl
 */
class CurlRequest
{
    /**
     * Options for curl request
     *
     * @var array
     */
    protected $options = array();

    /**
     * the url
     *
     * @var string
     */
    protected $url = null;

    /**
     * @var string
     */
    protected $content = null;

    /**
     * @var mixed
     */
    protected $infos = array();

    /**
     * @var string
     */
    protected $error = null;

    /**
     * constructor
     *
     * @param       $url
     * @param array $options
     *
     * @throws CurlException
     */
    public function __construct($url = null, $options = array())
    {
        $this->url     = $url;
        $this->options = $options;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @return array
     */
    public function getInfos()
    {
        return $this->infos;
    }

    /**
     * retrieve an info about this curl request
     *
     * @param $key
     *
     * @return mixed
     * @throws CurlException
     */
    public function getInfo($key)
    {
        if (!array_key_exists($key, $this->infos)) {
            throw new CurlException('Information ' . $key . ' not found in CurlRequest');
        }

        return $this->infos[$key];
    }

    /**
     * return status code of last curl request
     *
     * @return int
     */
    public function getStatusCode()
    {
        return $this->getInfo('http_code');
    }

    /**
     * returns the content type of this curl request
     *
     * @return mixed
     */
    public function getContentType()
    {
        return $this->getInfo('content_type');
    }

    /**
     * removes all options from the request
     *
     * @return $this
     */
    public function clearOptions()
    {
        $this->options = array();

        return $this;
    }

    /**
     * sets a new set of options, overrides the old ones
     *
     * @param $options
     *
     * @return $this
     */
    public function setOptions($options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * sets a specifig option for this curl request
     *
     * @param $key
     * @param $value
     *
     * @return $this
     */
    public function setOption($key, $value)
    {
        $this->options[$key] = $value;

        return $this;
    }

    /**
     * gets the value of an option in this curl request
     *
     * @param $key
     *
     * @return mixed
     * @throws CurlException
     */
    public function getOption($key)
    {
        if (!array_key_exists($key, $this->options)) {
            throw new CurlException('The Options ' . $key . ' is not registered in this CurlRequest');
        }

        return $this->options[$key];
    }

    /**
     * removes an option from this curl request
     *
     * @param $key
     *
     * @return $this
     */
    public function removeOption($key)
    {
        unset($this->options[$key]);

        return $this;
    }

    /**
     * sets the parameters for a post request
     *
     * @param array $v
     *
     * @return $this
     */
    public function setParameters($v = array())
    {
        $this->setOption(CURLOPT_POSTFIELDS, $v);

        return $this;
    }

    /**
     * executes a get request
     *
     * @return mixed
     */
    public function get()
    {
        $this->setOption(CURLOPT_HTTPGET, true);

        return $this->execute();
    }

    /**
     * executes a post request
     *
     * @param null $parameters
     *
     * @return mixed
     */
    public function post($parameters = null)
    {
        $this->setOption(CURLOPT_POST, true);
        if ($parameters !== null) {
            $this->setParameters($parameters);
        }

        return $this->execute();
    }

    /**
     * executes a put request
     *
     * @param null $parameters
     *
     * @return mixed
     */
    public function put($parameters = null)
    {
        $this->setOption(CURLOPT_PUT, true);
        if ($parameters !== null) {
            $this->setParameters($parameters);
        }

        return $this->execute();
    }

    /**
     * executes the curl request and fills in the information
     *
     * @return mixed
     * @throws CurlException
     */
    protected function execute()
    {
        if ($this->url === null) {
            throw new CurlException('You have to provide an URL for the CurlRequest');
        }

        $ch = curl_init($this->url);
        curl_setopt_array($ch, $this->options);

        $this->content = curl_exec($ch);
        $this->infos   = curl_getinfo($ch);
        $this->error   = curl_error($ch);

        curl_close($ch);

        return $this->content;
    }
}
