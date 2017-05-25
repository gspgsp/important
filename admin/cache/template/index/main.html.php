__layout|public:main_layout|layout__ 
<div class="tblform" style="margin:5px;">
  <h4>系统信息</h4>
  <div class="part">
    <table cellspacing="0" cellpadding="0" border="0">
      <tbody>
      
      <?php $_from = $this->_var['sys_info']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->_push_vars('k', 'v');$this->_foreach['n'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['n']['total'] > 0):
    foreach ($_from AS $this->_var['k'] => $this->_var['v']):
        $this->_foreach['n']['iteration']++;
?>
      <tr>
        <th><?php echo $this->_var['k']; ?>: </th>
        <td><?php echo $this->_var['v']; ?></td>
      </tr>
      <?php endforeach; endif; unset($_from); ?><?php $this->_pop_vars();; ?>
        </tbody>
      
    </table>
  </div>
  <span class="inline_note">系统版本号：V001</span><br>
</div>
