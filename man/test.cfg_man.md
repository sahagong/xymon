### task.cfg 
xymon background 데몬의 옵션 설정 및 alert, monitering 스케줄의 대한 시간을 지정할 수 있다.
xymon 환경변수를 사용하여 USER가 직접 스크립트를 작성하여 스케줄을 추가할 수 있다.

![텍스트](https://github.com/sahagong/xymon/blob/master/img/task.cfg.jpg)  

- ① make_bbhosts.cfg : BBMON DB의 hostname을 그룹으로 sorting하여 /home/xymon/server/etc/hosts.cfg 에 저장   
- ② mana-display.sh: 위탁대상 리스트 페이지 갱신하는 script  
- ③ ls-display.sh: 비위탁 리스트 페이지 갱신하는 script  
- ④ task를 실행하는 최종 main process(xymod 종속으로 하단 task가 실행됨)  
    - checkpoint-interval: 3600초(60분 간격)  
- ⑤ 모니터링 요소별 통신 테스트(conn -> xymonping, http 응답여부 실행, 각 포트별 응답여부 확인)  
   * dns-ip: ping 테스트 시, hostname으로 체크하지 않고 ip로만 체크  
   * dns-timeout: dns질의시간 제한없음(web 체크 시, dns응답없어도 fail)  
   * timeout: 서비스 체크 응답까지 대기시간  
   * notrace: 호스트 및 IP 대한 trace 비활성화  
   * ping-tasks: conn 체크 병렬체크 시 job 갯수  
- ⑥ xymonnet 실행 후 red발생된 리스트에 대해 1분(1m) 간격으로 다시 체크  
  (xymonnet 실행 후 ping-task당 ping.pid번호로 파일 생성 됨. 해당파일을 기준으로 red만 재 체크)  
- ⑦xymonet 및 xymonnetagain 실행 후 삭제되지 않은 파일 중 7일이상 지난 파일 삭제  

