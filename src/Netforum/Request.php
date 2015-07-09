<?php namespace Netforum;

use Netforum\Traits\SingletonTrait;
use Netforum\Exceptions\RuntimeException;

class Request extends \SoapClient
{
    use SingletonTrait;

    protected $config;
    protected $token;
    protected $ssoToken;
    protected $cstToken;

    public function __construct($wsdl, array $params)
    {
        $this->config = (object) $params;
        $this->wsdl = $wsdl;
        $this->wsdl_params = $this->constructParams($params);

        parent::__construct($wsdl, $this->wsdl_params);
        return $this;
    }

    public function getTimeout()
    {
        return (int) $this->config->timeout;
    }

    public function setTimeout(int $t)
    {
        $this->config->timeout = $t;
    }

    public function getSoapVersion()
    {
        return SOAP_1_2;
    }

    protected function constructParams(array $p)
    {
        if ( $this->config->debug ) {
            $p += ['trace' => true];
        }

        $p += [
            'exceptions'             => true,
            'soap_version'           => $this->getSoapVersion(),
            'connection_timeout'     => $this->getTimeout(),
            'default_socket_timeout' => $this->getTimeout(),
            'cache_wsdl'             => WSDL_CACHE_BOTH,
            'features'               => SOAP_SINGLE_ELEMENT_ARRAYS,
            'encoding'               => 'UTF-8',
            'user_agent'             => 'NetForum Api (Simple) by FusionSpan llc.',
        ];

        return array_filter($p);
    }

    public function __doRequest($request, $location, $action, $version, $one_way = 0)
    {
        ini_set('default_socket_timeout', $this->getTimeout());

        if ( $this->config->debug ) {
            dd(colorize(" >>> Sending Request _______", 'blue'));
            dd(colorize("Params: \nRequest: " . prettyXML($request) . "\nLocation: $location\nAction: $action\nVersion: $version", 'blue'));
            dd(colorize("________________", 'blue') . "\n\n");
        }

        return parent::__doRequest($request, $location, $action, $version);
    }

    /*public function __soapCall($cmd, array $params = [], array $options = null, \SoapHeader $headers = null)
    {
        return parent::__soapCall($cmd, $params, $options, $headers, $output_headers);
    }*/

    public function auth()
    {
        $response = $this->request('Authenticate', [
                'parameters' => [
                    'userName' => $this->config->username,
                    'password' => $this->config->password
                ]]
        );

        if ( is_object($response) ) {
            $this->token = $response->AuthenticateResult;
        }

        return $this;
    }

    public function authSso($user = null, $pass = null)
    {
        if ( is_null($user) && is_null($pass) && !$this->config->credentials ) {
            throw new \Exception('Client credentials are required.');
        }

        if ( is_null($user) && is_null($pass) && $this->config->credentials ) {
            $user = $this->config->credentials['username'];
            $pass = $this->config->credentials['password'];
        }

        $response = $this->auth()->request('GetSignOnToken', [
                'parameters' => [
                    'Email'    => $user,
                    'Password' => $pass,
                    'Minutes'  => $this->config->ttl,
                ]]
        );

        if ( is_object($response) && isset($response->GetSignOnTokenResult) ) {
            $this->ssoToken = array_pop(explode('=', $response->GetSignOnTokenResult));
        }

        return $this;
    }

    public function authCST()
    {

        $response = $this->auth()->request('GetCstKeyFromSignOnToken', [
                'parameters' => [
                    'szEncryptedSingOnToken' => $this->ssoToken,
                ]]
        );

        if ( is_object($response) ) {
            $this->cstToken = $response->GetCstKeyFromSignOnTokenResult;
        }

        return $this;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function getSsoToken()
    {
        if ( is_null($this->ssoToken) ) {
            $this->authSso();
        }

        return $this->ssoToken;
    }

    public function getCstToken()
    {
        if ( is_null($this->cstToken) ) {
            $this->authSso();
            $this->authCST();
        }

        return $this->cstToken;
    }

    public function getCustomerByKey($key = null)
    {
        //$this->getCstToken();
        return $this->OD()->request('GetCustomerByKey', [
                'parameters' => [
                    'szCstKey' => is_null($key)
                        ? $this->getCstToken()
                        : $key,
                ]]
        );
    }

    public function request($cmd, array $params = [], $headers = null)
    {
        try {

            if ( $this->config->debug ) {
                dd(colorize('Command is ' . $cmd, 'yellow'));
            }

            // embed token automatically if exists.
            if ( !isset($params['parameters']['AuthToken']) && isset($this->token) ) {
                $params['parameters']['AuthToken'] = $this->token;

                // set token headers.
                $headers = new \SoapHeader(
                    'http://www.avectra.com/OnDemand/2005/',
                    'AuthorizationToken', [
                    'Token' => $this->token,
                ]);

                if ( $this->config->debug ) {
                    dd('SENDING HEADERS: ');
                    dd($headers);
                }
            }

            // make an internal soap call.
            $resp = $this->__soapCall($cmd, $params, null, $headers);

            // debugging
            if ( $this->config->debug ) {
                dd(colorize(" <<< $cmd Response Received _______", 'green') . "\n\n");
                dd($this->toPrettyXML($cmd, $resp));
                dd(colorize("________________", 'green') . "\n\n");
            }

            return $this->toPrettyXML($cmd, $resp);
        }
        catch (\SoapFault $e) {
            $msg = $e->getMessage();
            if ( preg_match('/failed to load external entity/i', $msg) ) {
                $msg = 'request failed, netForum did not respond to our request, try again.';
            }
            throw new RuntimeException($msg, $e->getCode(), $e);
        }
    }

    private function toPrettyXML($cmd, $response)
    {
        $result = $cmd . 'Result';
        if ( !isset($response->$result->any) ) {
            return $response;
        }

        libxml_use_internal_errors(true);
        $response = simplexml_load_string($response->$result->any);

        // get only result object from response
        $response = (is_object($response) && isset($response->Result))
            ? $response->Result
            : $response;

        return sizeof($response)
            ? $response
            : [];
    }

    protected function OD()
    {
        $this->auth();

        if ( !isset($this->od) ) {
            if ( preg_match('/signon/', $this->wsdl) ) {
                $wsdl = $this->setWsdlPage('netforumxmlondemand.wsdl');
            } else {
                $wsdl = $this->setWsdlPage('netFORUMXMLONDemand.asmx');
            }
            $this->od = new static($wsdl, $this->wsdl_params);
        }

        $this->od->token = $this->token;
        $this->od->ssoToken = $this->ssoToken;
        $this->od->cstToken = $this->cstToken;

        return $this->od;
    }

    protected function getWsdlPage($page, $url = null)
    {
        if ( is_null($url) ) {
            $url = $this->wsdl;
        }

        $parts = parse_url($url);
        $parts['path'] = dirname($parts['path']) . '/' . $page;
        return http_build_url($url, $parts);
    }
}