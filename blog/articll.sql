--文章表
create table tn_article(
	id int unsigned auto_increment primary key comment '主键id',
	title varchar(60) not null default '' comment '标题',
	content text not null comment '内容',
	add_time int unsigned not null default 0 comment '创建时间',
	user_id int unsigned not null default 0 comment '用户id',
	category_id int unsigned not null default 0 comment '分类id',
	summary varchar(255) not null default '' comment '摘要',
	cover varchar(255) not null default '' comment '封面',
	status enum ('publish','save') comment '状态',
	is_delete tinyint unsigned not null default 0 comment '是否删除',
	tags varchar(255) not null default '' comment '标签'
)engine=myisam charset=utf8;

insert into tn_article values(
	1,'微信营销','微信营销的独孤九剑',unix_timestamp(),001,1,'微信营销的简介',
	'1.jpg','publish',default,'微信营销'
);

insert into tn_article values(
	2,'微博营销','微博营销的精髓',unix_timestamp(),002,1,'微博营销的简介',
	'2.jpg','publish',default,'微信营销'
);

insert into tn_article values(
	3,'QQ营销','QQ营销的独孤九剑',unix_timestamp(),003,1,'QQ营销的简介',
	'3.jpg','publish',default,'QQ营销'
);

insert into tn_article values(
	4,'论坛营销','论坛营销的精髓',unix_timestamp(),004,1,'论坛营销的简介',
	'4.jpg','publish',default,'论坛营销'
);

insert into tn_article values
(null,'多彩生活1','生活是一本书',unix_timestamp(),5,2,'简爱的意义',
'5.jpg','publish',default,'悠然见南山'),
(null,'多彩生活2','我只闻到你的香水，却没看到你的汗水',unix_timestamp(),6,2,'聚美优品',
'6.jpg','publish',default,'你有你的原则'),
(null,'多彩生活3','你笑我一无所有不配去爱',unix_timestamp(),7,2,'我可怜你只能等待',
'7.jpg','publish',default,'我有我的规则');

insert into tn_article values
(null,'你的世界1','在爱情的列车上如果你要提前下车',unix_timestamp(),8,3,'请不要叫醒装睡的我',
'1.jpg','publish',default,'那样我可以假装睡着不知道你已经离开'),
(null,'你的世界2','我们被权威漠视',unix_timestamp(),9,3,'也要为自己的天分保持骄傲',
'2.jpg','publish',default,'坚持'),
(null,'你的世界3','我们被房价羞辱',unix_timestamp(),10,3,'也要让简陋的现实变得温暖',
'3.jpg','publish',default,'蜗居');


insert into tn_article values
(null,'可惜不是你1','人人说年轻人烂，男孩无下限，女孩无节操',unix_timestamp(),8,4,'人人说不等于人人说',
'1.jpg','publish',default,'除了宅男就是花痴'),
(null,'可惜不是你2','人人说年轻人赞',unix_timestamp(),9,4,'男孩有创意',
'2.jpg','publish',default,'女孩够独立'),
(null,'可惜不是你3','宁愿二也不装',unix_timestamp(),10,4,'能为梦想豁出去',
'3.jpg','publish',default,'人人说不等于人人说');


create table tn_user(
	id int unsigned auto_increment primary key,
	username varchar(64) not null default '' unique,
	password varchar(32) not null default '',
	nickname varchar(64) not null default '',
	logo varchar(128) not null default '',
	create_at int unsigned not null default 0
)charset=utf8 engine=MyISAM;

insert into tn_user values
(null,'zmj',md5('123456'),'花落北海道','1.jpg',unix_timestamp());

create table category(
	id int unsigned auto_increment primary key,
	title varchar(64) not null default '',
	order_number smallint not null default 100
)engine=myisam charset=utf8;

insert into category values
(null,'PHP开发','100'),
(null,'JAVA开发','101'),
(null,'.NET开发','102'),
(null,'C开发','103');


create table `session`(
	session_id int unsigned not null primary key,
	session_data text not null,
	last_time int not null default 0
);