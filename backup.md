##How to backup with linux server AWS
1. setting the AWS instance images for your backup
  * You have to check off the terminate your instance area
  
2. Use crond service
  * first set up directory for backup "mkdir /backup"
  * second set up .sh file to use cron service "vi backup.sh"
  * 3 move your backup.sh file to "/etc/cron.daily" directory
  * 3-1 you may check "vi /etc/crontab" if there is any commands you should type the cron command
  * 4 restart cron service "service crond restart"
  * 4-1 if you want to check .sh file runs correctly use "sh backup.sh"
  * 4-2 you should give authorization for "backup.sh"
     - "chmod 755 /etc/cron.daily/backup.sh" make help. 
