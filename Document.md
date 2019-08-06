## XYMON
### 환경
- OS: CentOS release 6.9(64bit)
- XYMON: xymon-4.3.28
- WEB: Apache/2.2.15, DB: maria-10.0.38, php-5.6.25(별도의 관리자 페이지 구축시에만 필요)

#### 1.	OS 지원(xymon-server 설치 가능 OS)
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

#### 2.	패키지 설치:
```sh
yum install pcre-devel  
yum install rrdtool-devel**  
yum install openssl-devel**  
yum install openldap-devel**  
yum install fping**  
```
