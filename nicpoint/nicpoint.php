<?php
/**
 * WHMCS NICPOINT Registrar Module
 *
 * Built by: BYTE d.o.o. Sarajevo | https://byte.ba
 */

if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

require_once __DIR__ . '/lib/NicpointApiClient.php';

use WHMCS\Module\Registrar\Nicpoint\NicpointApiClient;

/**
 * Define module configuration
 */
function nicpoint_MetaData()
{
    return [
        'DisplayName' => 'NICPOINT.BA Registrar',
        'APIVersion' => '1.0.0',
    ];
}

/**
 * Define module configuration options
 */
function nicpoint_ConfigOptions()
{
    return [
        'Username' => [
            'Type' => 'text',
            'Size' => '25',
            'Default' => '',
            'Description' => 'Enter your NICPOINT username',
        ],
        'Password' => [
            'Type' => 'password',
            'Size' => '25',
            'Default' => '',
            'Description' => 'Enter your NICPOINT password',
        ],
    ];
}

/**
 * Initialize API Client
 */
function nicpoint_GetApiClient($params)
{
    return new NicpointApiClient(
        'https://api.nicpoint.ba',
        'https://auth.nicpoint.ba',
        $params['Username'],
        $params['Password']
    );
}

/**
 * Check Domain Availability
 */
function nicpoint_CheckAvailability($params)
{
    $client = nicpoint_GetApiClient($params);
    $domains = [$params['domainName'] . '.' . $params['tld']];

    try {
        $results = $client->checkAvailability($domains);
        // Map NICPOINT response to WHMCS availability results
        // This is a simplified mapping
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
function nicpoint_RegisterDomain($params)
{
    $client = nicpoint_GetApiClient($params);

    $postData = [
        'domainName' => $params['domainname'],
        'period' => $params['regperiod'],
        'contactId' => $params['contactId'] ?? '', // WHMCS should provide mapping
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
function nicpoint_RenewDomain($params)
{
    $client = nicpoint_GetApiClient($params);

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
function nicpoint_TransferDomain($params)
{
    $client = nicpoint_GetApiClient($params);

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
function nicpoint_GetNameservers($params)
{
    $client = nicpoint_GetApiClient($params);

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
