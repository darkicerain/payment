<?php
ini_set("display_errors", "On");
/*
 * The file is part of the payment lib.
 *
 * (c) Leo <dayugog@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

require_once __DIR__ . '/../../vendor/autoload.php';


date_default_timezone_set('Asia/Shanghai');
$aliConfig = require_once __DIR__ . '/../config/aliconfig.php';

// 交易信息
$tradeNo = time() . rand(1000, 9999);
$payData = [
    'product_code' => 'CYCLE_PAY_AUTH',
    'personal_product_code' => 'CYCLE_PAY_AUTH_P',
    'access_params' => ['channel' => 'QRCODE'],
    'period_rule_params' => [
        'period_type' => 'DAY',
        'period' => 7,
        'execute_time' => '2022-11-23',
        'single_amount' => 1
    ],
];

// 使用
try {
    $client = new \Payment\Client(\Payment\Client::ALIPAY, $aliConfig);
    $res = $client->pay(\Payment\Client::ALI_CHANNEL_SIGN, $payData);
} catch (InvalidArgumentException $e) {
    echo $e->getMessage();
    exit;
} catch (\Payment\Exceptions\GatewayException $e) {
    echo $e->getMessage();
    var_dump($e->getRaw());
    exit;
} catch (\Payment\Exceptions\ClassNotFoundException $e) {
    echo $e->getMessage();
    exit;
} catch (Exception $e) {
    echo $e->getMessage();
    exit;
}

echo $res;// 这里如果直接输出到页面，&not 会被转义，请注意
