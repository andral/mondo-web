Name:		fzag-mondo-cron
Version:	1.2
Release:	5%{?dist}
Summary:	Mondo Rescue cron job
Vendor:		Flughafen Zuerich AG

Group:		Misc
License:	GPL
#URL:		
Source0:	%{name}-%version.tar.gz
BuildArch:	noarch
BuildRoot:	%(mktemp -ud %{_tmppath}/%{name}-%{version}-%{release}-XXXXXX)

#BuildRequires:	
Requires:	mondo

%description
Cron Job script to run Mondo Rescue regularly.

%prep
%setup -q


%build

%install
rm -rf %{buildroot}
install -m 0744 -d %{buildroot}/opt/fzag/
install -m 0744 -d %{buildroot}/opt/fzag/bin
install -m 0744 -d %{buildroot}/opt/fzag/etc
install -m 0744 -d %{buildroot}%{_sysconfdir}/cron.d
install -m 0744 mondo-cron.sh %{buildroot}/opt/fzag/bin/mondo-cron.sh 
install -m 0744 mondo-cron.conf %{buildroot}/opt/fzag/etc/mondo-cron.conf 
install -m 0744 fzag-mondo %{buildroot}%{_sysconfdir}/cron.d/fzag-mondo


%clean
rm -rf %{buildroot}


%files
%defattr(-,root,root,-)
%dir /opt/fzag
%dir /opt/fzag/bin
%dir /opt/fzag/etc
%attr(0744,root,root) /opt/fzag/bin/mondo-cron.sh
%config(noreplace) /opt/fzag/etc/mondo-cron.conf
%attr(0644,root,root) %{_sysconfdir}/cron.d/fzag-mondo

%changelog
* Wed Mar 06 2013 Sandro Roth - 1.2-5
- fixed cleanup in /dev/shm
* Mon Sep 24 2012 Sandro Roth - 1.2-3
- check if mount fails
- changed dirs to /dev/shm for scratch and tmp
* Tue Aug 14 2012 Sandro Roth - 1.2-2
- changed cronjob run time
* Tue Aug 14 2012 Sandro Roth - 1.2-2
- fixed $backup_dev variable
* Tue Aug 14 2012 Sandro Roth - 1.2-1
- moved variables to config file and update nfs4 path
* Wed Jul 25 2012 Sandro Roth - 1.1-2
- Updated to use NFSv4 and custom $PATH
* Tue Jul 24 2012 Sandro Roth - 1.1-1
- Updated cron job and error reporting
* Mon Jul 23 2012 Sandro Roth - 1.0-1
- Initial RPM
