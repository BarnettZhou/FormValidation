<?php

namespace Xuchen\FormValidation;

class CommonValidation
{
    /**
     * @var int $_error_code    错误码
     * @var $_error_msg         错误信息
     * @var $error_key          错误信息KEY，用于log保存
     */
    protected $_error_code  = 0;
    protected $_error_msg   = '';
    protected $_error_key   = '';

    /**
     * @var array 请求的所有表单数据
     */
    protected $_form_params = [];

    const MODE_ALL          = 0;    // 字段检查为ALL模式，检查Service定义的所有参数
    const MODE_ONLY         = 1;    // 字段检查为ONLY模式，检查传入$fields参数内所有字段
    const MODE_EXCEPT       = 2;    // 字段检查为EXCEPT模式，检查除了传入的$fields参数内的所有字段

    private static $_instances = [];

    /**
     * 实例化一个子类
     *
     * @param string $className 子类类名
     * @return CommonService $instance
     */
    public static function init($className = __CLASS__)
    {
        if (isset(self::$_instances[$className])) {
            return self::$_instances[$className];
        } else {
            $instance = self::$_instances[$className] = new $className();
            return $instance;
        }
    }

    /**
     * @param array $params 表单数据，使用Request::all()方法获取即可
     * @return $this
     */
    final public function setFormParams($params)
    {
        $this->_form_params = $params;
        return $this;
    }

    /**
     * 设置错误信息
     *
     * @param int $code 错误码
     * @param string $msg  错误信息
     * @param string $key  错误KEY
     * @return $this
     */
    final public function setErrorInfo($code = 0, $msg = '', $key = '')
    {
        $this->_error_code  = $code;
        $this->_error_msg   = $msg;
        $this->_error_key   = $key;
        return $this;
    }

    /**
     * @return array 所有错误信息
     */
    final public function getErrorInfo()
    {
        return [
            'code'  => $this->_error_code,
            'msg'   => $this->_error_msg,
            'key'   => $this->_error_key,
        ];
    }

    /**
     * @return int 返回错误码
     */
    final public function getErrorCode()
    {
        return $this->_error_code;
    }

    /**
     * @return string 返回错误信息
     */
    final public function getErrorMsg()
    {
        return $this->_error_msg;
    }

    /**
     * @return string 返回错误KEY
     */
    final public function getErrorKey()
    {
        return $this->_error_key;
    }

    /**
     * @return array 字段检查规则
     */
    protected function fieldsValidationRules()
    {
        return [];
    }

    /**
     * @param array $fields
     * @param int $mode
     * @return bool
     */
    final public function validateFields($fields = [], $mode = self::MODE_ONLY)
    {
        $_all_fields = array_keys($this->fieldsValidationRules());
        if ($mode == 1 && $fields) {    // 获取$_all_fields与$fields的交集
            $fields = array_intersect($_all_fields, $fields);
        } else if ($mode == 2) {         // 获取$_all_fields与$fields的差集
            $fields = array_diff($_all_fields, $fields);
        } else {                         // 检查所有字段
            $fields = $_all_fields;
        }

        foreach ($fields as $_func_name) {
            $ret = call_user_func($this->fieldsValidationRules()[$_func_name]);
            // 若返回false则直接返回错误
            if ($ret === false) {
                return false;
                // 若返回为数组则根据数组内规则检查字段
            } else if (is_array($ret)) {
                foreach ($ret as $validate_rule => $rule_error_msg) {
                    // 分割rule字符串
                    $rule_array = explode('|', $validate_rule);

                    // 方法名
                    $method_name = '';
                    $method_name_array = explode('-', $rule_array[0]);
                    array_map(function($item) use (&$method_name) { $method_name .= ucfirst($item); }, $method_name_array);
                    $param_list = count($rule_array) > 1? array_splice($rule_array, 1) : [];


                    // 验证方法是否存在
                    if (!method_exists($this, $method_name)) {
                        echo 'method ' . $method_name . ' not exists';die;
                        continue;
                    }
                    // 验证字段
                    if ($this->$method_name($_func_name, ...$param_list) === false) {
                        $this->_error_msg = $rule_error_msg;
                        return false;
                    }
                }
            }
        }

        return true;
    }

    /**
     * 获取表单中参数
     *
     * @param int $key 字段
     * @param string $default 默认值
     * @param string $trans 转换方法，例如intval
     * @return mixed|string 表单参数
     */
    final public function getFormParam($key = 0, $default = '', $trans = '')
    {
        $value = isset($this->_form_params[$key])? $this->_form_params[$key] : $default;
        if ($trans && function_exists($trans)) {
            $value = $trans($value);
        }
        return $value;
    }
}