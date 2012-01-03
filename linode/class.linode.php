<?php

class linode
{
    function linode() {
        $this->api_key = '';
        $this->api_url = "https://api.linode.com/?api_key=" . $this->api_key;
    }

    function list_domains() {
        $action = "domain.list";
        $params = "";

        $results = self::api_call($action,$params);

        foreach ($results['DATA'] as $domain) {
            echo "Domain Name: " . $domain['DOMAIN'] . "\n";
            echo "Domain ID: " . $domain['DOMAINID'] . "\n\n";
        }
    }

    function add_domain($domain,$email) {
		$action = "domain.create";
		$params['Domain'] = $domain;
		$params['Type'] = 'master';
		$params['SOA_Email'] = $email;
		$results = self::api_call($action,$params);
		if (!$results['ERRORARRAY']) {
			$message = $domain . " created as ID " . $results['DATA']['DomainID'] . "\n";
			return $message;
		}
    }

    function list_records($domain_id) {
        $action = "domain.resource.list";
        $params['DOMAINID'] = $domain_id;

        $results = self::api_call($action,$params);
        
        foreach ($results['DATA'] as $record) {
            foreach ($record as $key=>$val ){
                echo $key . ": " . $val . "\n";
            }
        }
    }

    function create_record($records,$domain_id) {
        $action = "domain.resource.create";
        foreach ($records as $record) {
            $record['DOMAINID']=$domain_id;
            self::api_call($action,$record);
        }
    }

    function list_nodes() {
        $action = "linode.list";
        return self::api_call($action,'');
    }

    function api_call($action,$params) {
        $ch = curl_init();
        $url = $this->api_url . "&api_action=" . $action;
        
        if ($params) {
            foreach ($params as $attribute=>$value) {
                $url = $url . "&" . $attribute . "=" . $value;
            }
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        curl_close($ch);
        $results = json_decode($response,true);
        if ($results['ERRORARRAY']) {
            foreach ($results['ERRORARRAY'] as $error) {
                echo $error['ERRORMESSAGE'] . "\n";
            }
            exit();
        } else {
            return $results;
        }
    }
}       
