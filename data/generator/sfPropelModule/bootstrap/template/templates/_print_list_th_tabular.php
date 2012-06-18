<?php foreach ($this->configuration->getValue('list.display') as $name => $field): ?>
    <th class="sf_admin_<?php echo strtolower($field->getType()) ?> sf_admin_list_th_<?php echo $name ?>">
        [?php echo __('<?php echo $field->getConfig('label', '', true) ?>') ?]
    </th>
<?php endforeach; ?>
