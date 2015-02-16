<?php /* Smarty version 2.6.26, created on 2015-01-29 16:56:59
         compiled from wysiwyg/popup_smileys.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'wysiwyg/popup_smileys.html', 7, false),)), $this); ?>
<?php $_from = $this->_tpl_vars['lists']['smileys']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>

<img src="<?php echo $this->_tpl_vars['root']; ?>
images/smileys/<?php echo $this->_tpl_vars['item']; ?>
"
     alt=""
   width="20"
  height="20"
 onclick="WysiwygInsSmiley('<?php echo $this->_tpl_vars['id']; ?>
', this.src);" /><?php echo smarty_function_cycle(array('name' => $this->_tpl_vars['id'],'values' => ',,,,,,,,<br />'), $this);?>


<script type="text/javascript">
var img = new Image(); img.src = '<?php echo $this->_tpl_vars['root']; ?>
images/smileys/<?php echo $this->_tpl_vars['item']; ?>
';
</script>

<?php endforeach; endif; unset($_from); ?>