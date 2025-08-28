<?php

namespace carono\exchange1c\behaviors;

use Yii;
use yii\base\ActionFilter;
use yii\web\Response;

class BomBehavior extends ActionFilter
{
    public function beforeAction($action)
    {
        $response = Yii::$app->getResponse();
        $response->on(Response::EVENT_AFTER_PREPARE, [$this, 'addBomToResponse'],
            ['module' => $action->controller->module]);

        return parent::beforeAction($action);
    }

    public function addBomToResponse($event)
    {
        $response = $event->sender;

        if (is_string($response->content)) {
            $xml = chr(0xEF) . chr(0xBB) . chr(0xBF) . $response->content;

            if (isset($event->data['module']) && $event->data['module']->encodeQueryResponse) {
                $xml = html_entity_decode($xml, ENT_QUOTES | ENT_HTML5 | ENT_COMPAT, 'UTF-8');
            }

            $response->content = $this->minifyXml($xml);
        }

        $response->headers->set('Content-Type', 'text/xml; charset=UTF-8');
    }

    private function minifyXml(string $xmlString): string
    {
        $xmlString = preg_replace('/>\s+</', '><', $xmlString);

        return trim($xmlString);
    }
}