<?php

namespace WHMCS\Module\Registrar\Nicpoint;

/**
 * Nicpoint API Client for WHMCS
 * 
 * Handles authentication and communication with the NICPOINT API.
 */
class NicpointApiClient
{
    private $apiUrl;
    private $authUrl;
    private $username;
    private $password;
    private $token;
    private $refreshToken;
    private $tokenExpiry;

    public function __construct($apiUrl, $authUrl, $username, $password)
    {
        $this->apiUrl = rtrim($apiUrl, '/');
        $this->authUrl = rtrim($authUrl, '/');
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Authenticate and retrieve JWT token
     */
    private function authenticate()
    {
        // Simple caching logic - ideally should be stored in WHMCS DB or temporary file
        if ($this->token && $this->tokenExpiry > time()) {
            return $this->token;
        }

        $endpoint = '/api/auth/login';
        if ($this->refreshToken) {
            $endpoint = '/api/auth/refreshtoken';
            $data = ['refreshToken' => $this->refreshToken];
        } else {
            $data = [
                'username' => $this->username,
                'password' => $this->password
            ];
        }

        $response = $this->call($endpoint, 'POST', $data, false);

        if (isset($response['authToken'])) {
            $this->token = $response['authToken'];
            $this->refreshToken = $response['refreshToken'];
            $this->tokenExpiry = time() + ($response['expiresIn'] ?? 3600) - 60; // Buffer
            return $this->token;
        }

        throw new \Exception("NICPOINT Authentication Failed: " . ($response['message'] ?? 'Unknown Error'));
    }

    /**
     * Generic API call method
     */
    public function call($endpoint, $method = 'GET', $data = [], $requiresAuth = true)
    {
        $baseUrl = $requiresAuth ? $this->apiUrl : $this->authUrl;
        $url = $baseUrl . $endpoint;
        $ch = curl_init($url);

        $headers = [
            'Content-Type: application/json',
            'Accept: application/json'
        ];

        if ($requiresAuth) {
            $token = $this->authenticate();
            $headers[] = 'Authorization: Bearer ' . $token;
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        
        if (!empty($data)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            throw new \Exception("NICPOINT API Connection Error: " . $error);
        }

        $decodedResponse = json_decode($response, true);

        if ($httpCode >= 400) {
            $msg = $decodedResponse['message'] ?? 'API Error ' . $httpCode;
            throw new \Exception("NICPOINT API Error: " . $msg);
        }

        return $decodedResponse;
    }

    /**
     * Check domain availability
     */
    public function checkAvailability($domains)
    {
        return $this->call('/api/Domain/Check', 'POST', (array)$domains);
    }

    /**
     * Register a new domain
     */
    public function registerDomain($params)
    {
        return $this->call('/api/Domain', 'POST', $params);
    }

    /**
     * Renew a domain
     */
    public function renewDomain($domainName, $period = 1)
    {
        return $this->call('/api/Domain/RenewDomain', 'POST', [
            'domainName' => $domainName,
            'period' => (int)$period
        ]);
    }

    /**
     * Transfer a domain
     */
    public function transferDomain($domainName, $authCode)
    {
        return $this->call('/api/Domain/TransferDomain', 'POST', [
            'domainName' => $domainName,
            'authCode' => $authCode
        ]);
    }

    /**
     * Get domain details
     */
    public function getDomain($domainName)
    {
        return $this->call('/api/Domain/' . urlencode($domainName), 'GET');
    }
}
