<?php

namespace Xuchen\FormValidation;

trait Rules
{
    /**
     * 检查参数是否存在
     *
     * @param string $field
     * @param bool $nullable 是否允许参数为null
     * @return bool
     */
    protected function fieldRequired($field, $nullable = false)
    {
        if (!isset($this->_form_params[$field])) {
            return false;
        }
        $param = $this->getFormParam($field, null);
        if ($param === null) {
            if ($nullable === 'true') {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    /**
     * 检查参数是否不为零
     *
     * @param string $field
     * @return bool
     */
    protected function fieldNotZero($field)
    {
        $param = $this->getFormParam($field, null, 'intval');
        if ($param === null) {
            return true;
        }

        if (!$param) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 检查参数是否不为空
     * - 参数使用empty()函数返回真则返回错误
     * - 注意当参数为null时返回值为true
     *
     * @param string $field
     * @return bool
     */
    protected function fieldNotEmpty($field)
    {
        $param = $this->getFormParam($field, null);
        if ($param === null) {
            return true;
        }

        return !empty($param);
    }

    /**
     * 检查参数是否为整数
     *
     * @param $field
     * @return bool
     */
    protected function fieldInteger($field)
    {
        $param = $this->getFormParam($field, null);
        if ($param === null) {
            return true;
        }

        if (!preg_match('/^\-{0,1}\d+$/', $param)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 检查参数是否符合手机号格式
     *
     * @param $field
     * @return bool
     */
    protected function fieldMobile($field)
    {
        $param = $this->getFormParam($field, null);
        if ($param === null) {
            return true;
        }

        if (!$param || !preg_match('/^1\d{10}$/', $param)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 检查字符串长度是否超过最大值
     * 使用strlen()获取字符串长度
     *
     * @param $field
     * @param int $length 长度，默认为0不检查
     * @return bool
     */
    protected function fieldStrlenMax($field, $length)
    {
        $param = $this->getFormParam($field, null);
        if ($param === null) {
            return true;
        }

        if (strlen($param) > $length) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 检查字符串长度是否小于最小值
     * 使用strlen()获取字符串长度
     *
     * @param $field
     * @param int $length 长度，默认为0不检查
     * @return bool
     */
    protected function fieldStrlenMin($field, $length = 0)
    {
        if ($length == 0) return true;
        $param = $this->getFormParam($field, null);
        if ($param === null) {
            return true;
        }

        if (strlen($param) < $length) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 检查字符串长度是否超过最大值
     * 使用mb_strlen()获取字符串长度
     *
     * @param $field
     * @param int $length 长度，默认为0不检查
     * @return bool
     */
    protected function fieldStrlenMaxZh($field, $length = 0)
    {
        if ($length == 0) return true;
        $param = $this->getFormParam($field, null);
        if ($param === null) {
            return true;
        }

        if (mb_strlen($param) > $length) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 检查字符串长度是否小于最小值
     * 使用mb_strlen()获取字符串长度
     *
     * @param $field
     * @param int $length 长度，默认为0不检查
     * @return bool
     */
    protected function fieldStrlenMinZh($field, $length = 0)
    {
        if ($length == 0) return true;
        $param = $this->getFormParam($field, null);
        if ($param === null) {
            return true;
        }

        if (mb_strlen($param) < $length) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 检查邮件地址的格式
     *
     * @param $field
     * @return bool
     */
    protected function fieldEmail($field)
    {
        $param = $this->getFormParam($field, null);
        if ($param === null) {
            return true;
        }

        if (!$param || !preg_match('/^(\w)+(\.\w+)*@(\w)+((\.\w+)+)$/', $param)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 检查QQ号格式
     * 是否为5位以上的纯数字
     *
     * @param $field
     * @return bool
     */
    protected function fieldQq($field)
    {
        $param = $this->getFormParam($field, null);
        if ($param === null) {
            return true;
        }

        if (!$param || !preg_match('/^\w{5,}$/', $param)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 检查银行账号格式
     *
     * @param $field
     * @return bool
     */
    protected function fieldBankAccount($field)
    {
        $param = $this->getFormParam($field, null);
        if ($param === null) {
            return true;
        }

        if (!$param || !preg_match('/^[\d]{16,19}$/', $param)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 检查数字的范围
     * - `1,10` : 检查数字是否在1到10（可以等于）之间
     * - `,10`  : 检查数字是否小于等于10
     * - `1,`   : 检查数字是否大于等于1
     *
     * @param $field
     * @param $start
     * @param $end
     * @return bool
     */
    protected function fieldNumberRange($field, $start = '', $end = '')
    {
        $param = $this->getFormParam($field, null);
        if ($param === null) {
            return true;
        }

        if ($start === '' && $end === '') {
            $this->setErrorInfo(500, '请检查验证器的取值范围');
            return false;
        }

        // 检查最大值
        if ($start === '' && $param > $end) {
            if ($param > $end) {
                return false;
            } else {
                return true;
            }
        // 检查最小值
        } else if ($end === '') {
            if ($param < $start) {
                return false;
            } else {
                return true;
            }
        // 检查范围
        } else if ($param < $start || $param > $end) {
            return false;
        }

        return true;
    }

    /**
     * 检查数字是否小于最大值
     * @param $field
     * @param $end
     * @return bool
     */
    protected function fieldMax($field, $end)
    {
        return $this->fieldNumberRange($field, '', $end);
    }

    /**
     * 检查数字是否大于最小值
     * @param $field
     * @param $start
     * @return bool
     */
    protected function fieldMin($field, $start)
    {
        return $this->fieldNumberRange($field, $start, '');
    }

    /**
     * 检查日期是否在范围内
     * 此方法等效于同时使用AfterOrEqualDate及BeforeOrEqualDate
     *
     * @param $field
     * @param $start
     * @param $end
     * @return bool
     */
    protected function fieldDateRange($field, $start, $end)
    {
        $param = $this->getFormParam($field, null);
        if ($param === null) {
            return true;
        }

        $param = strtotime($param);
        $start = strtotime($start);
        $end = strtotime($end);
        if ($param < $start || $param > $end) {
            return false;
        }
        return true;
    }

    /**
     * 检查日期是否在设定的日期之前（不等于）
     *
     * @param $field
     * @param $start_date
     * @return bool
     */
    protected function fieldBeforeDate($field, $start_date)
    {
        $param = $this->getFormParam($field, null);
        if ($param === null) {
            return true;
        }

        $param = strtotime($param);
        $start = strtotime($start_date);
        if ($param > $start) {
            return false;
        }
        return true;
    }

    /**
     * 检查日期是否小于等于设定的日期
     *
     * @param $field
     * @param $start_date
     * @return bool
     */
    protected function fieldBeforeOrEqualDate($field, $start_date)
    {
        $param = $this->getFormParam($field, null);
        if ($param === null) {
            return true;
        }

        $param = strtotime($param);
        $start = strtotime($start_date);
        if ($param >= $start) {
            return false;
        }
        return true;
    }

    /**
     * 检查日期是否在设定的日期之后（不等于）
     *
     * @param $field
     * @param $start_date
     * @return bool
     */
    protected function fieldAfterDate($field, $start_date)
    {
        $param = $this->getFormParam($field, null);
        if ($param === null) {
            return true;
        }

        $param = strtotime($param);
        $start_date = strtotime($start_date);
        if ($param < $start_date) {
            return false;
        }
        return true;
    }

    /**
     * 检查日期是否大于等于设定的日期
     *
     * @param $field
     * @param $start_date
     * @return bool
     */
    protected function fieldAfterOrEqualDate($field, $start_date)
    {
        $param = $this->getFormParam($field, null);
        if ($param === null) {
            return true;
        }

        $param = strtotime($param);
        $start_date = strtotime($start_date);
        if ($param < $start_date) {
            return false;
        }
        return true;
    }

    /**
     * 检查值是否在某个数组内
     *
     * @param $field
     * @param array ...$values
     * @return bool
     */
    protected function fieldInArray($field, ...$values)
    {
        $param = $this->getFormParam($field, null);
        if ($param === null) {
            return true;
        }

        if (!in_array($param, $values)) {
            return false;
        }
        return true;
    }

    /**
     * 检查值是否不在某个数组内
     *
     * @param $field
     * @param array ...$values
     * @return bool
     */
    protected function fieldNotInArray($field, ...$values)
    {
        $param = $this->getFormParam($field, null);
        if ($param === true) {
            return true;
        }

        if (in_array($param, $values)) {
            return false;
        }
        return true;
    }

    /**
     * 检查是否为IP地址
     * @param $field
     * @return bool
     */
    protected function fieldIpAddress($field)
    {
        $param = $this->getFormParam($field, null);
        if (!$param) {
            return true;
        }

        $address_array = explode('.', strval($param));
        if (!count($address_array) != 4) {
            return false;
        }

        foreach ($address_array as $value) {
            if ($value < 0 || $value > 255) {
                return false;
            }
        }

        return true;
    }
}
