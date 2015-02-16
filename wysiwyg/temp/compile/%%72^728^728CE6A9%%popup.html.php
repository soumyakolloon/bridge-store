<?php /* Smarty version 2.6.26, created on 2015-01-29 16:56:59
         compiled from popup/popup.html */ ?>
<div id="<?php echo $this->_tpl_vars['popup_id']; ?>
_main" class="cms_css_popup_main <?php echo $this->_tpl_vars['class']; ?>
">
<div id="<?php echo $this->_tpl_vars['popup_id']; ?>
_drag" class="cms_css_popup_drag">
<div id="<?php echo $this->_tpl_vars['popup_id']; ?>
_exit" class="cms_css_popup_exit"></div><?php echo $this->_tpl_vars['title']; ?>
</div>
<div id="<?php echo $this->_tpl_vars['popup_id']; ?>
_body" class="cms_css_popup_body">
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['popup'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></div></div>