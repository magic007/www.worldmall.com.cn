<?php echo $this->fetch('header.html'); ?>
<script type="text/javascript">
$(function(){
    $('#user_form').validate({
        errorPlacement: function(error, element){
            $(element).next('.field_notice').hide();
            $(element).after(error);
        },
        success       : function(label){
            label.addClass('right').text('OK!');
        },
        onkeyup    : false,
        rules : {
            user_name : {
                required : true,
                maxlength: 25,
                minlength: 3,
                remote   : {
                    url :'index.php?app=admin&act=check_user',
                    type:'get',
                    data:{
                        user_name : function(){
                            return $('#user_name').val();
                        },
                        id : '<?php echo $this->_var['user']['user_id']; ?>'
                    }
                }
            },
            password: {
                <?php if ($_GET['act'] == 'add'): ?>
                required : true,
                <?php endif; ?>
                maxlength: 20,
                minlength: 6
            },
            email   : {
                required : true,
                email : true
            }
        },
        messages : {
            user_name : {
                required : '管理员名称不能为空',
                maxlength: '用户名的长度应在3-25个字符之间',
                minlength: '用户名的长度应在3-25个字符之间',
                remote   : '该用户名已经存在了，请您换一个'
            },
            password : {
                <?php if ($_GET['act'] == 'add'): ?>
                required : '密码不能为空',
                <?php endif; ?>
                maxlength: '密码长度应在6-20个字符之间',
                minlength: '密码长度应在6-20个字符之间'
            },
            email  : {
                required : '电子邮箱不能为空',
                email   : '请您填写有效的电子邮箱'
            }
        }
    });
});
</script>
<div class="info">
  <form method="post" enctype="multipart/form-data" id="user_form">
    <table class="infoTable">
      <tr>
        <th class="paddingT15"> 用户名:</th>
        <td class="paddingT15 wordSpacing5">
          <?php if ($this->_var['user']['user_id']): ?>
          <?php echo htmlspecialchars($this->_var['user']['user_name']); ?>
          <?php else: ?>
          <input class="infoTableInput2" id="user_name" type="text" name="user_name" value="<?php echo htmlspecialchars($this->_var['user']['user_name']); ?>" />
          <label class="field_notice">用户名</label>
          <?php endif; ?>
        </td>
      </tr>
      <tr>
        <th class="paddingT15"> 密&nbsp;&nbsp;&nbsp;码:</th>
        <td class="paddingT15 wordSpacing5"><input class="infoTableInput2" name="password" type="password" id="password" />
          <?php if ($this->_var['user']['user_id']): ?>
          <span class="grey">留空表示不修改密码</span>
          <?php endif; ?>        </td>
      </tr>
      <tr>
        <th class="paddingT15"> 电子邮件:</th>
        <td class="paddingT15 wordSpacing5">
        	<input class="infoTableInput2" name="email" type="text" id="email" value="<?php echo htmlspecialchars($this->_var['user']['email']); ?>" />
            <label class="field_notice">电子邮件</label>
         </td>
      </tr>
      <tr>
        <th class="paddingT15"> 真实姓名:</th>
        <td class="paddingT15 wordSpacing5">
        	<input class="infoTableInput2" name="real_name" type="text" id="real_name" value="<?php echo htmlspecialchars($this->_var['user']['real_name']); ?>" />
         </td>
      </tr>
      <tr>
        <th class="paddingT15"> 性别:</th>
        <td class="paddingT15 wordSpacing5">
        	<p><label>
            <input name="gender" type="radio" value="0" <?php if ($this->_var['user']['gender'] == 0): ?>checked="checked"<?php endif; ?> />
            保密</label>
            <label>
            <input type="radio" name="gender" value="1" <?php if ($this->_var['user']['gender'] == 1): ?>checked="checked"<?php endif; ?> />
            男</label>
            <label>
            <input type="radio" name="gender" value="2" <?php if ($this->_var['user']['gender'] == 2): ?>checked="checked"<?php endif; ?> />
            女
            </label></p>
         </td>
      </tr>
      <tr>
        <th class="paddingT15"> QQ:</th>
        <td class="paddingT15 wordSpacing5">
        	<input class="infoTableInput2" name="im_qq" type="text" id="im_qq" value="<?php echo htmlspecialchars($this->_var['user']['im_qq']); ?>" />
        </td>
      </tr>
      <tr>
        <th class="paddingT15"> MSN:</th>
        <td class="paddingT15 wordSpacing5">
        	<input class="infoTableInput2" name="im_msn" type="text" id="im_msn" value="<?php echo htmlspecialchars($this->_var['user']['im_msn']); ?>" />
        </td>
      </tr>
      <tr>
        <th></th>
        <td class="ptb20">
        	<input class="formbtn" type="submit" name="Submit" value="提交" />
            <input class="formbtn" type="reset" name="Reset" value="重置" />
        </td>
      </tr>
    </table>
  </form>
</div>
<?php echo $this->fetch('footer.html'); ?>