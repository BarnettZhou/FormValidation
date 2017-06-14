# FormValidation

为Lumen准备的一个表单验证插件

## Installation

项目目录中运行如下命令

```
composer require xuchen/form-validation
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

class CopartnerValidation extends Validation
{
    /**
     * 通过ClassName::init()的方式调用一个类的实例化
     * 若使用依赖注入的方式调用则无须定义该方法
     *
     * @param string $className 当前类名
     */
    public static function init($className = __CLASS__)
    {
        return parent::init($className);
    }

    public function test()
    {
        echo 'my validation works';die;
    }

    /**
     * 字段验证规则
     */
    protected function fieldsValidationRules()
    {
        return [
            'real_name' => function() {
                return ['required' => '真实姓名必填'];
            },
            'mobile' => function() {
                return [
                    'required'  => '手机号必填',
                    'mobile'    => '手机号格式不正确',
                ];
            },
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
            return ['success' => false, 'error_msg' => $validation->getErrorMsg()];
        } else {
            return ['success' => true, 'error_msg' => ''];
        }
    }
}
```

## Author

Xuchen Zhou, [zhouxuchen1993@foxmail.com](mailto:zhouxuchen1993@foxmail.com)

## License

xuchen/form-validation is available under the Apache license.
