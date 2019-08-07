
### hosts.cfg  
xymon에서 모니터링 되는 서버 리스트가 정의되는 파일이다.
![텍스트](https://github.com/sahagong/xymon/blob/master/img/hosts.cfg.jpg)  

 (1) host 설정 내용  
group dev-test (http://xymon.sahagong.com 전체 화면에 표시될 group명)  
   - 123.123.123.123(서버IP)   dev-test123(호스트명)  testip # LS:total(display 그룹명)  NOCOLUMNS:cpu,disk,ftp,http,info,memory,ms-sql-s,msgs,mssql,mysql,pop3,procs,queue,smtp,ssh,trends,io (모니터링 불필요 컬럼)  
 
 (2) display 그룹 설정내용  
   - lspage    total  => devtest 리스트 display 그룹명  
    (124.124.124.124 dev-php  testip # LS:total  NOCOLUMNS:….)  

**/home/xymon/server/ext/ls-display.sh**
```sh
#/bin/sh

**total list**
$XYMONHOME/bin/xymongen \
--pageset=ls --template=ls \
--page-title=total --critical-reds-only $XYMONHOME/www/ls
```
 - ospage    mana => dev 리스트 display 그룹명  
   (124.124.124.125 dev-125  testip # OS:mana  NOCOLUMNS:…) 
   
**/home/xymon/server/ext/mana-display.sh**  
```sh
#/bin/sh
## manage list
$XYMONHOME/bin/xymongen \
--pageset=os --template=os \
--page-title=mana --critical-reds-only $XYMONHOME/www/mana
```


### protocols.cfg
xymon의 경우 기본적으로 체크하는 프로세서(http, ping, ftp..)가 있으며, 이외 추가로 포트를 모니터링 할 수 있다.

- mysql(3306), mssql(1433), http(80) 포트 체크 추가  
  **[mysql]**  
 	  port 3306  
    
  **[mssql]**  
    port 1433  

