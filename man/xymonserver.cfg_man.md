### xymonserver.cfg

xymon에서 사용하는 환경변수를 정의할 수 있는 xymon config 파일이다.
User가 변수를 정의하여 xymon에서 사용하는 cfg파일에 호출하여 사용할 수 있다.
아래 파일에서 추가된 변수는 host list를 관리하기 위해 관리자 DB에 접속정보를 변수로 정의 하였다.

**관리자 페이지 모니터링 IP 및 host등록 --> 관리자 페이지 DB 기록 --> make-hosts.php로 hosts.cfg 생성.(tasks.cfg에 스케줄 정의)**

![텍스트](https://github.com/sahagong/xymon/blob/master/img/xymonserver.cfg.jpg)  

- DB정보 정의  
![텍스트](https://github.com/sahagong/xymon/blob/master/img/xymonserver.cfg_DB.jpg)

- Check 횟수에 따른 display 기준 정의  
 * - DELAYRED="conn:1,procs:3,http:1,memory:90,disk:95,cpu:95"
	   (conn/http  1회이상 fail이거나, memory/disk/cpu  임계치 이상이면 red)  
 * - DELAYYELLOW="memory:80,disk:90,cpu:90,procs:3" 
		   (proc  3회이상 fail이거나,  memory/disk/cpu  임계치 이상이면 yellow)  
![텍스트](https://github.com/sahagong/xymon/blob/master/img/check_display.jpg)  






