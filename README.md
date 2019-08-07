## XYMON
### 기술
xymon은 기존 BBMON과 유사한 monitering opensource 입니다.  
쉽고 간단한 설치 및 config로 agent를 통한 모니터링이 가능합니다.  
BBMON에서 사용했던 bb agent와 호환이 가능하여 BBMON 사용중인 시스템에서 client 설치 없이 사용 가능합니다.
### 구성
* **기반언어**: perl
* **환경설정**: cfg 파일  
  - alerts.cfg(SMS, EMAIL 알람설정 파일)  
  - analysis.cfg(모니터링 임계치 설정파일)
  - backup(xymon관련 없음)
  - cgioptions.cfg(xymon perl 옵션설정)
  - client-local.cfg(xymon client필요시 설정)
  - columndoc.csv(모니터링 컬럼 리스트)
  - combo.cfg(HA cluster 모니터링 설정)
  - cookies.session(cookies세션 모니터링 설정)
  - critical.cfg
  - critical.cfg.bak
  - graphs.cfg(rrd 그래프 옵션 설정)
  - holidays.cfg(기간단위 모니터링 예외설정)	- hosts.cfg(모니터링 대상리스트)
  - protocols.cfg(포트, process 모니터링 설정)
  - rrddefinitions.cfg(rrd 데이터 누적 텀)
  - snmpmibs.cfg(MID, OID로 모니터링 추가)
  - tasks.cfg(xymon 실행 스케줄 설정)
  - tasks.d(tasks.cfg include되어 실행됨)
  - xymon-apache.conf(apache환경파일)
  - xymonmenu.cfg(xymon web페이지 메뉴 - 수정)
  - xymonmenu.cfg_ori(xymon web페이지 메뉴 원본)
  - xymonserver.cfg(xymon 환경설정 파일)


#### 1. 환경
- OS: CentOS release 6.9(64bit)
- XYMON: xymon-4.3.28
- WEB: Apache/2.2.15, DB: maria-10.0.38, php-5.6.25( DB 및 PHP는 별도의 관리자 페이지 구축시에만 필요)

#### 2.	OS 지원(xymon-server 설치 가능 OS)
* Red Hat Enterprise Linux 6 / CentOS 6  
* Red Hat Enterprise Linux 5 / CentOS 5   
* Red Hat Enterprise Linux 4 / CentOS 4   
* Red Hat Enterprise Linux 3 / CentOS 3   
* Fedora Linux 17  
* Debian 6 (Squeeze)  
* Ubuntu 12.04 LTS (Precise Pangolin)  
* FreeBSD 7, 8 and 9  
* OpenBSD 4, 5  
* Solaris 10/x86 (using OpenCSW)  
* Solaris 10/x86 (using Sun Freeware)  
* Mac OSX  

**perl기반이며, log또는 cfg파일로 관리되어 php, mysql 설치 필요없음**  
**gabia의 경우에는 아래 로직으로 처리되며, 관리페이지 접속이 필요하여 APM 모두 필요.**

#### 3.	패키지 설치:
```sh
$ yum install pcre-devel  
$ yum install rrdtool-devel  
$ yum install openssl-devel  
$ yum install openldap-devel 
$ yum install fping  
```

#### 4.	Xymon 설치:
```sh
$./configure.server
```
![텍스트](https://github.com/sahagong/xymon/blob/master/img/1.jpg)

```sh
Do you want to be able to test SSL-enabled services (y) ? n
Do you want to be able to test LDAP servers (y) ? n
What userid will be running Xymon [xymon] ? (Enter)
Where do you want the Xymon installation [/home/xymon] ? (Enter)
hat URL will you use for the Xymon webpages [/xymon]?      (Enter)
 ex) http://xymon.sahagong.com/xymon <--- 같은 형태로 alias가 걸림.
(추후 xymon_httpd.conf 수정해주면 됨)
```

```sh
####################################################################
# Toplevel dir
XYMONTOPDIR = /home/xymon
# Server data dir for hist/ etc.
XYMONVAR = /home/xymon/data
# CGI scripts go in CGIDIR
CGIDIR = /home/xymon/cgi-bin
# Admin CGI scripts go in SECURECGIDIR
SECURECGIDIR = /home/xymon/cgi-secure
# Where to put logfiles
XYMONLOGDIR = /var/log/xymon
# Where to install manpages
MANROOT = /usr/local/man
# How to run fping or xymonping
FPING = xymonping

# Username running Xymon
XYMONUSER = xymon
# Xymon server hostname
XYMONHOSTNAME = xymon.sahagong.com
# Xymon server IP-address
XYMONHOSTIP = 127.0.0.0 ** "xymon 설치 서버IP" **
# Xymon server OS
XYMONHOSTOS = linux

# URL for Xymon webpages
XYMONHOSTURL = /xymon
# URL for Xymon CGIs
XYMONCGIURL = /xymon-cgi
# URL for Xymon Admin CGIs
SECUREXYMONCGIURL = /xymon-seccgi
# Webserver group-ID
HTTPDGID=apache
####################################################################
```
```sh
$ make;make install
```
![텍스트](https://github.com/sahagong/xymon/blob/master/img/2.jpg)

#### 5.	apache 환경파일 설정:
![텍스트](https://github.com/sahagong/xymon/blob/master/img/3.jpg)

#### 6.	WEB 환경파일 수정
* httpd.conf  
![텍스트](https://github.com/sahagong/xymon/blob/master/img/4.jpg)

*	xymon-apache.conf  
![텍스트](https://github.com/sahagong/xymon/blob/master/img/5.jpg)

#### 8.	Xymon 환경설정 파일 및 수정 값.
##### 1)	task.cfg(xymon job 실행 스케줄 파일)
![task.cfg 상세설명](https://github.com/sahagong/xymon/blob/master/man/test.cfg_man.md)

#### 9.	Xymon 디렉토리 별 리스트 및 사용용도
|-	bin  	=> xymon 실행파일 저장 디렉토리 |
|-	download |
|-	etc		=> xymon cfg 설정파일 저장 디렉토리 |
|-	ext		=> user가 생성한 실행파일 저장 디렉토리 |
|-	tmp		=> 각 서비스별 pid 및 임시파일 저장 디렉토리 |
|-	web		=> 각 웹페이지에 대한 header 저장 디렉토리 |
|-	www	=> bb2.gabia.com main페이지 및 서비스별 페이지 저장 디렉토리|

