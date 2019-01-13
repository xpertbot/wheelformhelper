<?php
namespace wheelformhelper;

use wheelform\Mailer;
use yii\base\Event;
use wheelform\controllers\MessageController;

class Plugin extends \craft\base\Plugin
{

    public function init()
    {
        parent::init();

        Event::on(MessageController::class, MessageController::EVENT_BEFORE_SAVE, function($event){
            // $field is an ActiveRecord Object
            foreach($event->message as $field) {
                if($field->field->name == "message") {
                    $field->value = "Custom Value from event";
                }
            }
        });

        Event::on(Mailer::class, Mailer::EVENT_BEFORE_SEND, function($event)
        {
            //Add extra fields to message
            $event->message['extra_field'] = [
                'label' => 'New Label',
                'value' => 'Lorem Ipsum',
                'type' => "text"
            ];

            //Add static Reply_to email
            $event->reply_to = 'reply@example.com';

            //Add reply_to based on form field
            if($event->form_id == 1) {
                if(! empty($event->message['email']['value'])) {
                    $event->reply_to = $event->message['email']['value'];
                }
            }

            //Conditional To Emails
            //If user selected Chocolate as favorite 'flavour' field. (this can be radio, select, text, etc)
            //It's a good idea to add more checks such as form ID and valid email.
            if(array_key_exists('flavour', $event->message)) {
                if($event->message['flavour']['value'] == 'Chocolate' ) {
                    $event->to[] = 'chocolate@example.com';
                }
            }

            //If user selected specific 'toppings' in a checkbox field.
            if (array_key_exists('toppings', $event->message)) {
                if (is_array($event->message['toppings'])) {
                    if(in_array('Fudge', $event->message['toppings']['value'])) {
                        $event->to[] = 'fudge@example.com';
                    }
                }
            }
        });
    }
}
