CREATE SCHEMA realtime AUTHORIZATION sam;
CREATE TABLE realtime.t_template_tpl (tpl_id SERIAL NOT NULL, tpl_title VARCHAR(255) NOT NULL, tpl_body VARCHAR(255) NOT NULL, PRIMARY KEY(tpl_id));
