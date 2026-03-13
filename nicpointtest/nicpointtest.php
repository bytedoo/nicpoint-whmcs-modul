<?php
/**
 * WHMCS NICPOINT.BA TEST Registrar Module
 *
 * Built by: BYTE d.o.o. Sarajevo | https://byte.ba
 */

if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

require_once __DIR__ . '/lib/NicpointTestApiClient.php';

use WHMCS\Module\Registrar\NicpointTest\NicpointTestApiClient;

/**
 * Define module configuration
 */
function nicpointtest_MetaData()
{
    return [
        'DisplayName' => 'NICPOINT.BA TEST ENV',
        'APIVersion' => '1.1',
    ];
}

/**
 * Define module configuration options
 */
function nicpointtest_ConfigOptions()
{
    return [
        'Username' => [
            'Type' => 'text',
            'Size' => '25',
            'Default' => '',
            'Description' => 'Enter your NICPOINT TEST username',
        ],
        'Password' => [
            'Type' => 'password',
            'Size' => '25',
            'Default' => '',
            'Description' => 'Enter your NICPOINT TEST password',
        ],
    ];
}

/**
 * Initialize API Client
 */
function nicpointtest_GetApiClient($params)
{
    return new NicpointTestApiClient(
        'https://apitest.nicpoint.ba',
        'https://authtest.nicpoint.ba',
        $params['Username'],
        $params['Password']
    );
}

/**
 * Check Domain Availability
 */
function nicpointtest_CheckAvailability($params)
{
    $client = nicpointtest_GetApiClient($params);
    $domains = [$params['domainName'] . '.' . $params['tld']];

    try {
        $results = $client->checkAvailability($domains);
        return [
            'status' => ($results[0]['available'] ?? false) ? 'available' : 'unavailable'
        ];
    } catch (\Exception $e) {
        return ['error' => $e->getMessage()];
    }
}

/**
 * Register Domain
 */
function nicpointtest_RegisterDomain($params)
{
    $client = nicpointtest_GetApiClient($params);

    $postData = [
        'domainName' => $params['domainname'],
        'period' => $params['regperiod'],
        'contactId' => $params['contactId'] ?? '',
        'nsSets' => $params['nsSet'] ?? '',
    ];

    try {
        $client->registerDomain($postData);
        return ['success' => true];
    } catch (\Exception $e) {
        return ['error' => $e->getMessage()];
    }
}

/**
 * Renew Domain
 */
function nicpointtest_RenewDomain($params)
{
    $client = nicpointtest_GetApiClient($params);

    try {
        $client->renewDomain($params['domainname'], $params['regperiod']);
        return ['success' => true];
    } catch (\Exception $e) {
        return ['error' => $e->getMessage()];
    }
}

/**
 * Transfer Domain
 */
function nicpointtest_TransferDomain($params)
{
    $client = nicpointtest_GetApiClient($params);

    try {
        $client->transferDomain($params['domainname'], $params['eppcode']);
        return ['success' => true];
    } catch (\Exception $e) {
        return ['error' => $e->getMessage()];
    }
}

/**
 * Get Nameservers
 */
function nicpointtest_GetNameservers($params)
{
    $client = nicpointtest_GetApiClient($params);

    try {
        $data = $client->getDomain($params['domainname']);
        return [
            'ns1' => $data['nsset']['ns1'] ?? '',
            'ns2' => $data['nsset']['ns2'] ?? '',
            'ns3' => $data['nsset']['ns3'] ?? '',
            'ns4' => $data['nsset']['ns4'] ?? '',
        ];
    } catch (\Exception $e) {
        return ['error' => $e->getMessage()];
    }
}
