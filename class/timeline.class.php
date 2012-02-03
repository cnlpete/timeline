<?php

require_once('db.class.php');

class Timeline {
	private $events;
	private $matrix;
	private $counter;
	private $start_year;
	private $end_year;
	private $events_output;
	private $details_output;

	function __Construct($start, $end) {
		$this->start_year = $start;
		$this->end_year = $end;
		$this->getEvents();
		$this->alignEvents();
		$this->createEventsOutput();
		$this->createDetailsOutput();
	}

	static function checkAndUpdateTable($insertData) {
		$a = Timeline::checkAndUpdateTableColorClasses($insertData);
		$b = Timeline::checkAndUpdateTableEvents($insertData);
		return ($a && $b);
	}

	static function checkAndUpdateTableColorClasses($insertData) {
		$tablediff = DB::checkForTable('colorclasses', 
			array('color', 'color_id'));
			//this has to be sorted ...
		
		//the table is in some wrong state .... need to update or create
		if ($tablediff === null) {
			//table is missing?
			Log::debug("we have to create the table");
			$sql = <<<EOD
CREATE TABLE IF NOT EXISTS `colorclasses` (
  `color_id` VARCHAR(10) NOT NULL,
  `color` int(8) NOT NULL,
  PRIMARY KEY (`color_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
EOD;
			$result = DB::queryAssoc($sql);
			if ($result && $insertData) {
				Timeline::insertColorClassTestData();
			}
			return $result;
		}
		else if (sizeof($tablediff) > 0) {
			//all fields in $tablediff are missing...
			Log::debug("the table is missing these fields: ".implode(",", $tablediff));
			$sql = "";
			if (in_array('color', $tablediff))
				$sql .= "ALTER TABLE `colorclasses` ADD `color` int(8) NOT NULL;";

			return DB::queryAssoc($sql);
		}
		else
			return true;
	}

	static function checkAndUpdateTableEvents($insertData) {
		$tablediff = DB::checkForTable('events', 
			array('colorclass', 'details', 'end_year', 'event_id', 'start_year', 'title'));
			//this has to be sorted ...
		
		//the table is in some wrong state .... need to update or create
		if ($tablediff === null) {
			//table is missing?
			Log::debug("we have to create the table");
			$sql = <<<EOD
CREATE TABLE IF NOT EXISTS `events` (
  `event_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(30) NOT NULL,
  `details` text NOT NULL,
  `start_year` int(4) NOT NULL,
  `end_year` int(4) NOT NULL,
  `colorclass` varchar(10) NOT NULL,
  PRIMARY KEY (`event_id`),
  KEY `start_year` (`start_year`),
  KEY `end_year` (`end_year`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
EOD;
			$result = DB::queryAssoc($sql);
			if ($result && $insertData) {
				Timeline::insertEventTestData();
			}
			return $result;
		}
		else if (sizeof($tablediff) > 0) {
			//all fields in $tablediff are missing...
			Log::debug("the table is missing these fields: ".implode(",", $tablediff));
			$sql = "";
			if (in_array('colorclass', $tablediff))
				$sql .= "ALTER TABLE `events` ADD `colorclass` varchar(10) NOT NULL;";
			if (in_array('details', $tablediff))
				$sql .= "ALTER TABLE `events` ADD `details` text NOT NULL;";
			if (in_array('title', $tablediff))
				$sql .= "ALTER TABLE `events` ADD `title` varchar(30) NOT NULL;";

			return DB::queryAssoc($sql);
		}
		else
			return true;
	}
	
	static function insertColorClassTestData() {
		$sql = <<<EOD
INSERT INTO `colorclasses` (`color_id`, `color`) VALUES
('colorOne', CONV('FF00FF', 16, 10)),
('colorTwo', CONV('00FFFF', 16, 10));
EOD;
		Log::debug("got: '".implode(",", DB::queryAssoc($sql))."'");
	}

	static function insertEventTestData() {
		$sql = <<<EOD
INSERT INTO `events` (`title`, `start_year`, `end_year`, `details`, `colorclass`) VALUES
('noch ein langes Ereignis &uuml;ber mehrere Jahre', 1940, 1944, '<p>Sieger Troph&auml;e / -Pokal mit Wunsch-Gravur - Der Pokal ist eines der beliebtesten Geburtstagsgeschenke f&uuml;r M&auml;nner und Frauen und l&auml;sst sich auch sehr gut zu runden Geburtstagen wie dem 30. 40. oder 50. Geburtstag verschenken.</p>', 'colorOne'),
('Test-Event', 1952, 0, '', 'colorTwo'),
('aufregend', 1942, 1942, 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.', 'colorOne'),
('ein event und so', 1938, 1943, 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.', 'colorOne'),
('das kanns nicht sein', 1940, 1941, 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.', 'colorOne'),
('wer das liest ist doof', 1936, 1941, 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.', 'colorOne'),
('party', 1940, 1940, 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Loremclita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.'),
('wo ist der bus', 1941, 1943, 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.', 'colorTwo'),
('tralalala', 1936, 1939, 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.', 'colorOne');
EOD;
		Log::debug("got: '".implode(",", DB::queryAssoc($sql))."'");
	}

	function getEvents() {
		$sql = <<<EOD
SELECT e.event_id, e.title, e.details, e.start_year, e.end_year, HEX(c.color) AS colorclass, c.color
FROM events AS e 
LEFT JOIN colorclasses AS c ON e.colorclass = c.color_id;
EOD;
		$this->events = DB::queryAssoc($sql);
		// sort events by start_year
		usort($this->events, array($this, 'custom_sort'));
		// count events
		$this->counter = sizeof($this->events);

		foreach ($this->events AS &$event)
			$event['colorclass'] = "".sprintf("%06X", $event['color']);
	}


	function alignEvents() {
		$this->matrix = array();
		for ($i=0; $i<$this->counter; $i++) {
			$year = $this->events[$i]['start_year'];
			$line = 0;
			// search for the first free row
			while ($this->matrix[$year][$line]) {
				$this->events[$i]['line']++;
				$line++;
			}
			// found free row -> mark columns (=years) of this row in matrix
			for ($j = $year; $j <= $this->events[$i]['end_year']; $j++) {
				$this->matrix[$j][$line] = true;
			}
		}
	}


	function createEventsOutput() {
		$c = Config::getInstance();

		$this->events_output = <<<EOD
\t<table id="timeline" class="bordered">
\t\t<thead>
\t\t\t<tr>\n
EOD;
		for ($year = $this->start_year; $year < $this->end_year; $year++) {
			$this->events_output .= "\t\t\t\t<th class=\"date\" style=\"width: ".$c->tl_column_width."px\">".$year."</th>\n";
		}
		$this->events_output .= <<<EOD
\t\t\t</tr>
\t\t</thead>
\t\t<tbody>
\t\t\t<tr id="content">\n
EOD;
		for ($year = $this->start_year; $year < $this->end_year; $year++) {
			$this->events_output .= "\t\t\t\t<td>\n";
			foreach ($this->events as $event) {
				if ($event['start_year'] == $year) {
					$event['length'] = max(1, $event['end_year'] - $event['start_year'] + 1) * $c->tl_column_width - $c->tl_event_padding_x;
					$event['line'] = $event['line'] * $c->tl_event_padding_y;
					$this->events_output .= "\t\t\t\t\t<span class=\"event\" ".
						"style=\"width:".$event['length']."px;".
							"top:".$event['line']."px;".
							"background:#".$event['colorclass'].";\" ".
						"data-event=\"".$event['event_id']."\" ".
						"data-title=\"".$event['title']."\" ".
						"data-width=\"".$event['length']."\"".
						">".$event['title']."</span>\n";
				}
			}
			$this->events_output .= "\t\t\t\t</td>\n";
		}
		$this->events_output .= <<<EOD
\t\t\t</tr>
\t\t</tbody>
\t</table>\n
EOD;
	}


	function createDetailsOutput() {
		foreach ($this->events as $event) {
			$this->details_output .= "\t<div id=\"event-".$event['event_id']."\" class=\"event-details\">".$event['details']."</div>\n";
		}
	}


	function output($data) {
		if ($data == 'events') echo $this->events_output;
		if ($data == 'details') echo $this->details_output;
	}


	function custom_sort($a, $b) {
		return $a['start_year'] > $b['start_year'];
	}
}
?>
