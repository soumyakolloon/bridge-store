<?php /* Smarty version 2.6.26, created on 2015-01-29 16:56:59
         compiled from wysiwyg/select.html */ ?>
<select onchange="if (this.selectedIndex) WysiwygCmd('<?php echo $this->_tpl_vars['id']; ?>
', '<?php echo $this->_tpl_vars['command']; ?>
', this.value); this.selectedIndex = 0;"><?php $_from = $this->_tpl_vars['source']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?><option value="<?php echo $this->_tpl_vars['key']; ?>
"><?php echo $this->_tpl_vars['item']; ?>
</option><?php endforeach; endif; unset($_from); ?></select>