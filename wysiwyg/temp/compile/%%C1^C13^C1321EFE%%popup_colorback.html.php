<?php /* Smarty version 2.6.26, created on 2015-01-29 16:56:59
         compiled from wysiwyg/popup_colorback.html */ ?>
<img src="<?php echo $this->_tpl_vars['root']; ?>
images/wysiwyg/colors.png"
     alt=""
   width="147"
  height="99"
  usemap="#<?php echo $this->_tpl_vars['id']; ?>
_colorback_map" />
<map name="<?php echo $this->_tpl_vars['id']; ?>
_colorback_map"
       id="<?php echo $this->_tpl_vars['id']; ?>
_colorback_map"><?php $_from = $this->_tpl_vars['lists']['colors']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?><area href="#" alt="" coords="<?php echo $this->_tpl_vars['item']['x1']; ?>
,<?php echo $this->_tpl_vars['item']['y1']; ?>
,<?php echo $this->_tpl_vars['item']['x2']; ?>
,<?php echo $this->_tpl_vars['item']['y2']; ?>
" onclick="return WysiwygCmdColorBack('<?php echo $this->_tpl_vars['id']; ?>
', '#<?php echo $this->_tpl_vars['item']['color']; ?>
');" /><?php endforeach; endif; unset($_from); ?></map>

<script type="text/javascript">
var img = new Image(); img.src = '<?php echo $this->_tpl_vars['root']; ?>
images/wysiwyg/colors.png';
</script>