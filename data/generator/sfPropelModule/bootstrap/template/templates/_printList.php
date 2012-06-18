[?php if ($pager->getNbResults()): ?]
  <div class="">
    <table cellspacing="0" class="table table-admin table-list-print">
      <thead>
        <tr>
          [?php include_partial('<?php echo $this->getModuleName() ?>/print_list_th_<?php echo $this->configuration->getValue('list.layout') ?>', array('sort' => $sort)) ?]
        </tr>
      </thead>
      <tbody>
        [?php foreach ($pager->getResults() as $i => $<?php echo $this->getSingularName() ?>): $odd = fmod(++$i, 2) ? 'odd' : 'even' ?]
          <tr class="sf_admin_row [?php echo $odd ?]">
            [?php include_partial('<?php echo $this->getModuleName() ?>/list_td_<?php echo $this->configuration->getValue('list.layout') ?>', array('<?php echo $this->getSingularName() ?>' => $<?php echo $this->getSingularName() ?>)) ?]
          </tr>
        [?php endforeach; ?]
      </tbody>
    </table>
    </div>
[?php endif; ?]