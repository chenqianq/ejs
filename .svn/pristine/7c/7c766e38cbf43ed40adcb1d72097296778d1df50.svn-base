<?php
class SettingController extends ZcController
{
    private $adminService;
    private $adminInfo;
    private $systemService;
    private $settingLogService;
    private $urlHelper;

    public function __construct($route)
    {
        parent::__construct($route);
        $this->adminService = ServiceFactory::getAdminService();
        $this->adminInfo = $this->adminService->getAdminInfo();
        $this->systemService = ServiceFactory::getSystemService();
        $this->settingLogService = ServiceFactory::getSettingLogService();
        $this->urlHelper = HelperFactory::getUrlHelper();

    }

    /**
     * 参数列表
     */
    public function settingList()
    {
        // 保税
        $bondedSettingNameArray = [
            'bonded_order_amount_limit', // 保税单次限购金额(元)
            'bonded_sku_limit', // 保税限购数量
            'bonded_shipping_fee', // 保税默认运费
            'bonded_no_shipping_fee_amount_limit', // 保税默认包邮门槛
        ];
        // 直邮
        $directMailSettingNameArray = [
            'direct_mail_order_amount_limit',
            'direct_mail_sku_limit',
            'direct_mail_shipping_fee',
            'direct_mail_no_shipping_fee_amount_limit',
        ];

        $mergeSettingNameArray = array_merge($bondedSettingNameArray, $directMailSettingNameArray);

        // 参数修改提交
        if ($this->urlHelper->getValue('is_submit') == 'ok') {
            $settingName = $this->urlHelper->getValue('setting_name');
            $settingValue = $this->urlHelper->getValue('setting_value');
            $settingTitle = $this->urlHelper->getValue('setting_title');

            $validate = HelperFactory::getValidateHelper();
            if (in_array($settingName, ['bonded_sku_limit', 'direct_mail_sku_limit'])) {
                $validate->setDefaultValidate($settingValue, 'number', '该参数必须是整数');
            } else {
                $validate->setCustomValidate($settingValue, "/^\d+(\.\d{1,2})?$/u", '该参数必须支持两位小数');
            }
            if ($error = $validate->validate()) {
                $this->renderJSON(['status' => 'failed', 'msg' => $error]);
            }
            $updateData = [
                'value' => $settingValue,
                'gmt_modified' => date('Y-m-d H:i:s'),
            ];
            $res = $this->systemService->updateSettingInfoByName($updateData, $settingName);
            if ($res === false) {
                $this->renderJSON(['status' => 'failed', 'msg' => '修改失败']);
            }

            $this->settingLogService->createSettingLog($settingName, $settingValue, $settingTitle, $this->adminInfo['name']);

            $this->renderJSON(['status' => 'success', 'msg' => '修改成功']);
        }

        // 获取参数信息
        $settingInfoArray = $this->systemService->getSettingListByNameArray($mergeSettingNameArray);

        foreach ($settingInfoArray as $settingInfo) {
            if (in_array($settingInfo['name'], $bondedSettingNameArray)) {
                $bondedSettingArray[$settingInfo['name']] = $settingInfo;
            } else {
                $directMailSettingArray[$settingInfo['name']] = $settingInfo;
            }
        }

        $settingParamArray = [
            '保税' => $bondedSettingArray,
            '直邮' => $directMailSettingArray,
        ];

        // 日志列表
        $logArray = $this->settingLogService->getSettingLogByNameArray($mergeSettingNameArray, 30);

        $renderData = [
            'settingParamArray' => $settingParamArray,
            'logArray' => $logArray,
        ];
        $this->render($renderData);
    }

}