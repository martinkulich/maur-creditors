    public function executePrintList(sfWebRequest $request)
    {
        $this->pager = $this->getPager();
        $this->sort = $this->getSort();
        ServiceContainer::getPdfService()->generatePdf('<?php echo $this->getSingularName() ?>List.pdf', '<?php echo $this->getSingularName() ?>', 'printList', array('pager'=>$this->pager, 'sort'=>$this->sort, 'helper'=>$this->helper));
    }
