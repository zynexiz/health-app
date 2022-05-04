CREATE TABLE IF NOT EXISTS ha_uimode (
	id		int UNSIGNED NOT NULL AUTO_INCREMENT,
	uiname		varchar(10) NOT NULL,
	css		varchar(40) NOT NULL,
	PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS ha_units (
	unitid		int UNSIGNED NOT NULL AUTO_INCREMENT,
	name_short	varchar(10) NOT NULL,
	name_long	varchar(15) NOT NULL,
	unittype	varchar(20) NOT NULL,
	PRIMARY KEY (unitid)
);

CREATE TABLE IF NOT EXISTS ha_category (
	catid		int UNSIGNED NOT NULL AUTO_INCREMENT,
	name		varchar(25) NOT NULL,
	PRIMARY KEY (catid)
);

CREATE TABLE IF NOT EXISTS ha_goaltype (
	gtid		int UNSIGNED NOT NULL AUTO_INCREMENT,
	name		varchar(10) NOT NULL,
	unit		int UNSIGNED NOT NULL,
	category 	int UNSIGNED NOT NULL,
	PRIMARY KEY (gtid),
	FOREIGN KEY (unit) REFERENCES ha_units(unitid),
	FOREIGN KEY (category) REFERENCES ha_category(catid)
);

CREATE TABLE IF NOT EXISTS ha_lang (
	langid		int UNSIGNED NOT NULL AUTO_INCREMENT,
	lang		varchar(20) NOT NULL,
	code		varchar(12) NOT NULL,
	PRIMARY KEY (langid)
);

CREATE TABLE IF NOT EXISTS ha_roles (
	rid		int UNSIGNED NOT NULL AUTO_INCREMENT,
	userrole	varchar(10) NOT NULL,
	PRIMARY KEY (rid)
);

CREATE TABLE IF NOT EXISTS ha_users (
	uid		int UNSIGNED NOT NULL AUTO_INCREMENT,
	username	varchar(30) NOT NULL,
	passwd		varchar(25) NOT NULL,
	email		varchar(255) NOT NULL,
	urole		int UNSIGNED NOT NULL,
	PRIMARY KEY (uid),
	UNIQUE ha_unique_keys (username, email),
	FOREIGN KEY (urole) REFERENCES ha_roles(rid)
);

CREATE TABLE IF NOT EXISTS ha_userdata (
	udid		int UNSIGNED NOT NULL AUTO_INCREMENT,
	uid		int UNSIGNED NOT NULL,
	fname		varchar(30) NOT NULL,
	lname		varchar(30) NOT NULL,
	sex		int(1) NOT NULL,
	height		int(4) NOT NULL,
	ui_mode		int UNSIGNED NOT NULL,
	lang		int UNSIGNED NOT NULL,
	birthdate	date NOT NULL,
	PRIMARY KEY (udid),
	FOREIGN KEY (uid) REFERENCES ha_users(uid) ON DELETE CASCADE,
	FOREIGN KEY (ui_mode) REFERENCES ha_uimode(id),
	FOREIGN KEY (lang) REFERENCES ha_lang(langid)
);

CREATE TABLE IF NOT EXISTS ha_goals (
	gtid 		int UNSIGNED NOT NULL,
	uid		int UNSIGNED NOT NULL,
	goal		int NOT NULL,
	PRIMARY KEY (gtid, uid),
	FOREIGN KEY (gtid) REFERENCES ha_goaltype(gtid),
	FOREIGN KEY (uid) REFERENCES ha_users(uid) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS ha_logdata (
	ldid		int UNSIGNED NOT NULL AUTO_INCREMENT,
	ip		varchar(36) NOT NULL,
	browser		varchar(25) NOT NULL,
	platform	varchar(25) NOT NULL,
	timedate	datetime NOT NULL,
	page		varchar(255) NOT NULL,
	PRIMARY KEY (ldid)
);

CREATE TABLE IF NOT EXISTS ha_userlog (
	uid 		int UNSIGNED NOT NULL,
	ldid		int UNSIGNED NOT NULL,
	PRIMARY KEY (uid, ldid),
	FOREIGN KEY (uid) REFERENCES ha_users(uid) ON DELETE CASCADE,
	FOREIGN KEY (ldid) REFERENCES ha_logdata(ldid)
);

CREATE TABLE IF NOT EXISTS ha_healthtype (
	typeid		int UNSIGNED NOT NULL AUTO_INCREMENT,
	name		varchar(20) NOT NULL,
	category	int UNSIGNED NOT NULL,
	unit		int UNSIGNED NOT NULL,
	PRIMARY KEY (typeid),
	FOREIGN KEY (unit) REFERENCES ha_units(unitid),
	FOREIGN KEY (category) REFERENCES ha_category(catid)
);

CREATE TABLE IF NOT EXISTS ha_intensity (
	iid					int UNSIGNED NOT NULL AUTO_INCREMENT,
	typeid 		int UNSIGNED NOT NULL,
	name		varchar(10) NOT NULL,
	kcal		int UNSIGNED NOT NULL,
	PRIMARY KEY (iid),
	FOREIGN KEY (typeid) REFERENCES ha_healthtype(typeid)
);

CREATE TABLE IF NOT EXISTS ha_healthdata (
	hdid		int UNSIGNED NOT NULL AUTO_INCREMENT,
	uid		int UNSIGNED NOT NULL,
	healthtype	int UNSIGNED NOT NULL,
	amount		float NOT NULL,
	timestart	datetime NOT NULL,
	timeend		datetime NOT NULL,
	PRIMARY KEY (hdid),
	FOREIGN KEY (uid) REFERENCES ha_users(uid) ON DELETE CASCADE,
	FOREIGN KEY (healthtype) REFERENCES ha_healthtype(typeid)
);
