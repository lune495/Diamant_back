<?php

namespace App\Services;

use GuzzleHttp\Client;
use App\Models\{Outil};
class PayTechService
{
    protected $client;
    // const apiSecret  = '5f7c2f342fb550d8ddfd8aa6a88cd1157466e20c68163c2864fab2cb12e9f4fb';
    // const apiKey = 'f6f991952e622ca2145b3bc33539c1f99b55ac4874d6137f05938abda48d6e2c';

    //protected $apiKey; // Clé d'API PayTech SN
    /**
     * @var string
     */
    const URL = "https://paytech.sn";
    const PAYMENT_REQUEST_PATH = '/api/payment/request-payment';
    const PAYMENT_REDIRECT_PATH = '/payment/checkout/';//todo
    //const URL = "http://localhost:5008";//todo
    const MOBILE_CANCEL_URL = "https://paytech.sn/mobile/cancel";
    const MOBILE_SUCCESS_URL = "https://paytech.sn/mobile/success";
    
    /**
     * @var string
     */
    private $apiKey =  '5b1d7da9c93fdc7cbbd903e6c9bcc6687357559dc6648c042c00699e557d9534';
    /**
     * @var string
     */
    private $apiSecret = '5f7c2f342fb550d8ddfd8aa6a88cd1157466e20c68163c2864fab2cb12e9f4fb';
    /**
     * @var array
     */
    private $query = [];

    private $params = [];

    /**
     * @var array
     */
    private $customeField = [];

    private $liveMode = true;

    private $testMode = false;

    private $isMobile = false;

    private $currency = 'XOF';
    private $refCommand = '';

    private $notificationUrl = [];

    public function __construct($apiSecret,$apiKey)
    {
        $this->setApiKey($apiKey);
        $this->setApiSecret($apiSecret);

        if (!empty($_POST['is_mobile']) && $_POST['is_mobile'] === 'yes') {
            $this->isMobile = true;
        }
    }

    /**
     * @param string $apiKey
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @param string $apiSecret
     */
    public function setApiSecret($apiSecret)
    {
        $this->apiSecret = $apiSecret;
    }

        public function createPayment(array $data)
        {
            $params = [
                'item_name' => $data['item_name'],
                'item_price' => $data['item_price'],
                'command_name' => $data['command_name'],
                'ref_command' => $data['ref_command'],
                'env' => $data['env'],
                'currency' => $data['currency'],
                'ipn_url' => $data['ipn_url'],
                'success_url' => $data['success_url'],
                'cancel_url' => $data['cancel_url'],
                'custom_field' => $data['custom_field']
            ];
            $rawResponse = PayTechService::post($this::URL.$this::PAYMENT_REQUEST_PATH, $params, [
                "API_KEY: {$this->apiKey}",
                "API_SECRET: {$this->apiSecret}"
            ]);
                //var_dump($rawResponse);
                /**
                 * @var array
                 */
                    if (!is_string($rawResponse)) {
                    $jsonResponse = $rawResponse;
                }else {
                    $startPos = strpos($rawResponse, '{');
                    $jsonResponse = substr($rawResponse, $startPos);
                    $jsonResponse = json_decode($jsonResponse, true);
                }
                
                if ($jsonResponse !== null && isset($jsonResponse->token)) {
                    $query = '';
                    return [
                        'success' => 1,
                        'token' => $jsonResponse->token,
                        'redirect_url' => $this::URL.$this::PAYMENT_REDIRECT_PATH.$jsonResponse->token.$query
                    ];
                } else if (array_key_exists('error', $jsonResponse)) {
                    return [
                        'success' => -1,
                        'errors' => $jsonResponse['error']
                    ];
                } else {
                    return [
                        'success' => -1,
                        'errors' => [
                            'Internal Error'
                        ]
                    ];
                }
        }

        private static function arrayGet($array, $name, $default = '')
        {
            return empty($array[$name]) ? $default : $array[$name];
        }

        private static function post($url, $data = [], $header = [])
        {
            $retour = null;
            $strPostField = http_build_query($data);

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $strPostField);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

            curl_setopt($ch, CURLOPT_HTTPHEADER, array_merge($header, [
                'Content-Type: application/x-www-form-urlencoded;charset=utf-8',
                'Content-Length: ' . mb_strlen($strPostField)
            ]));

            $response = curl_exec($ch);
            $info = curl_getinfo($ch);
            if ($response === FALSE)
            {
                $ch = Outil::donneBonFormatString($ch);
                $retour = "Error cUrl (#%d): %"." / ".curl_errno($ch)." / ".htmlspecialchars(curl_error($ch));
            }
            curl_close($ch);

            if ($info['http_code'] != 200 && $info['http_code'] != 201 && $info['http_code'] != 206)
            {
                $response = Outil::donneBonFormatString($response);
                $retour = 'Error http code: ' . $info['http_code'] . ', body message: ' . $response;
            }
            else
            {
                $retour = json_decode($response);
                //Good
            }

            return $retour;
        }

    public function getUser($userId)
    {
        $response = $this->client->get('/users/' . $userId);

        return json_decode($response->getBody(), true);
    }
}