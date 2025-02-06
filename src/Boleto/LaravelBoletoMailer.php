<?php

namespace Wlrsilveira\LaravelBoletos\Boleto;

use Exception;
use Illuminate\Mail\Mailer;
use Illuminate\Foundation\Application;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Container\ContainerExceptionInterface;
use Wlrsilveira\LaravelMailAutoEmbed\Listeners\SymfonyEmbedImages;
use Wlrsilveira\LaravelMailAutoEmbed\Contracts\Listeners\EmbedImages;

class LaravelBoletoMailer extends Mailer
{
    /**
     * @param $message
     * @param $data
     * @return bool
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function shouldSendMessage($message, $data = [])
    {
        if (self::isLaravel9Plus() && ! app()->bound(EmbedImages::class)) {
            try {
                (new SymfonyEmbedImages(config()->get('mail-auto-embed')))->handle($message);
            }
            catch (Exception $e){}
        }

        return parent::shouldSendMessage($message, $data);
    }

    /**
     * @return bool|int
     */
    public static function isLaravel9Plus()
    {
        return version_compare(Application::VERSION, '9.0.0', '>=');
    }
}
