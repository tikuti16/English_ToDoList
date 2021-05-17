CREATE TABLE tasks (
  id INT NOT NULL AUTO_INCREMENT,
  title VARCHAR(30),
  is_done TINYINT DEFAULT 0,
  PRIMARY KEY (id)
);

INSERT INTO tasks (title) VALUES ('The Office 1 Episode');

SELECT * FROM tasks;