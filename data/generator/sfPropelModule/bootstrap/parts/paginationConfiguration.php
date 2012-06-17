  public function getPagerClass()
  {
    return '<?php echo isset($this->config['list']['pager_class']) ? $this->config['list']['pager_class'] : 'sfPropelPager' ?>';
<?php unset($this->config['list']['pager_class']) ?>
  }

  public function getPagerMaxPerPage()
    {
        $request = sfContext::getInstance()->getRequest();
        $filters = sfContext::getInstance()->getUser()->getAttribute('<?php echo $this->getModuleName()?>.filters', array(), 'admin_module');
        $perPage = isset($filters['per_page']) ? $filters['per_page'] : 20;
        $perPage = $request->getParameter('per_page',$perPage);
        return $perPage;
    }
