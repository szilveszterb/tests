begin;       
CREATE TABLE user_rightlevel
(
  ur_id serial NOT NULL, -- Right identifier
  ur_active boolean NOT NULL, -- Inactive/active
  ur_name character varying(32) NOT NULL, -- Right level name
  ur_desc character varying(128), -- Description
  CONSTRAINT pk_ur_id PRIMARY KEY (ur_id),
  CONSTRAINT tuc_ur_1 UNIQUE (ur_name)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE user_rightlevel
  OWNER TO timeoff_admin;
COMMENT ON TABLE user_rightlevel
  IS 'Levels of users right';
COMMENT ON COLUMN user_rightlevel.ur_id IS 'Right identifier';
COMMENT ON COLUMN user_rightlevel.ur_active IS 'Inactive/active';
COMMENT ON COLUMN user_rightlevel.ur_name IS 'Right level name';
COMMENT ON COLUMN user_rightlevel.ur_desc IS 'Description';

CREATE TABLE users
(
  user_id serial NOT NULL,
  user_right integer NOT NULL,
  user_full_name character varying(128) NOT NULL,
  user_url text,
  user_email character varying(64) NOT NULL,
  user_reg_date timestamp with time zone NOT NULL DEFAULT now(),
  user_birth_year integer,
  user_manager_id integer,
  user_children_number integer,  
  user_background_color character varying(7) NOT NULL DEFAULT '#333333', 
  user_foreground_color character varying(7) NOT NULL DEFAULT '#F6F6F6',
  CONSTRAINT pk_user_id PRIMARY KEY (user_id),
  CONSTRAINT fk_user_right FOREIGN KEY (user_right)
      REFERENCES user_rightlevel (ur_id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE
)
WITH (
  OIDS=FALSE
);
ALTER TABLE users
  OWNER TO timeoff_admin;
	
CREATE TABLE time_offs
(
  to_id serial NOT NULL,
  user_id integer NOT NULL,
  to_date_from date NOT NULL,
  to_date_to date NOT NULL,
  to_status integer NOT NULL,
  to_desc character varying(128) NOT NULL,
  CONSTRAINT pk_to_id PRIMARY KEY (to_id),
  CONSTRAINT fk_user_id FOREIGN KEY (user_id)
      REFERENCES users (user_id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE
)
WITH (
  OIDS=FALSE
);
ALTER TABLE time_offs
  OWNER TO timeoff_admin;

--rollback;
commit;