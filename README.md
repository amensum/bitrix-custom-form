# Bitrix custom form component

__Component calling example for default template:__
```php
$APPLICATION->IncludeComponent(
    "custom:form",
    "",
    array(
        'IBLOCK_ID' => '1',
        'MAIL_EVENT' => 'FORM_SENDED',
        'RECAPTCHA_ENABLED' => 'N',
        'RECAPTCHA_PUBLIC_KEY' => 'GoogleRecaptchaPublicKey',
        'RECAPTCHA_PRIVATE_KEY' => 'GoogleRecaptchaPrivateKey',
        'ACTIVE' => 'Y',
        'TOKEN' => 'form001',
        'FORM_NAME' => 'Form 1',
        'PROPS' => array(
            'NAME', // type - string
            'EMAIL', // type - string
            'PHONE', // type - string
            'SELECT', // type - select
            'CHECKBOX', // type - string
            'DATE', // type - date
            'MESSAGE,TEXT', // type - html/text
            'DOCUMENT,FILE', // type - file
            'DOCUMENTS,FILES' // type - multiple files
        ),
    )
);
```
