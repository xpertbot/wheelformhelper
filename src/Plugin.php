<?php
namespace wheelformhelper;

use wheelform\Mailer;
use yii\base\Event;

class Plugin extends \craft\base\Plugin
{

    public function init()
    {
        parent::init();

        Event::on(Mailer::class, Mailer::EVENT_BEFORE_SEND, function($event)
        {
            $event->message['extra_field'] = [
                'label' => 'New Label',
                'value' => 'Please Respond',
                'type' => "text"
            ];
        });
    }
}
