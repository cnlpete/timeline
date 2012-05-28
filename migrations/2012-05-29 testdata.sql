INSERT INTO `colorclasses` (`color_id`, `css_code`, `description`) VALUES
('Politik', 'background-image: linear-gradient(top, #ee0000, #aa0000);
  background-image: -o-linear-gradient(top, #ee0000, #aa0000);
  background-image: -ms-linear-gradient(top, #ee0000, #aa0000);
  background-image: -moz-linear-gradient(top, #ee0000, #aa0000);
  background-image: -webkit-linear-gradient(top, #ee0000, #aa0000);
  color: #fff;
  text-shadow: 0 1px 0 #000;','Politik'),
('Gesellschaft', 'background-image: linear-gradient(top, #eeee00, #aaaa00);
  background-image: -o-linear-gradient(top, #eeee00, #aaaa00);
  background-image: -ms-linear-gradient(top, #eeee00, #aaaa00);
  background-image: -moz-linear-gradient(top, #eeee00, #aaaa00);
  background-image: -webkit-linear-gradient(top, #eeee00, #aaaa00);
  color: #fff;
  text-shadow: 0 1px 0 #000;','Gesellschaft'),
('Wissenschaft', 'background-image: linear-gradient(top, #00ee00, #00aa00);
  background-image: -o-linear-gradient(top, #00ee00, #00aa00);
  background-image: -ms-linear-gradient(top, #00ee00, #00aa00);
  background-image: -moz-linear-gradient(top, #00ee00, #00aa00);
  background-image: -webkit-linear-gradient(top, #00ee00, #00aa00);
  color: #fff;
  text-shadow: 0 1px 0 #000;','Wissenschaft'),
('Religion', 'background-image: linear-gradient(top, #0066ee, #0033aa);
  background-image: -o-linear-gradient(top, #0066ee, #0033aa);
  background-image: -ms-linear-gradient(top, #0066ee, #0033aa);
  background-image: -moz-linear-gradient(top, #0066ee, #0033aa);
  background-image: -webkit-linear-gradient(top, #0066ee, #0033aa);
  color: #fff;
  text-shadow: 0 1px 0 #000;','Religion');
  
INSERT INTO `events` (`title`, `startdate`, `enddate`, `details`, `colorclass`,  `type`,  `image`) VALUES
('noch ein langes Ereignis &uuml;ber mehrere Jahre', '1940-03-01', '1944-03-01', 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Loremclita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.', 'Religion', 'text', 'bild'),
('Test-Event', '1952-03-01', '0000-00-00', 'dieses event hat kein enddatum', 'Politik', 'image', 'test.jpg'),
('aufregend', '1942-00-00', '1942-00-00', 'Dieses Event hört im gleichen jahr auf, wie es anfängt. es hat zudem keine moats angabe, bzw. tages angabe', 'Wissenschaft', 'quote', ''),
('ein event und so', '1938-03-01', '1943-03-01', 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.', 'Religion', 'quote', ''),
('das kanns nicht sein', '1940-03-01', '1941-03-01', 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.', 'Religion', 'text', 'bild'),
('wer das liest ist doof', '1936-03-01', '1941-03-01', 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.', 'Politik', 'text', 'bild'),
('party', '1940-03-01', '1940-03-01', 'Dieses Event ist nur einen Tag lang', 'Gesellschaft', 'image', 'test.jpg'),
('partylong', '1941-03-00', '1940-03-00', 'Dieses Event ist nur einen Monat lang', 'Gesellschaft', 'quote', ''),
('wo ist der bus', '1941-03-01', '1943-03-01', 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.', 'Religion', 'text', ''),
('tralalala', '1936-03-01', '1939-03-01', 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.', 'Wissenschaft', 'quote', 'bild');
