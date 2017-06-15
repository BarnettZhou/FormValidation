<?php

namespace Xuchen\FormValidation;


class Validation extends CommonValidation
{
    /**
     * 检查参数是否存在且不为空
     *
     * @param string $field
     * @return bool
     */
    protected function fieldRequired($field)
    {
        $param = $this->getFormParam($field, '');
        if (!$param) {
            return false;
        } else {
            return true;
        }
    }

    protected function fieldMobile($field)
    {
        $param = $this->getFormParam($field, '');
        if (!$param || !preg_match('/^1[3|5|7|8]\d{9}$/', $param)) {
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
    protected function fieldMax($field, $length)
    {
        $param = $this->getFormParam($field, '');
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
    protected function fieldMin($field, $length = 0)
    {
        if ($length == 0) return true;
        $param = $this->getFormParam($field, '');
        if (strlen($param) < $length) {
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
    protected function fieldMaxzh($field, $length = 0)
    {
        if ($length == 0) return true;
        $param = $this->getFormParam($field, '');
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
    protected function fieldMinzh($field, $length = 0)
    {
        if ($length == 0) return true;
        $param = $this->getFormParam($field, '');
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
        $param = $this->getFormParam($field, '');
        if (!$param || !preg_match('/^(\w)+(\.\w+)*@(\w)+((\.\w+)+)$/', $param)) {
            return false;
        } else {
            return false;
        }
    }

    /**
     * 检查QQ号格式
     *
     * @param $field
     * @return bool
     */
    protected function fieldQq($field)
    {
        $param = $this->getFormParam($field, '');
        if (!$param || !preg_match('/^\w{5,}$/', $param)) {
            return false;
        } else {
            return true;
        }
    }
}
