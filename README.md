# yuncms-summernote-widget

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist yuncms/yuncms-summernote-widget "*"
```

or add

```
"yuncms/yuncms-summernote-widget": "*"
```

to the require section of your `composer.json` file.


Usage
-----

```php
<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;

class MyController extends Controller
{

    /** @inheritdoc */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'upload'
                        ],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'upload' => [
                'class' => 'yuncms\summernote\SummerNoteAction',
                //etc...
            ],
        ];
    }
}
````

Once the extension is installed, simply use it in your code by  :

```php

<?= $form->field($model, 'content')->widget(\yuncms\summernote\SummerNote::className(),[
	//etc...
]) ?>
<?= \yuncms\summernote\SummerNote::widget(); ?>