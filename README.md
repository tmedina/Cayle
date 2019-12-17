# Cayle

Cayle is a PHP web application for managing business operations for Nuçi's Space, a non-profit organization.

The application uses a calendar view to create, read, update and delete practice room reservations for musicians. It also allows users manage equipment rentals, food and refreshments, add these to a customer's final bill and check out.

This application was developed as a class project by The University of Georgia's Master of Information Science Program.

Nuçi's Space is a non-profit organization dedicated to promoting mental health awareness and suicide prevention among the music community of Athens, GA.

The software is named for Cayle Bywater, a Nuçi's Space volunteer and member of the UGA MIS class who initiated the project. Cayle was lost to suicide before the project was completed.

Installation
============

Requires XAMPP 1.7 on a case-insensitive filesystem (tested on Windows XP and Windows 7).

Make sure to add the mysql username/password of your installation in the file include/config.inc.

In xampp/php/ replace the stock php.ini file with the one from the repo's environment/ directory.

Copy the directory xampp/MYSQL_BACKUPS from the repo's environment/ directory to the xampp/ directory of the installation.

Backup Script
=============
Unzip and copy the phpMyBackupPro directory from environment/ to the xampp/htdocs/ directory of the installation.

Cayle uses phpMyBackupPro for backups. Make sure the phpMyBackupPro directory is unzipped into the htdocs folder, then 
navigate in a browser to <server_address>/phpMyBackupPro/config.php and ensure the MySQL credentials are entered into the 
appropriate fields and click on save.

To ensure nightly backups, use the following script in the Task Manager (windows)

`
C:\xampp\php\php.exe C:\xampp\htdocs\phpMyBackupPro\backup.php nucis_space_tables_prod 1 1 1 1 C:\xampp\MYSQL_BACKUPS\father\
`

