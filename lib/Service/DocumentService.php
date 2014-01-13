<?php

class DocumentService
{

    public function getDocumentRootDirPath()
    {
        return sfConfig::get('sf_upload_dir');
    }

    public function getDocumentPath(Document $document)
    {
        return $this->getDocumentRootDirPath().DIRECTORY_SEPARATOR.$document->getSrc();
    }

    public function getDocumentExtension(Document $document)
    {
        $src = $document->getSrc();

        $parts = explode('.', $src);

        return end($parts); 
    }
}
