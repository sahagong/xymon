#!/usr/local/php/bin/php -f
<?
/*

make file $XYMONHOME/etc/bb-hosts 
by coffeein <2003.11.20>

*/


	require("include/db_class.inc");
	
	//admin.gabia.com의 T_SERVER_GROUP 에서 그룹 리스트 가져옴
	require_once("include/admin_dbconn.php");

	//$BB_SERVER_IP = "";
	$BB_SERVER_IP = trim(shell_exec("/sbin/ifconfig eth0|grep \"inet addr\" |awk -F\" \" '{print$2}' |awk -F\":\" '{print$2}'"));

	$sql = "select group_name, idx from T_SERVER_GROUP where bb_server_ip='$BB_SERVER_IP' order by page_order";
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


	for($i = 0 ; $i < count($group_result); $i++){
		$group_name = $group_result[$i][group_name];
		$group_code = $group_result[$i][idx];


		ob_start();

		echo "\n";		
		echo "group $group_name\n";

		$sql_h = "select server_ip, server_name from bb_hosts where group_code='$group_code' group by server_ip order by server_ip ";

		$re_h = $my_db->select($sql_h);

		for($j = 0 ; $j < count($re_h); $j++){
			$service_list = null;

			$server_name 	= $re_h[$j][server_name];
			$ipcheck	= $re_h[$j][ipcheck];

			$server_name 	= $server_name." ".$ipcheck;
			
			$server_ip 	= $re_h[$j][server_ip];


			$service_code = null;
			$sql_t = "select service_code from bb_hosts where server_ip='$server_ip' group by service_code";
			$re_t = $my_db->select($sql_t);
			$max_count = count($re_t);


			for($k = 0 ; $k < count($re_t); $k++){

				if ("conn" == $re_t[$k][service_code]){

					$service_code   .="";
				} else if ("noconn" == $re_t[$k][service_code]){

					$service_code   .="noping";

				} else if ("ms-sql-s" == $re_t[$k][service_code]) {
				
					$service_code   .="mssql ";
				}else { 
					$service_code 	.= $re_t[$k][service_code]." ";
				}
			


/*
                                 $str_service_code=split(" ",$service_code);
				 for ($m =0; $m<count($str_service_code); $m++){
                                 //print_r($str_service_code);


						echo trim($str_service_code[$m]);
				 }
				
*/
				if($k == ($max_count - 1)){
					if(strcmp($BB_SERVER_IP, $server_ip) == 0){
						echo $server_ip."\t".$server_name." # BBPAGER BBNET BBDISPLAY ".$service_code."\n";

					}
					else{

					
                                               //$str_service_code=split(" ",$service_code);
                                               //print_r($str_service_code);
/*
					   for ($m =0; $m<count($str_service_code); $m++){
					         if (empty($str_service_code[$m])){
				
					 	   echo $str_service_code[$m];
					        }
					 
					   }
*/
					
						//if($service_code == "conn" || $service_code == "noconn") {
						if(strcmp($group_code, "71") == 0){
					
								//$str_service_code=str_replace("conn","# OS:mana",trim($service_code));
								//$str_service_code=str_replace("noconn","# OS:mana",trim($service_code));
	
								//$service_code[0][0]   .= "OS:mana"." ";
								//print_r($service_code);
								echo $server_ip."\t".$server_name." testip # OS:mana NOCOLUMNS:".$MANA_NOCOLUMNS." ".$service_code."\n";
							//}
						}else{
							//$str_service_code=str_replace("conn "," ",trim($service_code));
							//$str_service_code=str_replace("noconn "," ",trim($service_code));

							//echo trim($service_code);
								echo $server_ip."\t".$server_name." testip # LS:total NOCOLUMNS:".$TOTAL_NOCOLUMNS." ".$service_code."\n";
							
						}
					}
				}
			}
		
		}
	
		
		$file_stream  = ob_get_contents();
		ob_end_clean();



		$get_pid = getmypid();
		$tmp_file = "$XYMONHOME/ext/tmp/bb-hosts.$group_code.$get_pid";
		$tmp_file2 = "$XYMONHOME/ext/tmp/bb-hosts".".sort.".$get_pid;

		$file = fopen($tmp_file, "w");
		fwrite($file, $file_stream);
		fclose($file);

		#### ip sort 
		$command = "echo $tmp_file | perl -e 'my \$file=<STDIN> ; print map { \$_->[0] } sort { \$a->[1] cmp \$b->[1] } map {  [ \$_, sprintf(\"%03d.%03d.%03d.%03d\", (split /\D/, \$_)[0 .. 3]) ] } `cat \$file`;' >> $tmp_file2 ";

		shell_exec($command);

	}
	$my_db->close();

	//shell_exec("sed -i -e '1s/^/'\"aaaaa\"'\n/' -e '1s/^/'\"bbbb\"'\n/' -e '2s/^/'\"\"'\n/' -e '2s/^/'\"\"'\n/'");
	exec("cp $tmp_file2 $XYMONHOME/etc/hosts.cfg");

	//print_r($error);

	shell_exec('sed -i -e 1s/^/"noflap=colo144-112,colo192-140,colo213-177,server179-35"\\\n/ -e 1s/^/"ospage    mana"\\\n/ -e 1s/^/"lspage    total"\\\n/ -e 2s/^/""\\\n/ -e 2s/^/""\\\n/ /home/xymon/server/etc/hosts.cfg; \
			sed -i -e 1s/^/"ospage    mana"\\\n/ -e 1s/^/"lspage    total"\\\n/ -e 2s/^/""\\\n/ -e 2s/^/""\\\n/ '.$tmp_file2);

	shell_exec("cp $tmp_file2 $XYMONHOME/ext/tmp/hosts.cfg_ori; rm -rf $XYMONHOME/ext/tmp/bb-hosts*");

?>

