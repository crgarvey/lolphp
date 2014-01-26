<?php
/**
 * Created for Lolphp on 1/24/14.
 *
 * @author Robbie Vaughn <robbie@robbievaughn.me>
 */
namespace Lolphp;

/**
 * Class Connection
 * @package Lolphp
 */
class Connection
{
    const VERB_GET    = 'GET';
    const VERB_POST   = 'POST';
    const VERB_PUT    = 'PUT';
    const VERB_DELETE = 'DELETE';

    const RESPONSE_JSON = 'json';
    const RESPONSE_XML  = 'xml';

    const APIMETHOD_PARAMTYPE_PREPEND   = 0;
    const APIMETHOD_PARAMTYPE_APPEND    = 1;

    /**
     * Constants: Regions
     */
    const REGION_NORTHAMERICA = 'na';

    /**
     * Constants: API Methods
     */
    const APIMETHOD_SUMMONER = 'summoner';

    /**
     * List of all API method versions
     *
     * @var array $apiMethodVersionList
     */
    public $apiMethodVersionList       = [
        self::APIMETHOD_SUMMONER        => 'v1.3'
    ];

    private $apiUrl = '';
    private $apiKey = '';
    private $responseType = self::RESPONSE_JSON;
    private $timeout = 15;

    /**
     * @param $apiUrl
     * @param $apiKey
     */
    public function __construct(
        $apiUrl,
        $apiKey
    ) {
        $this->apiUrl = $apiUrl;
        $this->apiKey = $apiKey;
    }

    /**
     * Check HTTP Status and return appropriate response
     *
     * @param $status
     *
     * @return bool
     * @throws \Exception
     */
    private function checkStatus($status)
    {
        switch ($status) {
            case 200:

            case 201:

            case 204:
                return true;

            case 422:
                throw new \Exception('Request cannot be processed.', 422);

            case 404:
                throw new \Exception('Request cannot be found.', 404);

            case 403:
                throw new \Exception('Request is unauthorized.', 403);

            case 500:

            default:
                throw new \Exception('Remote server error.', 500);
        }
    }

    /**
     * @param string $request
     * @param array $fields
     * @param string $verb
     * @return mixed
     * @throws \Exception
     */
    public function call(
        $request,
        Array $fields = [],
        $verb = self::VERB_GET
    ) {
        // Append api_key to fields.
        $fields['api_key'] = $this->apiKey;

        $curlPath = $this->apiUrl . $request;
        $ch = curl_init();

        $curlOpts = array(
            CURLOPT_URL            => $curlPath,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => $this->timeout,
            CURLOPT_HTTPHEADER     => array(
                'X-Api-Key: ' . $this->apiKey,
                'Content-Type: application/' . $this->responseType,
                'Accept: application/' . $this->responseType
            ),
            CURLOPT_SSL_VERIFYPEER => false
        );

        switch ($verb) {
            case $this::VERB_GET:

            default:
                $curlOpts[CURLOPT_POST] = false;
                $curlOpts[CURLOPT_URL] = $curlPath . '?' . http_build_query($fields);
                $curlOpts[CURLOPT_POST] = false;
                break;
        }

        curl_setopt_array($ch, $curlOpts);

        $result     = curl_exec($ch);
        $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $responseData = json_decode($result);

        try {
            $this->checkStatus($httpStatus);
        } catch (\Exception $e) {
            $message = $e->getMessage();
            if (!empty($responseData->error)) {
                $message .= ' {' . $responseData->error . '}';
            }

            throw new \Exception($message, $e->getCode());
        }

        return $responseData;
    }
}