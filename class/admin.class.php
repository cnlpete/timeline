<?php
/* 
 *
 * @link http://github.com/cnlpete/timeline
 * @author Hauke Schade <http://hauke-schade.de>
 *
 */

namespace Timeline;

require_once 'db.class.php';
require_once 'colorclasses.class.php';
require_once 'event.class.php';
require_once 'util.class.php';

class Admin {
	private $user;

	static function login($name, $pass) {
		$result = DB::queryAssocAtom("SELECT name FROM `users` WHERE `name` = '".$name."' AND `pass` = '".md5($pass)."' LIMIT 1;");

		if ($result) {
			Admin::setSession('admin', $result['name']);
			return true;
		} else {
			return false;
		}
	}

	static function logout() {
		Admin::unsetSession('admin');
	}

	static function loggedIn() {
		return isset($_SESSION['admin']);
	}

	private static function setSession($name, $value) {
		$_SESSION[$name] = $value;
	}

	private static function unsetSession($name) {
		unset($_SESSION[$name]);
	}


	static function getEvents() {
		$events = Events::getEvents($start, $end);

		$output = <<<EOD
<table class="bordered">
	<tr>
		<th width="90">Kategorie</th>
		<th>Ereignis</th>
		<th>bearbeiten</th>
	</tr>
EOD;

		foreach ($events as $event) {
			$output .= <<<EOD
<tr data-id="{$event->getId()}">
	<td class="colorclass_{$event->getColorclass()}">{$event->getColorDescription()}</td>
	<td>{$event->getTitle()} (#{$event->getId()})</td>
	<td>
		<button title="Ereignis anzeigen" class="button show"></button>
		<button title="Ereignis bearbeiten" class="button edit"></button>
		<button title="Ereignis l&ouml;schen" class="button delete"></button>
	</td>
</tr>
EOD;
		}
		$output .= "</table>";
		return $output;
	}

	static function showEvent($id) {
		return Event::getEventFromId($id)->toAdminRepresentation();
	}

	static function getInsertEventForm() {
		return Event::getForm();
	}

	static function saveEvent($data) {
		//-1 indicates, that there is no id yet ... so it will get inserted, when calling save
		$e = new Event(-1, $data['title'], $data['details'], $data['start'], $data['end'], $data['colorclass'], "", $data['type'], $data['image'], $data['source']);
		if ($e->save())
			return "Ereignis erfolgreich eingetragen!";
		else
			return "Ereignis konnte nicht gespeichert werden!";
	}

	static function editEvent($id) {
		$event = Event::getEventFromId($id);
		return Event::getForm($event);
	}

	static function updateEvent($id, $data) {
		$e = new Event($id, $data['title'], $data['details'], $data['start'], $data['end'], $data['colorclass'], "", $data['type'], $data['image'], $data['source']);
		if ($e->save())
			return "Ereignis ".$id." erfolgreich bearbeitet!";
		else
			return "Ereignis ".$id." konnte nicht bearbeitet werden!";
	}

	static function deleteEventConfirmation($id) {
		$e = Event::getEventFromId($id);
		if ($e && $e->delete())
			return "Ereignis ".$id." erfolgreich gel&ouml;scht!";
		else
			return "Ereignis ".$id." konnte nicht gel&ouml;scht werden!";
	}

}
?>
