<?php
/**
 * Created by PhpStorm.
 * User: igor
 * Date: 2019-03-04
 * Time: 22:29.
 */

namespace Bu4ak\TelegramNotifierLite;

interface TelegramNotifier
{
    /**
     * @param mixed  $data
     * @param string $token
     */
    public function send($data, string $token = ''): void;
}
