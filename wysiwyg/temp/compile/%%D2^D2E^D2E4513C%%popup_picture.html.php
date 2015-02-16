<?php /* Smarty version 2.6.26, created on 2015-01-29 16:56:59
         compiled from wysiwyg/popup_picture.html */ ?>
<table><tr><td><?php echo $this->_tpl_vars['names']['picture_url']; ?>
:</td><td><input id="<?php echo $this->_tpl_vars['id']; ?>
_picture_url"    type="text" /></td></tr>
       <tr><td><?php echo $this->_tpl_vars['names']['picture_alt']; ?>
:</td><td><input id="<?php echo $this->_tpl_vars['id']; ?>
_picture_alt"    type="text" /></td></tr>
       <tr><td><?php echo $this->_tpl_vars['names']['picture_width']; ?>
:</td><td><input id="<?php echo $this->_tpl_vars['id']; ?>
_picture_width"  type="text" /></td></tr>
       <tr><td><?php echo $this->_tpl_vars['names']['picture_height']; ?>
:</td><td><input id="<?php echo $this->_tpl_vars['id']; ?>
_picture_height" type="text" /></td></tr>
       <tr><td></td><td><input type="button" value="<?php echo $this->_tpl_vars['names']['submit']; ?>
" onclick="WysiwygInsPicture('<?php echo $this->_tpl_vars['id']; ?>
');" /></td></tr></table>