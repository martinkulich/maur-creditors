<div class="sf_admin_pagination">
  <div class="btn-toolbar">
   <div class="btn-group">
  <a href="[?php echo url_for('@<?php echo $this->getUrlForAction('list') ?>') ?]?page=1" class="btn">
    &nbsp;<i class="icon-fast-backward"></i>&nbsp;
  </a>

  <a href="[?php echo url_for('@<?php echo $this->getUrlForAction('list') ?>') ?]?page=[?php echo $pager->getPreviousPage() ?]" class="btn">
    &nbsp;<i class="icon-step-backward"></i>&nbsp;
  </a>
  </div>
  <div class="btn-group">
  [?php foreach ($pager->getLinks() as $page): ?]
      <a href="[?php echo url_for('@<?php echo $this->getUrlForAction('list') ?>') ?]?page=[?php echo $page ?]" class="btn [?php if ($page == $pager->getPage()) echo 'active' ?]">[?php echo $page ?]</a>
  [?php endforeach; ?]
  </div>
    <div class="btn-group">
      <a href="[?php echo url_for('@<?php echo $this->getUrlForAction('list') ?>') ?]?page=[?php echo $pager->getNextPage() ?]" class="btn">
          &nbsp;<i class="icon-forward"></i>&nbsp;
      </a>

      <a href="[?php echo url_for('@<?php echo $this->getUrlForAction('list') ?>') ?]?page=[?php echo $pager->getLastPage() ?]" class="btn">
        &nbsp;<i class="icon-fast-forward"></i>&nbsp;
      </a>
</div>
  </div>
</div>
