<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>通用权限管理</title>
<link href="<?php echo url_project();?>images/css.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginheight="0" marginwidth="0"><br />
<TABLE height=28 cellSpacing=0 cellPadding=0 width="546" background="<?php echo url_project();?>images/37.gif" border=0>
        <TBODY>
        <TR>
          <TD width=10>&nbsp;</TD>
          <TD width=115 align=center  background="<?php echo url_project();?>images/30.gif"><a href="<?php echo url_for("rbac/acllist",true);?>"><STRONG><FONT color=#ff6600>Router类列表</FONT></STRONG></a></TD>
          <TD width=10>&nbsp;</TD>
          <TD width=10>&nbsp;</TD>
          <TD width=115 align=center background="<?php echo url_project();?>images/29.gif"><span class="cattitle"><a href="<?php echo url_for("rbac/addacl",true);?>">添加Router类</a></span></TD>
		  <TD width=10>&nbsp;</TD>
          <TD width=10>&nbsp;</TD>
          <TD width="266" align=right></TD>
</TR></TBODY></TABLE><br />
Router类列表，主要权限就是限制Router类 ,最好由程序员来标记各个类和类方法使用说明
<hr color="#0066CC" align="left" width="400"><br />
<table width="760" border="0" cellpadding="2" cellspacing="1" class="forumline">
  <tr>
    <th width="11%" align="center" nowrap="nowrap" class="thCornerL">序号</th>
    <th width="28%" height="25" align="center" nowrap="nowrap" class="thCornerL">权限名称</th>
    <th width="21%" align="center" nowrap="nowrap" class="thCornerL">Router类名</th>
    <th width="21%" align="center" nowrap="nowrap" class="thCornerL">路径</th>
	<th width="35%" align="center" nowrap="nowrap" class="thCornerR">操作</th>
  </tr>
  <?php foreach($acllist as $k=>$v):?>                
  <tr>
    <td height="30" align="center" class="row2"><?php echo $v['aclid'];?></td>
    <td height="30" align="center" class="row2"><?php echo $v['title'];?></td>
    <td height="30" align="center" class="row2"><?php echo $v['model'];?></td>
    <td height="30" align="center" class="row2"><?php echo $v['aclpath'];?></td>
    <td height="30" align="center" valign="middle" nowrap="nowrap" class="row2">[<a href="<?php echo url_for("rbac/deleteacl/sid/".$v['aclid'],true);?>">删除</a>] [<a href="<?php echo url_for("rbac/editacl/sid/".$v['aclid'],true);?>">编辑</a>] [<a href="<?php echo url_for("rbac/aclmethod/sid/".$v['aclid'],true);?>">方法列表</a>]</td>
  </tr>
  <?php endforeach;?>
</table>
<p><span class="thCornerL" style="color:#F00">演示:Router路径:project Router类名:curd 或guestbook</span></p>
</html>