<?php
namespace LaravelAcademy\UrlScanner\Url;

use GuzzleHttp\Client;

class Scanner
{
    protected $urls;

    protected $httpClient;
    /**
     * Scanner constructor.
     * @param $urls
     */
    public function __construct(array $urls)
    {
        $this->urls = $urls;
        $this->httpClient = new Client();
    }

    /**
     * 获取访问url的HTTP状态码
     * @param $url
     * @return int
     */
    public function getStatusCodeForUrl($url)
    {
        $httpResponse = $this->httpClient->get($url,['verify' => false]);
        return $httpResponse->getStatusCode();
    }

    /**
     * 获取不能访问的链接
     * @return array
     */
    public function getInvalidUrls()
    {
        $invalidUrls = [];
        foreach ($this->urls as $url) {
            try {
                $statusCode = $this->getStatusCodeForUrl($url);
            } catch (\Exception $e) {
                $statusCode = 500;
            }

            if ($statusCode >= 400) {
                array_push($invalidUrls, ['url' => $url, 'status' => $statusCode]);
            }
        }

        return $invalidUrls;
    }
}