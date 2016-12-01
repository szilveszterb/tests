TimeOffManager - The leave management solution
==============================================

http://localhost/timeoffmanager/Login/LoginDisplay

What Is This?
-------------

The TimeOffManager is an MVC (Model/View/Control) pattern based web application
which can manage attendance management of companies.
The user authentication is handled by Google, so only a Google account is necessary
to start useing the software.
The TimeOffManager can handle three different type of user
- Guest
- Operator
- Manager

Every user has their own calendar but the functionality of the software depends on
the right level.
In Operator level the user has the opportunity to add,
modify, delete and drag and drop agenda items, nevertheless each Operator is allowed
to see its own holidays.
In Manager level the user can modify each status of holiday item which belongs to an
Operator who chose the current user as manager.
In Guest level the user has the possibility to review every holidays of all Operators.

Requirements Of TimeOffManager 
-----------------------------
The software use the following programs_
- Apache 2.4
- PHP 5+
- PostgreSQL 9+

How To Use The TimeOffManager 
-----------------------------

There are three main tasks to use the software:

1. If you do not have a Google account, let's do it!

2. Sign in with your Google user name and password and define the asked additinal
data. Your right level will be Operator.

3. After the first two steps a calendar will be visualized and you can create
holiday requests. Have fun!


How To Install The TimeOffManager 
---------------------------------

1. The most important step is to enable the mod_rewrite in Apache confuguration,
which is necessary for URL mapping.

2. Allow the URL mapping for this application in Apache httpd.conf file.
only.
<Directory "path where the software is">
    Options Indexes FollowSymLinks
    AllowOverride All
    Require all granted
</Directory>

3. Create a symbolic link from Apache htdocs folder to the application folder
which must be 'timeoffmanager'.

4. Configure the database from \TimeOffManager\data\database\:
   4.a. 1_create_role_and_database.sql - Runs the command separately from each other.
   4.b. 2_create_tables.sql - Runs this file, it will create the database tables.
   4.c. 3_load_data.sql - Runs this file if you would test users and holidays in database.
   You can find \TimeOffManager\docs\Google_user_with_password.txt which contains the test
   users name, email addresses and passwords.
   4.d. 4_drop_tables.sql -> Runs this file just in case if you would like to drop tables.

5. Open a browser and enter the following URL: http://[host]/timeoffmanager/Login/LoginDisplay
