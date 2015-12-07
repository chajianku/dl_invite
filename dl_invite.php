<?php if (!defined('SYSTEM_ROOT')) { die('Insufficient Permissions'); } 
/*
Plugin Name: 站点多邀请码
Version: 1.0
Plugin URL:http://www.tbsign.cn
Description: 邀请码不再只支持一个，将支持N个
Author: D丶L
Author Email:admin@tbsign.cn
Author URL: http://tbsign.cn
For: 3.8+
*/

function dl_invite_reg(){
	?>
	<b>请输入您的账号信息以注册本站</b><br/><br/>
  <?php if (isset($_GET['error_msg'])): ?><div class="alert alert-danger alert-dismissable">
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
  错误：<?php echo strip_tags($_GET['error_msg']); ?></div><?php endif;?>
  <form name="f" method="post" action="<?php echo SYSTEM_URL ?>index.php?mod=admin:reg">
	<div class="input-group">
  <span class="input-group-addon">账户</span>
  <input type="text" class="form-control" name="user" required>
</div><br/>
<div class="input-group">
  <span class="input-group-addon">密码</span>
  <input type="password" class="form-control" name="pw" id="pw" required>
</div><br/>
<div class="input-group">
  <span class="input-group-addon">邮箱</span>
  <input type="email" class="form-control" name="mail" id="mail" required>
</div>
<?php 
$yr_reg = option::get('dl_intive_enable');
if (!empty($yr_reg)) { ?>
<br/>
<div class="input-group">
  <span class="input-group-addon">邀请码</span>
  <input type="text" class="form-control" name="invite" id="invite" required>
</div>
<?php } ?>
	<div class="login-button"><br/>
	<?php doAction('reg_page_2'); ?>
  <button type="submit" class="btn btn-primary" style="width:100%;float:left;">继续注册</button>
  <?php doAction('reg_page_3'); ?>
	</div><br/><br/><br/>
	<?php echo SYSTEM_FN ?> V<?php echo SYSTEM_VER ?> // 作者: <a href="http://zhizhe8.net" target="_blank">无名智者</a> @ <a href="http://www.stus8.com/" target="_blank">StusGame GROUP</a> &amp; <a href="http://www.longtings.com/" target="_blank">mokeyjay</a>
	<?php
	$icp=option::get('icp');
    if (!empty($icp)) {
      echo ' | <a href="http://www.miitbeian.gov.cn/" target="_blank">'.$icp.'</a>';
    }
    echo '<br/>'.option::get('footer');
    doAction('footer');
    ?>
	<div style=" clear:both;"></div>
	<div class="login-ext"></div>
	<div class="login-bottom"></div>
</div>
<?php
die;
	}
function dl_invite_yz(){
	global $m;
	if (option::get('enable_reg') != '1') {
		msg('注册失败：该站点已关闭注册');
	}
	$name = isset($_POST['user']) ? addslashes(strip_tags($_POST['user'])) : '';
	$mail = isset($_POST['mail']) ? addslashes(strip_tags($_POST['mail'])) : '';
	$pw = isset($_POST['pw']) ? addslashes(strip_tags($_POST['pw'])) : '';
	$yr = isset($_POST['invite']) ? addslashes(strip_tags($_POST['invite'])) : '';
	if (empty($name) || empty($mail) || empty($pw)) {
		msg('注册失败：请正确填写账户、密码或邮箱');
	}
	$x=$m->once_fetch_array("SELECT COUNT(*) AS total FROM `".DB_NAME."`.`".DB_PREFIX."users` WHERE name='{$name}'");
	$z=$m->once_fetch_array("SELECT COUNT(*) AS total FROM `".DB_NAME."`.`".DB_PREFIX."users` WHERE email='{$name}'");
	$y=$m->once_fetch_array("SELECT COUNT(*) AS total FROM `".DB_NAME."`.`".DB_PREFIX."users`");
	if ($x['total'] > 0) {
		msg('注册失败：用户名已经存在');
	}
	if ($z['total'] > 0) {
		msg('注册失败：邮箱已经存在');
	}
	if (!checkMail($mail)) {
		msg('注册失败：邮箱格式不正确');
	}
	if (empty($yr)) {
		msg('注册失败：请输入邀请码');
	}
	$invite = $m->fetch_array($m->query('select * from `'.DB_NAME.'`.`'.DB_PREFIX.'dl_invite` where `code` = "'.$yr.'"'));
	if(!empty($invite['code'])){
		$dlyr = $invite['code'];
		$m->query('DELETE FROM `'.DB_NAME.'`.`'.DB_PREFIX.'dl_invite` where `code` = "'.$dlyr.'"');
		} else {
			msg('注册失败：邀请码错误或已被使用');
			}
	

	
	if ($y['total'] <= 0) {
		$role = 'admin';
	} else {
		$role = 'user';
	}
	doAction('admin_reg_2');
	$m->query('INSERT INTO `'.DB_NAME.'`.`'.DB_PREFIX.'users` (`id`, `name`, `pw`, `email`, `role`, `t`) VALUES (NULL, \''.$name.'\', \''.EncodePwd($pw).'\', \''.$mail.'\', \''.$role.'\', \''.getfreetable().'\');');
	setcookie("wmzz_tc_user",$name);
	setcookie("wmzz_tc_pw",EncodePwd($pw));
	doAction('admin_reg_3');
	ReDirect('index.php');
	echo '}';
	die;
	}
addAction('reg_page_1','dl_invite_reg');
addAction('admin_reg_1','dl_invite_yz');
?>