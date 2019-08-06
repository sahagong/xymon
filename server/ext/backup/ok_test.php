#!/usr/local/php/bin/php -f
<?
/*

make file $XYMONHOME/etc/bb-hosts 
by coffeein <2003.11.20>

*/

function exclude_list(){

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

        return $list_arr;

}

	$list=exclude_list();

			for ($i=0;$i<count($list);$i++){
				$check=array_keys($list[$i], "server177-81");

				if ($check){

					$val_arr=array_search("server177-81", $list[$i]);

					echo $list[$i][1];
				}

			}
