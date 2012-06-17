  public function getPagerClass()
  {
    return '<?php echo isset($this->config['list']['pager_class']) ? $this->config['list']['pager_class'] : 'sfPropelPager' ?>';
<?php unset($this->config['list']['pager_class']) ?>
  }

  public function getPagerMaxPerPage()
    {
        return 0;
    }
