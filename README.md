# Event Manager by Roar Media
A WordPress plugin that adds a lightweight events calendar to Enfold. This plugin current requires Advanced Custom Fields Pro but future releases may not.

## Installation
Download the .zip file and extract to the wp-content/plugins directory of WordPress.

## Roadmap
This plugin is a work in progress. Final product features will include:
* ~~Recurring & Exclusion events (daily, weekly, monthly, and one-offs)~~ **DONE!**
* Event groups
* Event categorization and tagging
* Plugin Settings **Started**
* Venue Support **Started**
* Calendar and list views

## Version History
## Alpha
#### 0.4
All event ACF fields are now captured. New RM_Event will now generate the event datetime, event series, and RM Event object by default. Can be disabled by setting the second argument in to 0 or false. Added settings page and the ability to enable/disable rm-event-venue post type. Fields for venues will be added by next version.
#### 0.3
Completed the logic for weekly and once series and exclusions in events. Ready to move onto simpler tasks. However, I still have not resolved the small with end-dates for events with new times (ie: if an event begins on 12-5 at 23:00 and ends on 12-6 at 01:00 and one of the event series changes the times to 18:00 and 21:00, the actual time for the series events will still 12-n at 18:00 through 12-n+1 at 21:00). I hope that makes sense.
#### 0.2
Made more progress. ACF fields in place. Event series and exclusion logic begun. Currently need to work on Weekly, Monthly, and Once series/exclusions. Also need to work on logic for "New Event Times" in event series of events with end dates later than their start dates.
#### 0.1
Initial plugin setup. Created custom post types and taxonomies.