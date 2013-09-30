#!/bin/bash

export PATH=/sbin:/bin:/usr/sbin:/usr/bin

# let's make sure we're not a vm
if [ "`virt-what`"  = "vmware" ]; then exit 0; fi

CONFIG_FILE=/opt/fzag/etc/mondo-cron.conf

if [[ -f $CONFIG_FILE ]]; then
        . $CONFIG_FILE
else
        echo "config file $CONFIG_FILE not found! aborting.."
        exit 1;
fi

backup_dev=`pvs --noheading | grep sysvg1 | awk '{print $1}'`

if [[ "$backup_dev" == *cciss* ]];
then
        backup_dev=`echo $backup_dev | sed 's/p[0-9]*//'`
else
        backup_dev=`echo $backup_dev | sed 's/[0-9]*//g'`
fi

mkdir $backup_mount
mount -t nfs4 $backup_server:$backup_path $backup_mount
if [ $? -ne 0 ]; then echo "Mount failed on $(hostname -s)" | mail -s "Mondo Rescue Failed" root; rmdir $backup_mount; exit 1; fi
mkdir $backup_dir

while getopts d opt
do
    case $opt in
    d)  debug=yes;;
    *)  exit 1;;
    esac
done

if [ ! -z $debug ];
then
    mondoarchive -OVi -d $backup_dir -I $backup_dev -p `hostname -s` -S /dev/shm -T /dev/shm -G -N -s 4g
else
    mondoarchive -OVi -d $backup_dir -I $backup_dev -p `hostname -s` -S /dev/shm -T /dev/shm -G -N -s 4g > $logfile 2>&1
fi
if [ $? -ne 0 ]; then cat /var/log/mondoarchive.log | mail -s "Mondo Rescue Failed" root; fi
if [ -e /var/cache/mindi/mondorescue.iso ]; then cp /var/cache/mindi/mondorescue.iso $backup_dir; fi
if [ -e /var/log/mondoarchive.log ]; then cp /var/log/mondoarchive.log $backup_dir; fi
if [ -e $logfile ]; then cp $logfile $backup_dir; fi

umount $backup_mount
rmdir $backup_mount
rm -rf /dev/shm/mondo.tmp*
rm -rf /dev/shm/mondo.scratch*
rm -f $logfile
