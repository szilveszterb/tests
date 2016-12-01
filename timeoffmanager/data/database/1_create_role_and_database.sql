CREATE ROLE timeoff_admin LOGIN
  ENCRYPTED PASSWORD 'md5bbb500b7afd8c367a6634edc0d001038'
  NOSUPERUSER INHERIT CREATEDB CREATEROLE;

CREATE DATABASE timeoffmanager
  WITH OWNER = timeoff_admin
       ENCODING = 'UTF8'
       TABLESPACE = pg_default
       LC_COLLATE = 'Hungarian, Hungary'
       LC_CTYPE = 'Hungarian, Hungary'
       CONNECTION LIMIT = -1;

CREATE OR REPLACE LANGUAGE plpgsql;  