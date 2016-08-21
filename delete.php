<script language = "javascript">
  function check_form(form) {
    if(!delete_form.passwd.value) {
      alert('패스워드를 입력하세요.');
      delete_form.passwd.focus();
      return;
    }

    delete_form.submit();
  }
</script>
<?php
include "dbconn.php";
$mode = $_GET["mode"];
$delete_uno = $_GET["delete_uno"];
$table_name = "board_free";

if(!$mode)
  $mode = "form";

if(!strcmp($mode, "form")) {
  $query = "select name from $table_name where uno = $delete_uno";
  $result = mysql_query($query, $dbconn);
  $name = mysql_result($result, 0, 0);
  ?>
  <form name = "delete_form" method = "post"
  action = "./delete.php?mode=post&delete_uno=<?php echo("$delete_uno");?>">
    <table width = "750" border = "1" cellspacing = "0" cellpadding = "0">      
     <tr>
        <td align = "center">
          <b><?php echo("$name");?></b> 님의 글을 삭제합니다.
        </td>
      </tr>
      <tr>
        <td height = "50" align = "center">
          패스워드
          <input type = "password" name = "passwd" size = "20">
        </td>
      </tr>
    </table>
    <br>
    <table width = "750" border = "0" cellspacing = "0" cellpadding = "0">      
      <tr>      
        <td align = "center">  
          <input type = "button" onclick = "check_form();" value = "입력 확인">
          <input type = "button" onclick = "form.reset();" value = "다시 쓰기">
        </td>
      </tr>
    </table>
  </form>  
<?php
} else if(!strcmp($mode, "post")) {
  $passwd = $_POST["passwd"];  
  $query = "select passwd from $table_name where uno = $delete_uno";
  $result = mysql_query($query, $dbconn);
  $real_passwd = mysql_result($result, 0, 0);

  $query = "select password('$passwd')";
  $result = mysql_query($query, $dbconn);
  $input_passwd = mysql_result($result, 0, 0);

  if(strcmp($real_passwd, $input_passwd)) {
  ?>
    <script language = "javascript">
      alert("패스워드가 일치하지 않습니다!");
      history.back();
    </script>
    <?php
    exit();
  }  

  $query = "delete from $table_name where uno = $delete_uno";
  $result = mysql_query($query, $dbconn);
  ?>
  <script language = "javascript">
    document.location.href = './list.php';
  </script>  
<?php
}
?>
