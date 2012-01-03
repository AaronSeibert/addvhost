<?php
require('class.linode.php');


$linode = new linode();
/*
$records = array(
    1 => array(
        'Type' => 'A',
        'Name' => 'awseibert.net',
        'Target' => '66.228.44.100'
    ), 
    2 => array(
        'Type' => 'AAAA',
        'Name' => 'awseibert.net',
        'Target' => '2600:3c03::f03c:91ff:fe93:d5ff'
    ),
    3 => array(
        'Type' => 'A',
        'Name' => 'leda',
        'Target' => '66.228.44.100'
    ),
    4 => array(
        'Type' => 'AAAA',
        'Name' => 'leda',
        'Target' => '2600:3c03::f03c:91ff:fe93:d5ff'
    ),
    5 => array(
        'Type' => 'CNAME',
        'Name' => 'blog',
        'Target' => 'leda.awseibert.net'
    ),
    6 => array(
        'Type' => 'CNAME',
        'Name' => 'calendar',
        'Target' => 'ghs.google.com'
    ),
    7 => array(
        'Type' => 'CNAME',
        'Name' => 'docs',
        'Target' => 'ghs.google.com'
    ),
    8 => array(
        'Type' => 'CNAME',
        'Name' => 'home',
        'Target' => 'awseibert1.no-ip.org'
    ),
    9 => array(
        'Type' => 'CNAME',
        'Name' => 'mail',
        'Target' => 'ghs.google.com'
    ),
    10 => array(
        'Type' => 'CNAME',
        'Name' => 'start',
        'Target' => 'ghs.google.com'
    ),
    11 => array(
        'Type' => 'CNAME',
        'Name' => 'tech',
        'Target' => 'leda.awseibert.net'
    ),
    12 => array(
        'Type' => 'CNAME',
        'Name' => 'www',
        'Target' => 'leda.awseibert.net'
    ),
    13 => array(
        'Type' => 'CNAME',
        'Name' => 'www.blog',
        'Target' => 'leda.awseibert.net'
    ),
    14 => array(
        'Type' => 'MX',
        'Name' => 'awseibert.net',
        'Target' => 'aspmx3.googlemail.com',
        'Subdomain' => '',
        'Priority' => 40
    ),
    15 => array(
        'Type' => 'MX',
        'Name' => 'awseibert.net',
        'Target' => 'aspmx2.googlemail.com',
        'Subdomain' => '';
        'Priority' => 30
    ),
    16 => array(
        'Type' => 'MX',
        'Name' => 'awseibert.net',
        'Target' => 'alt1.aspmx.l.google.com',
        'Subdomain' => '',
        'Priority' => 10
    ),
    17 => array(
        'Type' => 'MX',
        'Name' => 'awseibert.net',
        'Target' => 'alt2.aspmx.l.google.com',
        'Subdomain' => '',
        'Priority' => 20
    ),
    18 => array(
        'Type' => 'TXT',
        'Name' => 'google._domainkey',
        'Target' => '"v=DKIM1; k=rsa; t=y; p=MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQChHM7JOVUV0Bwr1KE8E7VKKaGj7bbGSo2ObdQs7m6io48ZwR2Mgt66oCDXOssyhF4Zk3xX+J87em7R0kAmTbKii4eyCioPE86W90gz+BaJZo7rqHT0eL+qxhA/xWRHPPvVNxwcroXTG/TocIMNTePySuK44XvbbNreAFmFsOomnQIDAQAB"'
    )
);
*/

var_dump($linode->list_nodes());

?>
