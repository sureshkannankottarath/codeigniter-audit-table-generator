
###################
What is Audit Table
###################

An audit table is a table that contains the full history of rows. I.e. based on the primary key of a row in the source table one can query the full history of the row in the audit table and find out when the row was created, modified (possibly many times), and maybe eventually deleted

************************
Pupose of Audit Tables
************************
The audit tables  **track changes and deletions made to your data at the database level**. When enabled, updates and deletions to every type of record are tracked by the database and stored separately for faster querying and reporting.

*******************
Notes
*******************

 1. The auditing table (primary table) must need updated by, updated
    on,created by and created on information
 2. Don't start table names with 'audit_' as prefix
 3. Before running this application must need to establish database
    connectivity through - application/config/database.php
 4. Run following query in connected database:

	

    CREATE TABLE audit_tables_history (
                audit_id int(11) NOT NULL AUTO_INCREMENT,
                audit_table_name varchar(100) NOT NULL,
                parent_table varchar(100) NOT NULL,
                created_on datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (audit_id)
              )
              ENGINE = INNODB,
              AUTO_INCREMENT = 1,
              CHARACTER SET latin1,
              COLLATE latin1_swedish_ci,
              COMMENT = "CI audit table generator - audit table creation history"

***********
Resources
***********

-  [How to Run Codeigniter Project](https://www.binaryboxtuts.com/php-tutorials/how-to-install-codeigniter-3-in-xampp-easy-tutorial/)
-  Inspired From [# Awesome feature of MySQL that you must Try](https://www.youtube.com/watch?v=SF3h7fydEcY)
