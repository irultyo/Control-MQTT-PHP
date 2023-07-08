<?php
require('vendor/autoload.php');

use \PhpMqtt\Client\MqttClient;
use \PhpMqtt\Client\ConnectionSettings;

$server   = '192.168.43.195';
$port     = 1883;
$clean_session = false;
$mqtt_version = MqttClient::MQTT_3_1_1;

$connectionSettings = (new ConnectionSettings)
    ->setKeepAliveInterval(60)
    ->setLastWillMessage('client disconnect')
    ->setLastWillQualityOfService(1);


$mqtt = new MqttClient($server, $port, $mqtt_version);

$mqtt->connect($connectionSettings, $clean_session);

if (isset($_POST['state_auto'])) {
    $payload = array(
        "auto_fan" => (int)$_POST['state_auto'],
        "state_fan" => (int)$_POST['fan_state_auto'],
        "auto_lamp" => 0,
        "state_lamp" => 0
    );
    $mqtt->publish(
        'actuators/all',
        json_encode($payload),
        0,
        true
    );
    header('Location:index.php');
}else if (isset($_POST['state_fan'])) {
    $payload = array(
        "auto_fan" => (int)$_POST['auto_state_fan'],
        "state_fan" => (int)$_POST['state_fan'],
        "auto_lamp" => 0,
        "state_lamp" => 0
    );
    $mqtt->publish(
        'actuators/all',
        json_encode($payload),
        0,
        true
    );
    header('Location:index.php');
} 
?>