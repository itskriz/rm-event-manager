<?php
/*
	Class: RM_Event
*/


class RM_Event {

	/*
	public function get_acf_fields() {
		$acf_fields = get_field_objects($this->ID);
		foreach ($acf_fields as $key => $value) {
			if (!isset($this->acf_fields)) {
				$this->acf_fields = array();
			}
			$acf_field_type = $acf_fields[$key]['type'];
			if (!isset($this->acf_fields[$acf_field_type])) {
				$this->acf_fields[$acf_field_type] = array();
			}
			array_push($this->acf_fields[$acf_field_type], $key);
		}
	}
	*/

	public function debug_acf_fields() {
		return get_field_objects($this->ID);
	}

	public function get_acf_fields() {
		$acf_fields = get_field_objects($this->ID);
		foreach ($acf_fields as $key => $value) {
			if (!isset($this->acf_fields)) {
				$this->acf_fields = array();
			}
			$type = $acf_fields[$key]['type'];
			if (!isset($this->acf_fields[$type])) {
				$this->acf_fields[$type] = array();
			}
			array_push($this->acf_fields[$type], $key);
		}
	}

//// Event Series
	public function set_event_series() {
		// Check for event series
		if (have_rows('rm_event_series', $this->ID)) {
			// Set up event series
			$this->event_series = array();
			$this->event_series_dates = array();
			// Validation to prevent duplicate events
			$event_series_duplicates = array();

			while (have_rows('rm_event_series', $this->ID)) {
				the_row();

				$event_series = array();

				// Get initial start and end dates
				$event_series['date'] = get_field('rm_event_date', $this->ID);

				// Get Time for Event Series
				if (get_sub_field('rm_event_series_sametime')) {
					$get_event_time = get_field('rm_event_time', $this->ID);
				} else {
					$get_event_time = get_sub_field('rm_event_series_time');
				}
				$event_series['time'] = array(
					'start'	=> $get_event_time['start'],
					'end'		=> $get_event_time['end'],
				);

				// Check for All-Day Event
				if (get_field('rm_event_allday', $this->ID)) {
					$event_series['allday'] = 1;
					$event_series['time'] = array(
						'start'	=> '00:00:00',
						'end'		=> '23:59:59',
					);
				} else {
					$event_series['allday'] = null;
				}

				// Get End for event series
				$event_series['start'] = $event_series['date']['start'];
				$event_series['end'] = get_sub_field('rm_event_series_end');

				// Get series fequency, 0-3, once, daily, weekly, monthly
				$event_series['frequency'] = get_sub_field('rm_event_series_freq');
				// Get frequency interval depending on frequency
				if (0 == $event_series['frequency']) {
					// Once
					$event_series['interval'] = get_sub_field('rm_event_series_once');
				} elseif (1 == $event_series['frequency']) {
					// Daily
					$event_series['interval'] = get_sub_field('rm_event_series_daily');
				} elseif (2 == $event_series['frequency']) {
					// Weekly
					$event_series['interval'] = get_sub_field('rm_event_series_weekly') * 7;
				} elseif (3 == $event_series['frequency']) {
					// Monthly
					$event_series['interval'] = get_sub_field('rm_event_series_monthly');
				}


				// Calculate date periods for frequencies not 0
				if (0 != $event_series['frequency']) {
					// Set DateTime Objs
					$event_series_start = new DateTime($event_series['date']['start']);
					$event_series_end = new DateTime(get_sub_field('rm_event_series_end'));
					$event_series_end = $event_series_end->modify('+1 day');
					// Set interval
					$event_series['interval'] = 'P' . $event_series['interval'];
					if (1 == $event_series['frequency'] || 2 == $event_series['frequency']) {
						$event_series['interval'] .= 'D';
					} elseif (3 == $event_series['frequency']) {
						$event_series['interval'] .= 'M';
					}
					$event_series['interval'] = new DateInterval($event_series['interval']);
					// Set daterange
					$event_series['daterange'] = new DatePeriod($event_series_start, $event_series['interval'], $event_series_end);
					// Loop daterange
					foreach ($event_series['daterange'] as $event_date) {
						if (!isset($event_series['dates'])) {
							$event_series['dates'] = array();
						}


						// Set event start date
						$event_series_date = array();
						$event_series_date['start'] = $event_date->format('Y-m-d');

						// Set event end date
						// Check if event end is on different day than event start
						$check_event_start = new DateTime($event_series['date']['start']);
						$check_event_end = new DateTime($event_series['date']['end']);
						$check_allday = get_field('rm_event_allday', $this->ID);
						// If not an allday event and the end day is greater than the start day.
						if (!$check_allday && ($check_event_end > $check_event_start)) {
							$event_date_diff = $check_event_start->diff($check_event_end)->format('%a');
							$event_series_date['end'] = $event_date->modify('+'.$event_date_diff.'day')->format('Y-m-d');
						} else {
							$event_series_date['end'] = $event_series_date['start'];
						}

						/* PROBABLY GOING TO NEED SOME LOGIC IN HERE THAT CHECKS FOR EVENTS AT NEW TIMES THAT DON'T OVERFLOW INTO THE NEXT DAY */

						// Attach times to dates
						$event_series_date['start'] = $event_series_date['start'] . ' ' . $event_series['time']['start'];
						$event_series_date['end'] = $event_series_date['end'] . ' ' . $event_series['time']['end'];


						// Special conditions for weekly recurrenc
						if (2 == $event_series['frequency']) {
							$event_series['weekly_days'] = get_sub_field('rm_event_series_weekly_days');
							$event_series_repeat_day = date('w', strtotime($event_series_date['start']));
							$event_series_weekly = array();
							for ($i = 0; $i < count($event_series['weekly_days']); $i++) {
								if ($event_series_repeat_day < $event_series['weekly_days'][$i]) {
									// Do before days
									$day_diff = $event_series_repeat_day - $event_series['weekly_days'][$i];
									$new_start = strtotime('-'.$day_diff.'day', strtotime($event_series_date['start']));
									$new_end = strtotime('-'.$day_diff.'day', strtotime($event_series_date['end']));
									$event_series_weekly['start'] = date('Y-m-d H:i:s', $new_start);
									$event_series_weekly['end'] = date('Y-m-d H:i:s', $new_end);
								} elseif ($event_series_repeat_day === $event_series['weekly_days'][$i]) {
									// Do On Days
									$event_series_weekly = $event_series_date;
								} elseif (($event_series_repeat_day > $event_series['weekly_days'][$i])) {
									// Do after days
									$day_diff = $event_series['weekly_days'][$i] - $event_series_repeat_day;
									$new_start = strtotime('+'.$day_diff.'day', strtotime($event_series_date['start']));
									$new_end = strtotime('+'.$day_diff.'day', strtotime($event_series_date['end']));
									$event_series_weekly['start'] = date('Y-m-d H:i:s', $new_start);
									$event_series_weekly['end'] = date('Y-m-d H:i:s', $new_end);
								}
								// NEED TO CHECK START DATE FOR EVENT BEFORE ADDING HERE
								$check_event_series_start = strtotime($event_series['date']['start']);
								$check_event_weekly_start = strtotime($event_series_weekly['start']);
								if ($check_event_weekly_start >= $check_event_series_start) {
									array_push($event_series['dates'], $event_series_weekly);
								}
							}
						} else {
							// Else just push date
							array_push($event_series['dates'], $event_series_date);
						}

					}
				} else {
					// Is Once Series
				}


				// Push to event series
				array_push($this->event_series_dates, $event_series['dates']);
			} // End while have_rows rm_event_series


			/* EXLUSION LOGIC NEEDS TO GO HERE */
			if (have_rows('rm_event_exclude',  $this->ID)) {
				// Set variable
				//$this->event_exclude = array();
				while (have_rows('rm_event_exclude', $this->ID)) {
					the_row();
					$event_exclude = array();

					// Get exclusion frequency
					$event_exclude['frequency'] = get_sub_field('rm_event_exclude_freq');
					// Get frequency interval depending on frequency
					if (0 == $event_exclude['frequency']) {
						// Once
						$event_exclude['interval'] = get_sub_field('rm_event_exclude_once');
					} elseif (1 == $event_exclude['frequency']) {
						// Daily
						$event_exclude['interval'] = get_sub_field('rm_event_exclude_daily');
					} elseif (2 == $event_exclude['frequency']) {
						// Weekly
						$event_exclude['interval'] = get_sub_field('rm_event_exclude_weekly') * 7;
					} elseif (3 == $event_exclude['frequency']) {
						// Monthly
						$event_exclude['interval'] = get_sub_field('rm_event_exclude_monthly');
					} 

					// Calculate date periods for frequencies not 0
					if (0 != $event_exclude['frequency']) {
						// Set DateTime Objs
						$event_exclude_start = new DateTime($event_series['date']['start']);
						$event_exclude_end = new DateTime(get_sub_field('rm_event_exclude_end'));
						$event_exclude_end = $event_series_end->modify('+1 day');
						// Set interval
						$event_exclude['interval'] = 'P' . $event_exclude['interval'];
						if (1 == $event_exclude['frequency'] || 2 == $event_exclude['frequency']) {
							$event_exclude['interval'] .= 'D';
						} elseif (3 == $event_exclude['frequency']) {
							$event_exclude['interval'] .= 'M';
						}
						$event_exclude['interval'] = new DateInterval($event_exclude['interval']);
						$event_exclude['daterange'] = new DatePeriod($event_exclude_start, $event_exclude['interval'], $event_exclude_end);
						// Loop daterange
						foreach ($event_exclude['daterange'] as $exclude) {
							// Speecial conditions for weekly exclusions
							if (2 == $event_exclude['frequency']) {
								// Build weekly days
								$event_exclude['weekly_days'] = get_sub_field('rm_event_exclude_weekly_days');
								$event_exclude_repeat_day = $exclude->format('w');
								$event_exclude_weekly = '';
								for ($i = 0; $i < count($event_exclude['weekly_days']); $i++) {
									$event_exclude_weekly = $exclude->format('Y-m-d');

									if ($event_exclude_repeat_day < $event_exclude['weekly_days'][$i]) {
										$excl_diff = $event_exclude_repeat_day - $event_exclude['weekly_days'][$i];
										$new_excl = $exclude->format('Y-m-d');
										$new_excl = strtotime('-'.$excl_diff.'day', strtotime($new_excl));
										$new_excl = date('Y-m-d', $new_excl);
										$event_exclude_weekly = $new_excl;
									} elseif ($event_exclude_repeat_day === $event_exclude['weekly_days'][$i]) {
										$event_exclude_weekly = $exclude->format('Y-m-d');
									} elseif ($event_exclude_repeat_day > $event_exclude['weekly_days'][$i]) {
										$excl_diff = $event_exclude['weekly_days'][$i] - $event_exclude_repeat_day;
										$new_excl = $exclude->format('Y-m-d');
										$new_excl = strtotime('+'.$excl_diff.'day', strtotime($new_excl));
										$new_excl = date('Y-m-d', $new_excl);
										$event_exclude_weekly = $new_excl;
									}

									array_push($event_series_duplicates, $event_exclude_weekly);
								}
							} else {
								// Not a weekly exclusion
								array_push($event_series_duplicates, $exclude->format('Y-m-d'));
							}
						}
					} else {
						// Is Once Exclusion
					}

				} // End While for excludes
			} // end if for excludes

			// Begin loop through each series
			for ($k = 0; $k < count($this->event_series_dates); $k++) {
				$this_event_series = $this->event_series_dates[$k];
				// Begin loop through series dates in series
				for ($i = 0; $i < count($this_event_series); $i++) {
					$this_event_start = $this_event_series[$i]['start'];
					$this_event_start_timeless = explode(' ', $this_event_series[$i]['start']);
					$this_event_start_timeless = $this_event_start_timeless[0];
					// Check if this start date exists in duplicates
					if (!in_array($this_event_start, $event_series_duplicates) && !in_array($this_event_start_timeless, $event_series_duplicates)) {
						// If not, push start date to duplicates
						array_push($event_series_duplicates, $this_event_start);
						
						// And send to event series
						array_push($this->event_series, $this_event_series[$i]);
					}
				}
			}
			// Sort events in ascending order
			usort($this->event_series, function ($a, $b) {
				return strtotime($b['start']) - strtotime($a['start']);
			});
			$this->event_series = array_reverse($this->event_series);

			// Unset event series build variables
			unset($this->event_series_dates, $event_series);
		} // End if have_rows rm_event_series
	}


//// CONSTRUCTOR
	public function __construct($post_id = null) {
		// Check if $post_id provided
		if ($post_id === null || !is_int($post_id)) {
			// Post is null or post ID isn't an integer
			// Get most recent published event post
			$get_posts = get_posts(
				array(
					'post_type'		=> 'rm-event',
					'numberposts'	=> 1,
					'order'				=> 'DESC',
					'post_status'	=> 'publish',
				)
			);
			$post_id = $get_posts[0]->ID;
		}
		// Get Post Object and make this object
		$post_obj = get_post($post_id);
		// Assign post_obj properties to this object
		foreach ($post_obj as $key => $value) {
			$this->$key = $value;
		}
	}

}

?>