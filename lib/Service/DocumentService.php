<?php

class DocumentService
{

    public function getDocumentRootDirPath()
    {
        return sfConfig::get('sf_root_dir').DIRECTORY_SEPARATOR.'uploads';
    }

    public function getContractDocumentPath(Contract $contract)
    {
        return $this->getDocumentRootDirPath().DIRECTORY_SEPARATOR.$contract->getDocument();
    }

    public function getContractDocumentExtension(Contract $contract)
    {
        $document = $contract->getDocument();

        $parts = explode('.', $document);

        return end($parts); 
    }
}
