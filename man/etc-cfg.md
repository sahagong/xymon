
### hosts.cfg  
### protocols.cfg
xymon의 경우 기본적으로 체크하는 프로세서(http, ping, ftp..)가 있으며, 이외 추가로 포트를 모니터링 할 수 있다.

- mysql(3306), mssql(1433), http(80) 포트 체크 추가
  [mysql]
 	  port 3306
  [mssql]
    port 1433

