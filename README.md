## 1) Generate optimized autoload files for autoload required files like: seeders, etc
    
    Run below command:

    composer dump-autoload

## 2) Run all migrations & seeds
    2.1 ) php artisan migrate
    2.2 ) php artisan db:seed

    OR

    2.1 ) php artisan migrate:fresh --seed

## 3) Run below command for generate required directories
    php artisan directory:create-upload-directories
    /opt/php70/bin/php artisan directory:create-upload-directories

## 4) Create One Symbolic Link :
    
    ln -s /Applications/XAMPP/xamppfiles/htdocs/ethioPay/storage/app/public/uploads/ /Applications/XAMPP/xamppfiles/htdocs/ethioPay/public/
    
    ln -s /home2/wewealth/public_html/t1factory/ethioPay/storage/app/public/uploads/ /home2/wewealth/public_html/t1factory/ethioPay/public/

## 5) Set Cronjob
    * * * * * php /Applications/XAMPP/xamppfiles/htdocs/ethioPay/artisan schedule:run >> /dev/null 2>&1

## 6) Run Queue
    php artisan queue:work --queue=stripe,emails,smsEmailMessage,twilio,default --tries=5

## 7 ) Required extensions
    1 ) GD extension
    2 ) bc math or gmp extension

## 8 ) Twilio Webhook setup
    - Messaging
        -- A MESSAGE COMES IN
            --- On Test
            Webhook -> http://020d36f0.ngrok.io/ethioPay/public/api/twilio-sms-webhook

            --- On Staging
            Webhook -> http://ethiopaycenter.com/api/twilio-sms-webhook