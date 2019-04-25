-- 球队表
create table 'live_team'(
	'id' tinyint unsigned not null auto_increment,
	'name' varchar(20) not null default '', -- 球队名称
	'image' varchar(20) not null default '', -- 不到非常时刻都不要有null值，字符串给默认为'',int给默认为0
	'type' tinyint(1) unsigned not null default 0,
	'create_time' int(10) unsigned not null default 0,
	'update_time' int(10) unsigned not null default 0,
	primary key ('id')
)engine=InnoDB AUTO_INCREMENT=1 default charset=utf8;