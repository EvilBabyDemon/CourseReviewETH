- change sql to api
- change fts search to smth else
- remove old placeholders and stuff with real text

Done:
- page which autoloads reviews per sql
  - examplecourse
- Write a 404 page
- check if website url is course
- search function
 

Creating an FTS Table:
CREATE VIRTUAL TABLE SEARCH USING FTS4(COURSE, NAME);
INSERT INTO SEARCH SELECT * FROM COURSES;

SELECT * FROM SEARCH WHERE SEARCH MATCH ':input' ORDER BY rank limit 10;

curl rubberducky.vsos.ethz.ch:1855/add/ id + course + review

curl rubberducky.vsos.ethz.ch:1855/get/ course

curl rubberducky.vsos.ethz.ch:1855/getUser/ ID


curl -X POST -F 'name=linuxize' -F 'email=linuxize@example.com' https://example.com/contact.php