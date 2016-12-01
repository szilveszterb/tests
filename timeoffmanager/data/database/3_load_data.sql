begin;
INSERT INTO user_rightlevel(ur_active, ur_name, ur_desc) VALUES (true, 'guest', 'Guest privilege to review all holidays.');
INSERT INTO user_rightlevel(ur_active, ur_name, ur_desc) VALUES (true, 'operator', 'Operator privilege to require holidays.');
INSERT INTO user_rightlevel(ur_active, ur_name, ur_desc) VALUES (true, 'manager', 'Manager privilege to consider holidays.');

INSERT INTO users(user_right, user_full_name, user_url, user_email, user_birth_year, user_children_number) VALUES (3, 'Manager TimeOffManager', 'https://lh3.googleusercontent.com/--OeahRrue2g/AAAAAAAAAAI/AAAAAAAAAAw/PkZi_HOHdqo/s96-c/photo.jpg', 'timeoffmanagermanager@gmail.com', 1980, 0);
INSERT INTO users(user_right, user_full_name, user_url, user_email, user_birth_year, user_manager_id, user_children_number) VALUES (3, 'Manager2 TimeOffManager', 'https://lh6.googleusercontent.com/-Yyqi9YmUTzk/AAAAAAAAAAI/AAAAAAAAAAw/Axz3OxlARbU/s96-c/photo.jpg', 'timeoffmanagermanager2@gmail.com', 1990, 1, 0);
INSERT INTO users(user_right, user_full_name, user_url, user_email, user_birth_year, user_manager_id, user_children_number, user_background_color, user_foreground_color) VALUES (2, 'Operator TimeOffManager', 'https://lh3.googleusercontent.com/-ucyG_CKmUWY/AAAAAAAAAAI/AAAAAAAAAAw/4zyJ5x2hHWw/s96-c/photo.jpg', 'timeoffmanageroperator@gmail.com', 1960, 1, 2, '#554fcc', '#F6F6F6');
INSERT INTO users(user_right, user_full_name, user_url, user_email, user_birth_year, user_manager_id, user_children_number, user_background_color, user_foreground_color) VALUES (2, 'Operator2 TimeOffManager', 'https://lh6.googleusercontent.com/-jFe2_x1EoQM/AAAAAAAAAAI/AAAAAAAAAAw/7PUtajDq4mg/s96-c/photo.jpg', 'timeoffmanageroperator2@gmail.com', 1970, 1, 3, '#15961c', '#F6F6F6');
INSERT INTO users(user_right, user_full_name, user_url, user_email, user_birth_year, user_manager_id, user_children_number, user_background_color, user_foreground_color) VALUES (2, 'Operator3 TimeOffManager', 'https://lh3.googleusercontent.com/-dn1Ktr2BIKk/AAAAAAAAAAI/AAAAAAAAAAs/0msuUnlzz8o/s96-c/photo.jpg', 'timeoffmanageroperator3@gmail.com', 1970, 2, 1, '#333333', '#F6F6F6');
INSERT INTO users(user_right, user_full_name, user_url, user_email, user_birth_year, user_manager_id, user_children_number) VALUES (1, 'Guest TimeOffManager', 'https://lh5.googleusercontent.com/-1EUtDo8m9e8/AAAAAAAAAAI/AAAAAAAAAAw/5fZf2MBCve8/s96-c/photo.jpg', 'timeoffmanagerguest@gmail.com', 1940, 1, 3);
INSERT INTO users(user_right, user_full_name, user_url, user_email, user_birth_year, user_manager_id, user_children_number) VALUES (1, 'Guest2 TimeOffManager', 'https://lh3.googleusercontent.com/-3hx-r77cfhw/AAAAAAAAAAI/AAAAAAAAAAs/D7mpjK4GCg0/s96-c/photo.jpg', 'timeoffmanagerguest2@gmail.com', 1950, 1, 2);

INSERT INTO time_offs(user_id, to_date_from, to_date_to, to_status, to_desc) VALUES (3, '2015-01-01', '2015-01-11', 0, 'Holidays at previous workplace.');
INSERT INTO time_offs(user_id, to_date_from, to_date_to, to_status, to_desc) VALUES (4, '2015-01-01', '2015-01-13', 0, 'Holidays at previous workplace.');
INSERT INTO time_offs(user_id, to_date_from, to_date_to, to_status, to_desc) VALUES (5, '2015-01-01', '2015-01-15', 0, 'Holidays at previous workplace.');

INSERT INTO time_offs(user_id, to_date_from, to_date_to, to_status, to_desc) VALUES (3, '2015-10-12', '2015-10-15', 1, 'Autumn holiday.');
INSERT INTO time_offs(user_id, to_date_from, to_date_to, to_status, to_desc) VALUES (3, '2015-10-23', '2015-10-23', 1, 'Wedding.');
INSERT INTO time_offs(user_id, to_date_from, to_date_to, to_status, to_desc) VALUES (3, '2015-11-16', '2015-11-20', 1, 'Traveling.');
INSERT INTO time_offs(user_id, to_date_from, to_date_to, to_status, to_desc) VALUES (3, '2015-12-21', '2015-12-24', 1, 'Christmas.');

INSERT INTO time_offs(user_id, to_date_from, to_date_to, to_status, to_desc) VALUES (4, '2015-10-08', '2015-10-09', 1, 'Long weekend.');
INSERT INTO time_offs(user_id, to_date_from, to_date_to, to_status, to_desc) VALUES (4, '2015-10-29', '2015-10-30', 1, 'Business trip.');
INSERT INTO time_offs(user_id, to_date_from, to_date_to, to_status, to_desc) VALUES (4, '2015-11-16', '2015-11-24', 1, 'Skiing.');
INSERT INTO time_offs(user_id, to_date_from, to_date_to, to_status, to_desc) VALUES (4, '2015-12-21', '2015-12-24', 1, 'Christmas.');

INSERT INTO time_offs(user_id, to_date_from, to_date_to, to_status, to_desc) VALUES (5, '2015-10-13', '2015-10-13', 1, 'Medical checkup.');
INSERT INTO time_offs(user_id, to_date_from, to_date_to, to_status, to_desc) VALUES (5, '2015-10-23', '2015-10-23', 1, 'Extension course.');
INSERT INTO time_offs(user_id, to_date_from, to_date_to, to_status, to_desc) VALUES (5, '2015-11-27', '2015-12-02', 1, 'Traveling.');
INSERT INTO time_offs(user_id, to_date_from, to_date_to, to_status, to_desc) VALUES (5, '2015-12-21', '2015-12-24', 1, 'Christmas.');


--rollback;
commit;