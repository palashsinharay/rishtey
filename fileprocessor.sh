#!/bin/bash
RUN=$(mysql -u root -parjun123 -B --disable-column-names rc_db -e "select COUNT(*) from takefile_exe")
if test $RUN -eq 0
then
PRO=$(mysql -u root -parjun123 -B --disable-column-names rc_db -e "select Count(*) from fb_process where status = 0")
if test $PRO -ne 0
then 
mysql -u root -parjun123 rc_db<<EOFMYSQL
SET @FBID := (SELECT fb_user_id FROM fb_process WHERE status = 0 LIMIT 1);
SELECT @FBID;
INSERT INTO takefile_exe (fb_user_id) VALUE(@FBID);
EOFMYSQL
php cli.php "FileToDb/takefile" >>fileprocessor.log
php cli.php "FileToDb/takefile_indirect" >>fileprocessor.log
else
echo nothing to process
fi
else
echo process running for $PRO
fi
