# PlacePress
Publish location-based, media-rich, structured narratives. Designed for public historians, urbanists, and other humanities researchers. Adds Tour and Location post types, as well as custom taxonomies and metadata fields.

## Location Shortcodes
PlacePress automatically adds custom content to each Story post. If you would like to change the order in which these components appear in a Story post or custom template, you may use the following shortcodes. Each of these accepts a `no-heading` attribute.
- `[placepress_images]` : custom placement of image gallery within a story
- `[placepress_audio]` : custom placement of audio playlist within a story
- `[placepress_video]` : custom placement of video playlist within a story
- `[placepress_map]` : custom placement of map within a story

## Global Shortcodes
The following shortcodes may be used to place PlacePress components in any non-Story post or page.
- `[placepress_global_map]` : adds the global map to a standalone page

## Helper Functions
PlacePress attempts to add custom fields and other components to your theme automatically using WordPress plugin filters. If you have compatibility issues with your theme or would like to customize where/how content appears, you can use the helper functions below. NOTE: Be sure to turn off content filters in PlacePress plugin settings if you are customizing your theme.

### General
- `placepress_setting($option)`
- `placepress_parse_markdown($string,$singleline=true)`

### Stories
- `placepress_display_media_section($post, $includeImages=true, $includeAudio=true, $includeVideo=true)`
- `placepress_get_story_media($post)`
- `placepress_story_map($post,$includeHeading=true)`
- `placepress_street_address($post)`
- `placepress_access_information($post)`
- `placepress_official_website($post)`
- `placepress_subtitle($post)`
- `placepress_lede($post)`
- `placepress_related_sources($post)`

The following functions require an array of files, which can be obtained using the `placepress_get_story_media()` function:
```
$media = placepress_get_story_media($post);
$images = $media['images'];
$audio = $media['audio'];
$video = $media['video'];
```
- `placepress_image_gallery($images,$containerTag='section',$includeHeading=true)`
- `placepress_audio_playlist($audio,$containerTag='section',$includeHeading=true)`
- `placepress_video_playlist($video,$containerTag='section',$includeHeading=true)`

Alternately, you may use `placepress_display_media_section()` to display all media components at once. See above.

### Tours
- `placepress_tour_map($post)`
- `placepress_stories_for_tour($post)`

### Global
- `placepress_global_map()`
