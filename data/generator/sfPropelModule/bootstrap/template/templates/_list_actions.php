<?php if ($actions = $this->configuration->getValue('list.actions')): ?>
    [?php slot('submenu') ?]
        <div class="subnav subnav-fixed no-print">
            <div class="container">
                <ul class="nav nav-pills nav-reservation nav-reservation-date pull-left">

                    <?php foreach ($actions as $name => $params): ?>
                        <?php if ('_new' == $name): ?>
                            <?php echo $this->addCredentialCondition('[?php echo $helper->linkToNew('.$this->asPhp($params).') ?]', $params)."\n" ?>
                        <?php elseif ('filters' == $name): ?>
                            <?php echo $this->addCredentialCondition('[?php echo $helper->linkToFilters('.$this->asPhp($params).') ?]', $params)."\n" ?>
                        <?php elseif ('reset' == $name): ?>
                            [?php  if(isset($showResetFilter) && $showResetFilter){ ?]
                                <?php echo $this->addCredentialCondition('[?php echo $helper->linkToreset('.$this->asPhp($params).') ?]', $params)."\n" ?>
                            [?php }?]
                        <?php elseif ('print' == $name): ?>
                                <?php echo $this->addCredentialCondition('[?php echo $helper->linkToPrint() ?]', $params)."\n" ?>
                        <?php else: ?>
                            <li class="sf_admin_action_<?php echo $params['class_suffix'] ?>">
                              <?php echo $this->addCredentialCondition($this->getLinkToAction($name, $params, false), $params)."\n" ?>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
              </ul>
              [?php if ($pager->haveToPaginate()): ?]
                  <ul class="nav nav-pills nav-reservation nav-reservation-date pull-right">
                      <li><div class="sf_admin_pagination">
                          [?php include_partial('<?php echo $this->getModuleName() ?>/pagination', array('pager' => $pager)) ?]
                      </div></li>
                  </ul>
            [?php endif; ?]
            </div>
        </div>
    [?php end_slot() ?]
<?php endif; ?>
