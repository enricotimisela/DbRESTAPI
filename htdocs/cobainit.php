<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", true);
ini_set("html_errors", true);
set_time_limit(0);
$vcap_services = json_decode($_ENV["VCAP_SERVICES"]);
$db_url = $vcap_services->{'postgres'}[0]->credentials->uri;
$db_params = parse_url($db_url);
$db_name = substr($db_params['path'], 1);
$connect_string = <<<CONNECT
host=${db_params['host']}
port=${db_params['port']}
user=${db_params['user']}
password=${db_params['pass']}
dbname=${db_name}
CONNECT;
echo "host=" . $vcap_services->{'postgres'}[0]->credentials->host;
echo "port=" . $vcap_services->{'postgres'}[0]->credentials->port;
echo "database=" . $vcap_services->{'postgres'}[0]->credentials->database;
echo "username=" . $vcap_services->{'postgres'}[0]->credentials->username;
echo "password=" . $vcap_services->{'postgres'}[0]->credentials->password;

$db_conn = pg_connect($connect_string) or die('Could not connect: ' . pg_last_error());
$query = 'SELECT id, language, url FROM buildpacks';
$result = pg_query($query) or die('Query failed: ' . pg_last_error());
?>