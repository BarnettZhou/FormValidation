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

    protected function fieldMax($field, $length)
    {
        $param = $this->getFormParam($field, '');
        if (strlen($param) > $length) {
            return false;
        } else {
            return true;
        }
    }
}
