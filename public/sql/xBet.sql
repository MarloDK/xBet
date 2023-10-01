#CREATE
create database xbet;
CREATE TABLE `users` (
  `ID` int PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `Navn` char(255) DEFAULT NULL,
  `Pass` char(255) DEFAULT NULL,
  `img` mediumtext DEFAULT NULL,
  `admin` int DEFAULT 1
  #foreign key (`ID`) references `stats`(`userID`),
  #foreign key (`ID`) references `bets`(`userID`),
  #foreign key (`ID`) references `joined_bets`(`userID`),
  #foreign key (`ID`) references `joined_lotteries`(`userID`)
);

CREATE TABLE `stats` (
  `ID` int PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `UserID` int NOT NULL,
  `penge` int NOT NULL DEFAULT 10000,
  `betWins` int NOT NULL DEFAULT 0,
  `lotteryWins` int NOT NULL DEFAULT 0,
  `xp` int NOT NULL DEFAULT 0,
  `level` int NOT NULL DEFAULT 1,
  `firstTicket` bool NOT NULL DEFAULT FALSE,
  foreign key (`userID`) references `users`(`ID`)
);

CREATE TABLE `logs` (
  `ID` int PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `UserID` int NOT NULL,
  `log` mediumtext NOT NULL,
  `date` datetime NOT NULL,
  foreign key (`userID`) references `users`(`ID`)
);

CREATE TABLE `banned` (
	`ID` int PRIMARY KEY NOT NULL AUTO_INCREMENT,
    `UserID` int NOT NULL,
    `bannedGrund` char(255) NOT NULL,
    `endTid` datetime NOT NULL,
    foreign key (`userID`) references `users`(`ID`)
);

CREATE TABLE `bets` (
  `ID` int PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `userID` int NOT NULL,
  `titel` char(255) NOT NULL,
  `slutTid` datetime NOT NULL,
  `joinInPenge` int NOT NULL DEFAULT 0,
  `winstate` char(255) NOT NULL DEFAULT "WAIT",
  `valg1` char(255) NOT NULL DEFAULT "Ja",
  `valg2` char(255) NOT NULL DEFAULT "Nej",
  #foreign key (`ID`) references `joined_bets`(`betID`)
  foreign key (`userID`) references `users`(`ID`)
);

CREATE TABLE `joined_bets` (
  `ID` int PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `userID` int NOT NULL,
  `betID` int NOT NULL,
  `chosenValg` char(5) NOT NULL DEFAULT "valg1",
  `joinedPenge` int NOT NULL,
  foreign key (`userID`) references `users`(`ID`),
  foreign key (`betID`) references `bets`(`ID`)
);

CREATE TABLE `lottery` (
  `ID` int PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `lotteryPenge` int NOT NULL DEFAULT 10000,
  `userWinChance` double NOT NULL DEFAULT 0.25,
  `maxTickets` int NOT NULL DEFAULT 1,
  `ticketPrice` int NOT NULL DEFAULT 1000,
  `slutTid` datetime NOT NULL,
  `winstate` char(255) NOT NULL DEFAULT "WAIT"
  #foreign key (`ID`) references `joined_lotteries`(`lotteryID`)
);

CREATE TABLE `joined_lotteries` (
  `ID` int PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `lotteryID` int NOT NULL,
  `userID` int NOT NULL,
  `tickets` int NOT NULL DEFAULT 1,
  #foreign key (`userID`) references `users`(`ID`)
  foreign key (`lotteryID`) references `lottery`(`ID`)
);

CREATE TABLE `nyheder` (
  `ID` int PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `dato` char(255) NOT NULL,
  `titel` char(255) NOT NULL,
  `nyhed` mediumtext NOT NULL,
  `forfatter` tinytext NOT NULL
);

CREATE TABLE `vejledninger` (
  `ID` int PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `titel` char(255) NOT NULL,
  `vejledning` mediumtext NOT NULL
);

CREATE TABLE `faq` (
  `ID` int PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `question` mediumtext NOT NULL,
  `answer` mediumtext NOT NULL
);

#ALTER TABLE `users` ADD img mediumtext default NULL;
#ALTER TABLE `lottery` add `slutTid` datetime NOT NULL;

#ALTER TABLE `users` ADD FOREIGN KEY (`ID`) REFERENCES `stats` (`UserID`);

#ALTER TABLE `users` ADD FOREIGN KEY (`ID`) REFERENCES `bets` (`userID`);

#ALTER TABLE `users` ADD FOREIGN KEY (`ID`) REFERENCES `joined_bets` (`userID`);

#ALTER TABLE `bets` ADD FOREIGN KEY (`ID`) REFERENCES `joined_bets` (`betID`);

#ALTER TABLE `lottery` ADD FOREIGN KEY (`ID`) REFERENCES `joined_lotteries` (`lotteryID`);

#ALTER TABLE `users` ADD FOREIGN KEY (`ID`) REFERENCES `joined_lotteries` (`userID`);

drop database xbet;

drop table users;
drop table stats;
drop table bets;
drop table nyheder;
drop table vejledninger;
drop table joined_bets;
show tables;

# Insert Users:
insert into users(Navn, Pass, admin) values ("TEST", "TEST", 2);

# Insert:
insert into nyheder(dato, titel, nyhed, forfatter) values (date_format(CURRENT_TIMESTAMP(), "%H:%i %d-%m-%Y"), "TEST", "TEST\\nTEST2\\nTEST3", "TESTPERSON");
insert into vejledninger(titel, vejledning) values ("TEST", "TEST\\nTEST2\\nTEST3");
insert into faq(question, answer) values ("Hej med", "dig");

insert into stats(UserID, betWins, lotteryWins) values (1, 1, 2);
insert into stats(UserID) values (1);
insert into lottery(lotteryPenge) values (100000);
insert into bets(userID, slutTid, joinInPenge) values (1, "2022-09-12 22:55", 2000);

# Update lottery
update lottery set lotterypenge = 2000, userWinChance = 0.50 where ID = 5 limit 1;
update lottery set lotterypenge = 5000, userWinChance = 0.25 where ID = 5 limit 1;
update bets set userID = 1, slutTid = "2022-09-15 22:00:00", joinInPenge = 5000, winstate = "TRUE" where ID = 4 limit 1;

# Update stats
update stats set xp = 0, level = 1, betWins = 0, lotteryWins = 0 where ID = 1;

# Select:
select * from nyheder;
select * from vejledninger;
select * from faq;

delete from nyheder where titel = "" limit 1;
delete from vejledninger where titel = "" limit 1;
delete from faq where answer = "" limit 1;

select ID, slutTid, slutTid < curdate() from bets;
select * from users;
select * from stats;
select * from lottery;
select * from bets;
select * from joined_bets;
select * from joined_lotteries;

select * from lottery order by ID desc limit 1;
select stats.*, users.Navn from stats join users on stats.UserID = users.ID;
select penge from stats where UserID = 1;

insert into joined_bets (userID, betId, chosenValg, joinedPenge) values (2,12,'valg1','300');

select users.Navn, stats.penge, stats.betWins, stats.level from stats join users on stats.UserID = users.ID limit 10;