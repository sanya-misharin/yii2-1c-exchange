<?php

namespace carono\exchange1c\behaviors;

use yii\base\ActionFilter;
use yii\web\Response;

class BomBehavior extends ActionFilter
{
    public function beforeAction($action)
    {
        $response = \Yii::$app->getResponse();

        $response->on(Response::EVENT_AFTER_PREPARE, [$this, 'addBomToResponse']);

        return parent::beforeAction($action);
    }

    public function addBomToResponse($event)
    {
        $response = $event->sender;

        if (is_string($response->content)) {
            $bom = chr(0xEF) . chr(0xBB) . chr(0xBF);
            $response->content = $bom . $response->content;
        }

        $response->headers->set('Content-Type', 'text/xml; charset=UTF-8');
    }
}