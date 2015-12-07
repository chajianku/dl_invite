<?php if (!defined('SYSTEM_ROOT')) { die('Insufficient Permissions'); } 
if (isset($_GET['ok'])) {
	echo '<div class="alert alert-success">操作成功</div>';
}
if (isset($_GET['update'])) {
	global $m;
	$result = $m->fetch_array($m->query("select max(id) as id from `".DB_NAME."`.`".DB_PREFIX."dl_invite`"));
    $row = $result['id'];
	$zg = $row +1;
	$zg2 = $row + 100;
	for($i = $zg;$i <= $zg2;$i++){
		$yqm = getRandStr(18);
		$m->query('INSERT INTO `'.DB_NAME.'`.`'.DB_PREFIX.'dl_invite` (`id`, `code`) VALUES (\''.$i.'\', \''.$yqm.'\');');
		}
	ReDirect(SYSTEM_URL.'index.php?mod=admin:setplug&plug=dl_invite&ok');
	}
if (isset($_GET['delete'])) {
	global $m;
	$m->query("truncate table `".DB_NAME."`.`".DB_PREFIX."dl_invite`");
	ReDirect(SYSTEM_URL.'index.php?mod=admin:setplug&plug=dl_invite&ok');
	}
?>
<h3>多邀请码设置</h3>
</br></br></br>
<?php
global $m;
$cont = '';
$result = $m->fetch_array($m->query("select max(id) as id from `".DB_NAME."`.`".DB_PREFIX."dl_invite`"));
$row = $result['id'];
for($i=0;$i <= $row;$i++){
	$invite = $m->fetch_array($m->query('select * from `'.DB_NAME.'`.`'.DB_PREFIX.'dl_invite` where `id` = '.$i));
	if(!empty($invite['code'])){
		$cont = $cont."\n".$invite['code'];
		}
	}
	echo '已生成的邀请码（每个邀请码使用一次即失效）：</br></br><textarea name="cont" class="form-control" style="height:400px;">'.$cont.'</textarea></br></br></br>';
?>
<button type="button" onclick="window.location = 'index.php?mod=admin:setplug&plug=dl_invite&update';" class="btn btn-success">生成100个邀请码</button>&nbsp;<button type="button" onclick="window.location = 'index.php?mod=admin:setplug&plug=dl_invite&delete';" class="btn btn-danger">清空所有邀请码</button>