<?php

/*
 * The file is part of the payment lib.
 *
 * (c) Leo <dayugog@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Payment\Gateways\Alipay;

use Payment\Contracts\IGatewayRequest;
use Payment\Exceptions\GatewayException;
use Payment\Helpers\ArrayUtil;

/**
 * @package Payment\Gateways\Alipay
 * @author  : Leo
 * @email   : dayugog@gmail.com
 * @date    : 2019/3/28 10:21 PM
 * @version : 1.0.0
 * @desc    : app 支付
 **/
class SignCharge extends AliBaseObject implements IGatewayRequest
{
    // 这个操作是在客户端发起的，服务端只负责组装参数
    const METHOD = 'alipay.user.agreement.page.sign';

    /**
     * 获取第三方返回结果
     * @param array $requestParams
     * @return mixed
     * @throws GatewayException
     */
    public function request(array $requestParams)
    {
        try {
            $params = $this->buildParams(self::METHOD, $requestParams);
//            return http_build_query($params);
            return sprintf('%s?%s', $this->gatewayUrl, http_build_query($params));

        } catch (GatewayException $e) {
            throw $e;
        }
    }

    /**
     * 构建请求参数
     * @param array $requestParams
     * @return mixed
     */
    protected function getBizContent(array $requestParams)
    {
        $timeoutExp = '';
        $timeExpire = intval($requestParams['time_expire']);
        if (!empty($timeExpire)) {
            $expire = floor(($timeExpire - time()) / 60);
            ($expire > 0) && $timeoutExp = $expire . 'm';// 超时时间 统一使用分钟计算
        }

        $bizContent = [
            'product_code' => $requestParams['product_code'] ?? '',
            'external_logon_id' => $requestParams['external_logon_id'] ?? '',
            'promo_params' => $requestParams['promo_params'] ?? '',
            'sign_scene' => $requestParams['sign_scene'] ?? '',
            'external_agreement_no' => $requestParams['external_agreement_no'] ?? '',
            'third_party_type' => $requestParams['third_party_type'] ?? '',
            'sign_validity_period' => $requestParams['sign_validity_period'] ?? '',
            'zm_auth_params' => $requestParams['zm_auth_params'] ?? '',
            'prod_params' => $requestParams['prod_params'] ?? '',
            'sub_merchant' => $requestParams['sub_merchant'] ?? '',
            'device_params' => $requestParams['device_params'] ?? '',
            'merchant_process_url' => $requestParams['merchant_process_url'] ?? '',
            'identity_params' => $requestParams['identity_params'] ?? '',
            'agreement_effect_type' => $requestParams['agreement_effect_type'] ?? '',
            'user_age_range' => $requestParams['user_age_range'] ?? '',
            'pass_params' => $requestParams['pass_params'] ?? '',
            'specified_sort_channel_params' => $requestParams['specified_sort_channel_params'] ?? '',
            // 使用禁用列表
            //'enable_pay_channels' => '',
            'personal_product_code' => $requestParams['personal_product_code'] ?? '',
            'access_params' => $requestParams['access_params'] ?? '',
            'period_rule_params' => $requestParams['period_rule_params'] ?? '',
            'specified_asset' => $requestParams['specified_asset'] ?? '',
        ];
        $bizContent = ArrayUtil::paraFilter($bizContent);

        return $bizContent;
    }
}
