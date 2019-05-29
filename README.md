<p align="center">
    <a href="https://opensource.org/licenses/MIT"><img src="https://img.shields.io/badge/License-MIT-green.svg" alt="MIT"></a>
    <a href="https://travis-ci.org/Bu4ak/telegram-notifier-lite-client"><img src="https://travis-ci.org/Bu4ak/telegram-notifier-lite-client.svg?branch=master" alt="Build Status"></a>
    <a href="https://codeclimate.com/github/Bu4ak/telegram-notifier-lite-client/maintainability"><img src="https://api.codeclimate.com/v1/badges/b9a870be87f14eccf917/maintainability" /></a>
    <a href="https://codeclimate.com/github/Bu4ak/telegram-notifier-lite-client/test_coverage"><img src="https://api.codeclimate.com/v1/badges/b9a870be87f14eccf917/test_coverage" /></a>
    <a href="https://styleci.io/repos/126356334"><img src="https://styleci.io/repos/126356334/shield?branch=master" alt="StyleCI"></a>
</p>

You do not need to register a bot or making other difficult actions. Start receiving notifications in two easy steps.
#### How to use:
* `composer require bu4ak/telegram-notifier-lite-client`
* Start a dialogue with the bot [Notifier](https://telegram.me/notificator_lite_bot) (@notificator_lite_bot), or invite him to your telegram channel. He will send you a token.
```php
use Bu4ak\TelegramNotifierLite\TelegramNotifierLite;

$defaultChannel = 'XXXXXXXXXXXXXXXX';
$debugChannel   = 'YYYYYYYYYYYYYYYY';

$notifier = new TelegramNotifierLite($defaultChannel);
$notifier->send('Wake up! You have a new order!');
...
$notifier->send(['New feedback' => 'Hello how are you I am under the water please help me']);
...
} catch (Exception $exception) {
    $notifier->send($exception->getMessage(), $debugChannel);
}
```

#### Using with Laravel:
* `composer require bu4ak/telegram-notifier-lite-client`
* If you using Laravel `without auto-discovery`, add it manually to config/app.php:
```php
 'providers' => [
    ...
    TelegramNotifierLiteServiceProvider::class
    ...
 ]
```
 * `php artisan vendor:publish --provider="Bu4ak\TelegramNotifierLite\TelegramNotifierLiteServiceProvider"`
 * add row to .env file:
 ```php
TELEGRAM_NOTIFIER_LITE_TOKEN=YOURTOKEN
```
##### Send message:
```php
app(TelegramNotifierLite::class)->send(['your data'=>123]);
//or
class RandomController extends BaseController
{
    public function randomMethod(TelegramNotifierLite $notifierLite)
    {
        $this->notifier->send('random message', 'ANOTHER_CHANNEL_TOKEN');
    }
}
```
