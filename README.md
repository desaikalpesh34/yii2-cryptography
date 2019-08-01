yii2-cryptography 
==================
yii2-cryptography automate encryption/decription

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist desaikalpesh34/yii2-cryptography "*"
```

or add

```
"desaikalpesh34/yii2-cryptography": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :

Add the following lines in the ```components``` section of your config file.

```
'crypto'=> [
		    'class'=>'class'=>'\cryptography\components\Crypto',
		    'secrateKey'=>'SecrateKeyGoesHere',
	    ],
```

Basic Usage
-----

You can now use the component manually in any part of the application to either encrypt data

```
\Yii::$app->crypto->encrypt('data to encrypt');
```

or decrypt and encrypted data

```
\Yii::$app->crypto->decrypt('data to decrypt');
```


Behavior
--------

The extension also comes with a behavior that you can easily attach to any ActiveRecord Model.

Use the following syntax to attach the behavior.

```
public function behaviors()
{
    return [
        'encryption' => [
            'class' => '\cryptography\behaviors\CryptographicBehavior',
            'attributes' => [
                'column1',
                'column2',
				'column3',
				   .
				   .
				   .
            ],
        ],
    ];
}
```

The behavior will automatically encrypt all the data before saving it on the database and decrypt it after the retrieve.