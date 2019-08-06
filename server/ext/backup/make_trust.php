#!/usr/local/php/bin/php -f
<?
/*

make file $XYMONHOME/etc/bb-hosts
by ljw

*/


        require("include/db_class.inc");

        //admin.gabia.com의 T_SERVER_GROUP 에서 그룹 리스트 가져옴
        require_once("include/admin_dbconn.php");

        $BB_SERVER_IP = trim(shell_exec("/sbin/ifconfig eth0|grep \"inet addr\" |awk -F\" \" '{print$2}' |awk -F\":\" '{print$2}'"));
        $sql = "select group_name, idx from T_SERVER_GROUP where idx='71' order by page_order";
        $group_result = $admin_db->select($sql);
        $admin_db->close();

        //db val
        $db_name = getenv("DBNAME");
        $db_user = getenv("DBUSER");
        $db_pass = getenv("DBPASS");
        $db_host = getenv("DBHOST");

        $XYMONHOME = getenv("XYMONHOME");

        //db connect
        $my_db = new my_db();
        $my_db->sqlhost = $db_host;
        $my_db->sqluser = $db_user;
        $my_db->sqlpass = $db_pass;
        $my_db->sqldb = $db_name;

        $my_db->connect();

        //group list
        for($i = 0 ; $i < count($group_result); $i++){
                $group_name = $group_result[$i][group_name];
                $group_code = $group_result[$i][idx];

                ob_start();

                echo "group $group_name\n";

                $sql_h = "select server_ip, server_name, ipcheck from bb_hosts where group_code='$group_code' group by server_ip order by server_ip ";

                $re_h = $my_db->select($sql_h);

                for($j = 0 ; $j < count($re_h); $j++){
                        $service_list = null;

                        $server_name    = $re_h[$j][server_name];
                        $ipcheck        = $re_h[$j][ipcheck];

                        $server_name    = $server_name." ".$ipcheck;

                        $server_ip      = $re_h[$j][server_ip];
                                                                                                                                       

                        $service_code = null;
                        $sql_t = "select service_code from bb_hosts where server_ip='$server_ip' group by service_code";
                        $re_t = $my_db->select($sql_t);
                        $max_count = count($re_t);

                        for($k = 0 ; $k < count($re_t); $k++){
                                $service_code   .= $re_t[$k][service_code]." ";

                                if($k == ($max_count - 1)){
                                        if(strcmp($BB_SERVER_IP, $server_ip) == 0){
                                                echo $server_ip."\t".$server_name." # BBPAGER BBNET BBDISPLAY ".$service_code."\n";

                                        }
                                        else{
                                                echo $server_ip."\t".$server_name."# ".$service_code."\n";
                                        }
                                }
                        }

                }


                $file_stream  = ob_get_contents();
                ob_end_clean();

                $get_pid = getmypid();
                $tmp_file = "$XYMONHOME/ext/tmp/bb-trust.$group_code.$get_pid";
                $tmp_file2 = "$XYMONHOME/ext/tmp/bb-trust".".sort.".$get_pid;

                $file = fopen($tmp_file, "w");
                fwrite($file, $file_stream);
                fclose($file);

                #### ip sort
                $command = "echo $tmp_file | perl -e 'my \$file=<STDIN> ; print map { \$_->[0] } sort { \$a->[1] cmp \$b->[1] } map {  [ \$_, sprintf(\"%03d.%03d.%03d.%03d\", (split /\D/, \$_)[0 .. 3]) ] } `cat \$file`;' >> $tmp_file2 ";

                shell_exec($command);

        }
        $my_db->close();

        $exe_host = "cp $tmp_file2 $XYMONHOME/etc/bb-trust";

        shell_exec($exe_host);

        shell_exec("rm -rf $XYMONHOME/ext/tmp/bb-trust*");

?> 
