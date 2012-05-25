<?php foreach ($this->configuration->getValue('list.display') as $name => $field): ?>
        <?php $config = $field->getConfig(); $isDate = strtolower($field->getType()) == 'date'?>
<?php echo $this->addCredentialCondition(sprintf(<<<EOF
<td class="sf_admin_%s sf_admin_list_td_%s ">
  [?php echo %s ?]
</td>

EOF
, strtolower($field->getType().($isDate ? ' no-wrap-line' : '')), $name, $this->renderField($field)), $config) ?>
<?php endforeach; ?>
