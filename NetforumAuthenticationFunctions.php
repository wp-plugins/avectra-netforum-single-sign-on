<?php
/**
 * Created by JetBrains PhpStorm.
 * User: gkher
 * Date: 11/30/12
 * Time: 4:13 PM
 */

class AuthenticationFunctions {

    private $ssoUrl, $strGlobalUser, $strGlobalPassword;


    function __construct($ssoUrl, $strGlobalUser, $strGlobalPassword) {
        $this->ssoUrl = $ssoUrl;
        $this->strGlobalPassword = $strGlobalPassword;
        $this->strGlobalUser = $strGlobalUser;
    }

    /**
     * @return string Global Authentication Token from Avectra SSO
     */
    private function globalAuth()
    {
        $globalToken = '';
        $globalWsClient = new SoapClient($this->ssoUrl, Array('trace' => true, 'exceptions' => true));

        //Set Authentication Parameters
        $authReqParams = Array('userName' => $this->strGlobalUser, 'password' => $this->strGlobalPassword);
        $responseHeaders = '';
        try
        {
            $response = $globalWsClient->__SoapCall("Authenticate", array('parameters' => $authReqParams), null, null, $responseHeaders);

            foreach ($response as $item)
                $globalToken = $item;

            return $globalToken;
        }
        catch (Exception $e)
        {
            return ("");
        }
    }

    /**
     * Authenticate Single Sign-On User
     * with the Avectra SSO service
     *
     * @param $strUser Username
     * @param $strPassword Password
     * @param $ssoToken Avectra SSO Token
     * @return bool|string SSO Authentication Token for User
     */
    public function ssoAuth($strUser, $strPassword, &$ssoToken)
    {
        try
        {
            $globalToken = $this->globalAuth();
            if (strlen($globalToken) == 0) {
                return '';
            }

            $ssoToken = '';
            $ssoWsClient = new SoapClient($this->ssoUrl, Array('soap_version' => SOAP_1_2, 'trace' => true, 'exceptions' => true));

            //Set Authentication Parameters
            $authSSOParams = Array('Email' => $strUser, 'Password' => $strPassword, 'AuthToken' => $globalToken, 'Minutes' => 30);

            $responseHeaders = '';
            $response = $ssoWsClient->__SoapCall("GetSignOnToken", array('parameters' => $authSSOParams), null, null, $responseHeaders);

            foreach ($response as $item)
                $ssoToken = $item;

            $ssoToken = $this->sanitizeToken($ssoToken);

            return (True);


        } catch (Exception $ex)
        {
            return '';
        }
    }

     /**
      * Will remove the ssoToken= prefix from the sso token
     * @param $ssoToken token
     * @return string SSO Token without any prefix
     */
    function sanitizeToken($ssoToken) {

        if(strrpos($ssoToken,"ssoToken=") == 0) {
            return substr($ssoToken,9);
        }
        return $ssoToken;
    }
}