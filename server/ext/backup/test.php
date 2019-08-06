#!/usr/local/php/bin/php -f
<?
/*

make file $XYMONHOME/etc/bb-hosts 
by coffeein <2003.11.20>

*/

function exclude_list($server_name){

        $result = "";
        $fp=@file('/home/xymon/server/www/exclude_list') or $result="no such or file.";;

        if ($fp != null ){
		$j=0;
                for($i=0;$i<count($fp);$i++){
			$txt_del=rtrim($fp[$i]);
			if (ereg("^#",$txt_del) || strlen(trim($txt_del)) < 1 ){

			}else{
				
				$list_ex=explode(" ", $fp[$i]);
				$list_arr[$j]=$list_ex;
				//echo $j;
				$j++;

                       	// $list_ex[$i] .=$list_ex;
			}
			
                }
        }


                        for ($i=0;$i<count($list_arr);$i++){
                                $check=array_keys($list_arr[$i], $server_name);

                                if ($check){

                                        $val_arr=array_search($server_name, $list_arr[$i]);

                                        echo $list_arr[$i][1];
                                }

                        }

}

$server_name="L4-MIP-191";
//echo exclude_list($server_name);

                                $exclude=exclude_list(trim($server_name));


                                if ($exclude){

                                        $comment="COMMENT:$exclude";
                                }else{

                                        $comment="";

                                }

