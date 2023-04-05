<?php

namespace carono\exchange1c\interfaces;

interface CustomDocumentInterface extends ExportFieldsInterface
{
    /**
     * Список кастомных документов с сайта
     *
     * @return CustomDocumentInterface[]
     */
    public static function findCustomDocuments1c();

    public function getPrimaryKey();
}
