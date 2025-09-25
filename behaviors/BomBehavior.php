<?php

namespace carono\exchange1c\behaviors;

use http\Encoding\Stream;
use Yii;
use yii\base\ActionFilter;
use yii\web\Response;

class BomBehavior extends ActionFilter
{
    public function beforeAction($action)
    {
        $response = Yii::$app->getResponse();
        $response->on(Response::EVENT_AFTER_PREPARE,
            [$this, 'addBomToResponse'],
            ['module' => $action->controller->module]);

        return parent::beforeAction($action);
    }

    public function addBomToResponse($event)
    {
        $response = $event->sender;

        if (is_string($response->content)) {
            $response->content = $this->minifyXml(chr(0xEF) . chr(0xBB) . chr(0xBF) . $response->content);
        }

        $response->headers->set('Content-Type', 'text/xml; charset=UTF-8');
    }

    private function minifyXml(string $xmlString)
    {
        $xmlString = preg_replace('/>\s+</', '><', $xmlString);

        return trim($xmlString);
    }
}