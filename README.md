# FormValidation

为Lumen准备的一个表单验证插件

## Require

``` javascript
{
    "require": {
        "php": ">=5.6.0",
        "ext-mbstring": "*"
    }
}
```

## Installation

项目目录中运行如下命令

```
composer require xuchen/form-validation:dev-master
```

或在项目目录composer.json的require中加入

```
"require": {
    "xuchen/form-validation": "dev-master"
}
```

并运行

```
composer update
```

## Usage

在Lumen项目的`app`目录下新建`MyValidation.php`文件（你也可以放在Validations目录中，注意命名空间的对应）

``` php
<?php

namespace App;


use Xuchen\FormValidation\Validation;

class MyValidation extends Validation
{
    /**
     * 字段验证规则
     */
    protected function fieldsValidationRules()
    {
        return [
            // 使用内置的方法验证字段
            'real_name' => function() {
                // 返回的数组item为`验证方法` => `验证失败时需要返回的错误信息`
                return ['required' => '真实姓名必填'];
            },
            'mobile' => function() {
                return [
                    'required'  => '手机号必填',
                    'mobile'    => '手机号格式不正确',
                ];
            },
            'content' => function() {
                return [
                    'maxzh:255' => '内容不能超过255个字符',
                ];
            },
            // 使用自定义的回调方法验证字段，返回false时表示验证失败
            'type' => function() {
                $type = $this->getFormParam('type', 'link', 'strval');
                if (!in_array($type, ['city', 'academy', 'link'])) {
                    $this->_error_msg = '请检查类型';
                    return false;
                }
            }
        ];
    }
}

```

在控制器中调用验证方法

``` php
<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\MyValidation;
use Illuminate\Http\Request;

class MyController extends Controller
{
    public function testPostRoute(Request $request, MyValidation $validation)
    {
        if (!$validation->setFormParams($request->all())->validateFields()) {
            // 使用Validation::getErrorMsg()获取错误信息
            return ['success' => false, 'error_msg' => $validation->getErrorMsg()];
        } else {
            return ['success' => true, 'error_msg' => ''];
        }
    }
}
```

更多文档见[wiki页面](https://github.com/BarnettZhou/FormValidation/wiki)。

## Author

Xuchen Zhou, [zhouxuchen1993@foxmail.com](mailto:zhouxuchen1993@foxmail.com)

## License

xuchen/form-validation is available under the Apache license.
