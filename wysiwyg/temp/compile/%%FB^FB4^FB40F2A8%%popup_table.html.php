<?php /* Smarty version 2.6.26, created on 2015-01-29 16:56:59
         compiled from wysiwyg/popup_table.html */ ?>
<table><tr><td><?php echo $this->_tpl_vars['names']['table_rows']; ?>
:</td><td><input id="<?php echo $this->_tpl_vars['id']; ?>
_table_rows"   type="text" value="2" /></td></tr>
       <tr><td><?php echo $this->_tpl_vars['names']['table_cols']; ?>
:</td><td><input id="<?php echo $this->_tpl_vars['id']; ?>
_table_cols"   type="text" value="2" /></td></tr>
       <tr><td><?php echo $this->_tpl_vars['names']['table_border']; ?>
:</td><td><input id="<?php echo $this->_tpl_vars['id']; ?>
_table_border" type="text" value="1" /></td></tr>
       <tr><td></td><td><input type="button" value="<?php echo $this->_tpl_vars['names']['submit']; ?>
" onclick="WysiwygInsTable('<?php echo $this->_tpl_vars['id']; ?>
');" /></td></tr></table>