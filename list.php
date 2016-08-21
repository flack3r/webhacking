<?php
include "dbconn.php";
$mode = $_GET["mode"];
$read_uno = $_GET["read_uno"];
$table_name = "board_free";

if(!strcmp($mode, "down"))
{
  $filename = $_GET["filename"];
  if($filename)
  {
    $filepath = './uploads/'.$filename;
    $filesize = filesize($filepath);
    $path_parts = pathinfo($filepath);
    $extension = $path_parts['extension'];

    header("Pragma: public");
    header("Expires: 0");
    header("Content-Type: application/octet-stream");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    header("Content-Transfer-Encoding: binary");
    header("Content-Length: $filesize");

    ob_clean();
    flush();
    readfile($filepath);
  }
}

else if(!strcmp($mode, "read")) {  
  $query = "update $table_name set hit = hit + 1 where uno = $read_uno";
  $result = mysql_query($query, $dbconn);

  $query = "select name, email, homepage, subject, content, client_ip, html_tag, filename
            from $table_name where uno = $read_uno";

  $result = mysql_query($query, $dbconn);

  $name = mysql_result($result, 0, 0);
  $email = mysql_result($result, 0, 1);
  $homepage = mysql_result($result, 0, 2);
  $subject = mysql_result($result, 0, 3);
  $content = mysql_result($result, 0, 4);
  $client_ip = mysql_result($result, 0, 5);
  $html_tag = mysql_result($result, 0, 6);
  $filename = mysql_result($result, 0, 7);

  $subject = htmlspecialchars($subject);
  $subject = stripslashes($subject);

  if(strcmp($html_tag, "on"))
    $content = htmlspecialchars($content);
  else
  {
    $content = str_replace("script","xscript",$content);
  }

  $content = stripslashes($content);
  $content = nl2br($content);
?>

  <table width = "750" border = "1" cellspacing = "0" cellpadding = "0">      
    <tr>
      <td width = "100">
        글 쓴이
      </td>
      <td width = "650">
        <?php
        if($email)
          echo("<a href = \"mailto:$email\">$name</a>");
        else
          echo("$name");
        ?>
      </td>
    </tr>
    <tr>
      <td>
        홈페이지
      </td>
      <td>
        <?php
        if($homepage)
          echo("<a href = \"$homepage\" target = \"_blank\">$homepage</a>");
        else
          echo("홈페이지 없음");
        ?>
      </td>
    </tr>
    <tr>
      <td width = "100">
        글 제목
      </td>
      <td width = "650">
        <?php echo("$subject");?>
      </td>
    </tr>
    <tr>
      <td width = "100">
        글 내용
      </td>
      <td width = "650">
        <?php echo("$content");?>
        <br><br>
        <?php echo("IP Address : $client_ip");?>
      </td>
    </tr>
    <tr>
      <td width = "100">
       첨부파일
      </td>
      <td width = "650">
      <?php
      if($filename)
      {
        echo("<a href = \"./list.php?mode=down&filename=$filename\">$filename</a>");
      }
      else
        echo("첨부파일 없음");
      ?>
  </table>
  <br>
  <table width = "750" border = "0" cellspacing = "0" cellpadding = "0">
    <tr>
      <td width = "375">
        <a href = "./list.php">[글 목록]</a>
        <a href = "./write.php?mode=form">[글 쓰기]</a>
      </td>
      <td width = "375" align = "right">
        <a href = "./reply.php?mode=form&reply_uno=<?php echo("$read_uno");?>">[답글 쓰기]</a>
        <a href = "./modify.php?mode=form&modify_uno=<?php echo("$read_uno");?>">[글 수정]</a>
        <a href = "./delete.php?mode=form&delete_uno=<?php echo("$read_uno");?>">[글 삭제]</a>
      </td>
    </tr>
  </table>
  <br>
<?php
}
?>
<table width = "750" border = "1" cellspacing = "0" cellpadding = "0">
  <tr>
    <td width = "50" align = "center">
      번호
    </td>
    <td width = "420" align = "center">
      글 제목
    </td>
    <td width = "100" align = "center">
      글 쓴이
    </td>
    <td width = "100" align = "center">
      등록 일자
    </td>
    <td width = "80" align = "center">
      조회수
    </td>
  </tr>
<?php
  $query = "select uno, gno, reply_depth, name, email, subject, register_date, hit from $table_name order by gno desc, reply_depth asc";

  $result = mysql_query($query, $dbconn);
  $total_record = mysql_num_rows($result);

  $article_no = $total_record;

  for($i = 0; $i < $total_record; $i++) {
    $uno = mysql_result($result, $i, 0);
    $gno = mysql_result($result, $i, 1);
    $reply_depth = mysql_result($result, $i, 2);
    $name = mysql_result($result, $i, 3);
    $email = mysql_result($result, $i, 4);
    $subject = mysql_result($result, $i, 5);
    $register_date = mysql_result($result, $i, 6);
    $hit = mysql_result($result, $i, 7);

    $subject = htmlspecialchars($subject);
    $subject = stripslashes($subject);

    $register_date = date("Y-m-d", $register_date);
?>
    <tr>
      <td align = "center">
        <?php echo("$article_no");?>
      </td>
      <td>
        <a href = "./list.php?mode=read&read_uno=<?php echo("$uno");?>"><?php echo("$subject");?></a>
      </td>
      <td align = "center">
        <?php echo("$name");?>
      </td>
      <td align = "center">
        <?php echo("$register_date");?>
      </td>
      <td align = "center">
        <?php echo("$hit");?>
      </td>
    </tr>
    <?php
    $article_no--;
  }

  if(!$total_record) {
  ?>
    <tr>
      <td align = "center" colspan = "5">
        등록된 글이 없습니다.
      </td>
    </tr>
  <?php
  }
  ?>
</table>
<br>
<table width = "750" border = "0" cellspacing = "0" cellpadding = "0">
  <tr>
    <td align = "right">
      <a href = "./list.php">[글 목록]</a>
      <a href = "./write.php?mode=form">[글 쓰기]</a>
    </td>
  </tr>
</table>