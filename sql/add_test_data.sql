-- Lisää INSERT INTO lauseet tähän tiedostoon
INSERT INTO Account(username,password,name,sex,age,location,description,intrestedin,minage,maxage) VALUES ('test', 'test1234', 'testi',1,20, 'Helsinki', 'Testitestitesti', 1, 18,25);
INSERT INTO Vote(account_id,liked_account_id,status) VALUES (21, 22, 2);
INSERT INTO Vote(account_id,liked_account_id,status) VALUES (22, 21, 2);