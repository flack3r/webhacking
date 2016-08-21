<script language = "javascript">
  function check_form(form) {
    if(!modify_form.name.value) {
      alert('이름을 입력하세요.');
      modify_form.name.focus();
      return;
    }

    if(!modify_form.passwd.value) {
      alert('패스워드를 입력하세요.');
      modify_form.passwd.focus();
      return;
    }

    if(!modify_form.subject.value) {
      alert('글 제목을 입력하세요.');
      modify_form.subject.focus();
      return;
    }

    if(!modify_form.content.value) {
      alert('글 내용을 입력하세요.');
      modify_form.content.focus();
      return;
    }

    modify_form.submit();
  }
</script>
<?php
include "dbconn.php";
$mode = $_GET["mode"];
$table_name = "board_free";

if(!$mode)
  $mode = "form";

if(!strcmp($mode, "form")) {
  $query = "select name, email, homepage, subject, content, client_ip, html_tag
            from $table_name where uno = $modify_uno";

  $result = mysql_query($query, $dbconn);

  $name = mysql_result($result, 0, 0);
  $email = mysql_result($result, 0, 1);
  $homepage = mysql_result($result, 0, 2);
  $subject = mysql_result($result, 0, 3);
  $content = mysql_result($result, 0, 4);
  $client_ip = mysql_result($result, 0, 5);
  $html_tag = mysql_result($result, 0, 6);

  $subject = htmlspecialchars($subject);
  $subject = stripslashes($subject);

  if(strcmp($html_tag, "on"))
    $content = htmlspecialchars($content);

  $content = stripslashes($content);
  ?>
  <form name = "modify_form" method = "post"
  action = "./modify.php?mode=post&modify_uno=<?php echo("$modify_uno"); ?>">
    <table width = "750" border = "1" cellspacing = "0" cellpadding = "0">      
     <tr>
        <td width = "100">
          이름
        </td>
        <td width = "650">
          <input type = "text" name = "name" value = "<?php echo("$name");?>" size = "20">
        </td>
      </tr>
      <tr>
        <td>
          패스워드
        </td>
        <td>
          <input type = "password" name = "passwd" size = "20">
        </td>
      </tr>
      <tr>
        <td>
          이메일
        </td>
        <td>
          <input type = "text" name = "email" value = "<?php echo("$email");?>" size = "40">
        </td>
      </tr>
      <tr>
        <td>
          홈페이지
        </td>
        <td>
          <input type = "text" name = "homepage" value = "<?php echo("$homepage");?>" size = "40">
        </td>
      </tr>
      <tr>
        <td>
          글 제목
        </td>
        <td>
          <input type = "text" name = "subject" value = "<?php echo("$subject");?>" size = "90">
        </td>
      </tr>      
      <tr>
        <td>
          글 내용
        </td>
        <td>
          HTML TAG
          <input type = "checkbox" name = "html_tag" <?php if(!strcmp($html_tag, "on")) echo "checked";?>>
          <br>
          <textarea name = "content" cols = "88" rows = "10"><?php echo("$content");?></textarea>
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
  $query = "select passwd from $table_name where uno = $modify_uno";
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

  $subject = addslashes($subject);
  $content = addslashes($content);  

  $query = "update $table_name set name = '$name', email = '$email', homepage = '$homepage', subject = '$subject', content = '$content', html_tag = '$html_tag' where uno = $modify_uno";
  $result = mysql_query($query, $dbconn);
  ?>
  <script language = "javascript">
    document.location.href = './list.php';
  </script>  
<?php
}
?>