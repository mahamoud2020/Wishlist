<?php

namespace wishlist\fonction;


class Alert {

	public static function set($nomSession) {
		$_SESSION['alerte'] = $nomSession;
	}

	public static function get_succes_alert($nomsSession, $texte) {
		if (SELF::isAlert($nomSession)) {
			SELF::clear();
			echo '
			<div class="succ" data-closable>
				<p>' . $texte . '</p>
				<button class="verou-btn" aria-label="Dismiss alert" type="button" data-close>
				<span aria-hidden="true">&times;</span>
				</button>
			</div>';
		}
	}

	public static function get_warning_alert($nomSession, $texte) {
		if (SELF::isAlert($nomSession)) {
			SELF::clear();
			echo '
			<div class="verou-warning " data-closable>
				<p>' . $texte . '</p>
				<button class="verou-btn" aria-label="Dismiss alert" type="button" data-close>
				<span aria-hidden="true">&times;</span>
				</button>
			</div>';
		}
	}

	public static function get_info_alert($nomSession, $texte) {
		if (SELF::isAlert($nomSession)) {
			SELF::clear();
			echo '
			<div class="verou-first" data-closable>
				<p>' . $texte . '</p>
				<button class="verou-btn" aria-label="Dismiss alert" type="button" data-close>
				<span aria-hidden="true">&times;</span>
				</button>
			</div>';
		}
	}

	public static function get_error_alert($nomSession, $texte) {
		if (SELF::isAlert($nomSession)) {
			SELF::clear();
			echo '
			<div class="verou alert" data-closable>
				<p>' . $texte . '</p>
				<button class="verou-btn" aria-label="Dismiss alert" type="button" data-close>
				<span aria-hidden="true">&times;</span>
				</button>
			</div>';
		}
	}

	public static function get_secondary_lert($nomSession, $texte) {
		if (SELF::isAlert($nomSession)) {
			SELF::clear();
			echo '
			<div class="verou-secondly" data-closable>
				<p>' . $texte . '</p>
				<button class="verou-btn" aria-label="Dismiss alert" type="button" data-close>
				<span aria-hidden="true">&times;</span>
				</button>
			</div>';
		}
	}

	public static function isAlert($nomSession) {
		return isset($_SESSION['alerte']) && $_SESSION['alerte'] == $nomSession;
	}

	public static function clear() {
		$_SESSION['alerte'] = null;
	}

}
