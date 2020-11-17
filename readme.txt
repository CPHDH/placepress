=== PlacePress ===
Contributors: cphdh,ebellempire
Tags: placepress, history, public, digital humanities, map, curatescape, blocks, gutenburg, location, tour, post types, walking, leaflet
Requires at least: 5.0
Tested up to: 5.5.3
Requires PHP: 5.2.4
Stable tag: 1.1.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

An elegant mapping solution for public historians, urbanists, and other humanities researchers.

== Description ==

= PlacePress =

PlacePress adds Location and Tour post types, structured map blocks (for single and global locations), structured tour stop blocks, and custom taxonomies.

= Getting started with Locations =

1. Add a Location using the Locations post type. Generally, a Location post should include some interesting narrative content. This is where you tell a story about your location, so feel free to use images, audio, video, and other blocks!
2. Add the Location Map block to your Location post to display an interactive map. You will use this block just once per Location.
	a. Use the search bar in the Location Map block to find your specific or general area and/or drag the map marker to the desired location.
	b. Add a caption below the map to add more detail and/or to help users with disabilities by adding text-equivalent content.
	c. The display of the map in your editor is how it will be displayed to users, so don't forget to adjust the zoom level and choose the default map style you prefer.
3. Note that the Location Map block not only adds a map to your Location post, it also gives it the coordinates required to show all your Location posts elsewhere on the site, for example when you use the Global Map block.
5. Tip: for a more dramatic impact, make your map taller and wider by setting the alignment to "Full" or "Wide" (theme support may vary).
6. Tip: Use the Location Type taxonomy (same as Category) to categorize your locations into groups that can be added to the navigation menu for quick access.
7. Tip: Use the Location Image (same as Featured Image) interface to add an attractive image of your location. This image will not only be used by most themes, it will also be used to represent your location on any global maps  you display on your site (see below).
8. Optional: Once you have published some Locations, add the Global Map block to any post or page to display all your locations on a single map.
9. Optional: Enable the relevant options on the Settings > PlacePress page to show a global map of all Location posts on the Locations archive page and/or the Location Types archive page.

= Getting started with Tours =

1. Add a Tour using the Tours post type. Generally, a Tour post should contain some interesting narrative content. This is where you use your storytelling skills to weave together a narrative about multiple locations (or, as we call them, tour stops). It's usually a good idea to include some introductory text at the top of the post.
2. Add the Tour Stop block to mark each new section of your Tour post with a hero image and text heading. You will use multiple Tour Stop blocks in each tour.
	a. Give the tour stop a title. By default, this text will appear as an H2 element, but you can modify the heading level to best suit your page structure. Many themes will also let you change the color of the text and background here.
	b. Add an image by clicking the "Choose Image" button, which will open your media library where you can upload or select a file. Note that the contents of the Caption field for your file will be used for the Tour Stop caption if you have enabled caption display in Settings > PlacePress.
	c. Configure the map settings by clicking the "Set Coordinates" button. This will open an interactive map overlay that works much like the Location Map block (see "Getting Started with Locations" above). Simply set your map's coordinates, zoom level, and default style.
3. Note that not only does the Tour Stop block give your Tour post some visual and semantic structure, it also contains the coordinates and other data used to display an interactive tour map to your users. When accessing the public view of your Tour post, a small floating map will appear at the bottom right of the page (unless you configure it to be hidden by default in Settings > PlacePress). Clicking on this map will open a larger map depicting all the locations in your tour, and the center of the map updates as you scroll down the page. Clicking on the map icon in your Tour Stop will also open the map.
4. Tip: for a more dramatic impact, make your Tour Stop header taller and wider by setting the alignment to "Full" or "Wide" (theme support may vary).
5. Tip: Use the Tour Type taxonomy (same as Category) to categorize your tours into groups that can be added to the navigation menu for quick access.
6. Tip: Use the Tour Image (same as Featured Image) interface to add an attractive image to your tour. This image will be used by most themes.


== Installation ==

The quickest method for installing PlacePress is:

1. Install PlacePress either via the WordPress.org plugin repository or by uploading the files to the plugins directory on your server.
2. Activate PlacePress.
3. Navigate to the PlacePress options page at Settings > PlacePress and update the default options.

== Frequently Asked Questions ==

= Where can I view some examples? =

Check out [wpplacepress.org/examples](https://wpplacepress.org/examples/) for videos and sample content.

= Does PlacePress work with my theme? =

Probably. It should work with any modern WordPress theme that is compatible with the block editor, but let us know if you have issues. 

= Can I change the way PlacePress looks? =

We've tried to keep the styles as subtle as possible so that PlacePress doesn't interfere too much with your theme's overall design. For small tweaks, we encourage you to use your own Custom CSS (available in most themes in the Customize menu), but let us know if you experience major issues or display errors, especially if you use a popular theme.

= I’m getting a 404 (Not Found) error when browsing Locations/Tours. =

If you have published Locations and you get a 404 error, it usually means you need to update your permalink settings. Simply go to Settings > Permalinks and press the Save button. You probably don’t need to change the settings for this fix to work. If this doesn’t fix the issue after refreshing your browser, you may be using an incompatible theme (which is unlikely if your theme is from the WordPress theme directory).

= How do I change the default map behavior? =

You can edit the PlacePress settings in Settings > PlacePress. There you will find options for controlling the default map coordinates, zoom level, basemap style, clustering, and more.

= I’d like to use a basemap that’s not included in PlacePress. =

We chose a handful of basemap styles to get started, but plan to add more in the future. If you would like to request a particular basemap, get in touch and we’ll see what we can do. Generally, we only add high-resolution basemaps that are available for free use. As we continue to build out PlacePress, we hope to add support for additional basemaps, including some from paid services like Mapbox.

= How do I add an image for my locations so they look pretty on the map? =

Use the Location Image interface. It works just like the Featured Image on posts.

= Can I build my own maps or an external application using a PlacePress API? =
You can fetch a JSON feed of mapped Locations at `?feed=placepress_locations_public`. You can also use the [WP REST API](https://developer.wordpress.org/rest-api/).

== Changelog ==

= 1.0 =
- Initial release.

= 1.1 =
- Adds Tours
- Adds optional Location Maps to archive pages
- Enhanced user interface for settings page, including Help menus
- Improved documentation in README file
- Bug fixes and performance improvements

= 1.1.1 =
- Bug fixes for Safari users (inaccessible geocode input fields, in-map user controls)

= 1.1.2 =
- Adds a compatibility option for themes that display pages as component sections within a front page template