-- Lisää CREATE TABLE lauseet tähän tiedostoon
CREATE TABLE Account(
  id SERIAL PRIMARY KEY, -- SERIAL tyyppinen pääavain pitää huolen, että tauluun lisätyllä rivillä on aina uniikki pääavain. Kätevää!
  username varchar(50) NOT NULL, -- Muista erottaa sarakkeiden määrittelyt pilkulla!
  password varchar(50) NOT NULL,
  name varchar(50) NOT NULL,
  sex INTEGER,
  age INTEGER,
  location varchar(50) NOT NULL,
  description varchar(2000) NOT NULL,
  intrestedIn INTEGER,
  minAge INTEGER,
  maxAge INTEGER
);

CREATE TABLE Like(
  account_id INTEGER REFERENCES Account(id),
  liked_account_id INTEGER REFERENCES Account(id),
  status INTEGER
);


CREATE TABLE Conversation(
  id SERIAL PRIMARY KEY
);

CREATE TABLE Match(
  id SERIAL PRIMARY KEY,
  account_1_id INTEGER REFERENCES Account(id),
  account_2_id INTEGER REFERENCES Account(id),
  conversation_id INTEGER REFERENCES Conversation(id)
);


CREATE TABLE Message(,
  conversation_id INTEGER REFERENCES Conversation(id),
  content varchar(1000) NOT NULL,
  time DATE
);

CREATE TABLE Hashtag(,
   name varchar(100) NOT NULL
);