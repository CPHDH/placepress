# Curatescape
Publish location-based, media-rich, structured narratives compatible with Curatescape mobile apps. Designed for public historians, urbanists, and other humanities researchers. Adds Tour and Story post types, as well as custom taxonomies and metadata fields.

## Story Shortcodes
Curatescape automatically adds custom content to each Story post. If you would like to change the order in which these components appear in a Story post or custom template, you may use the following shortcodes. Each of these accepts a `no-heading` attribute.
- `[curatescape_images]` : custom placement of image gallery within a story
- `[curatescape_audio]` : custom placement of audio playlist within a story
- `[curatescape_video]` : custom placement of video playlist within a story
- `[curatescape_map]` : custom placement of map within a story

## Global Shortcodes
The following shortcodes may be used to place Curatescpae components in any non-Story post or page.
- `[curatescape_global_map]` : adds the global map to a standalone page

## Helper Functions
### General
- `curatescape_setting($option)`
- `curatescape_parse_markdown($string,$singleline=true)`

### Stories
- `curatescape_display_media_section($post, $includeImages=true, $includeAudio=true, $includeVideo=true)`
- `curatescape_get_story_media($post)`
- `curatescape_story_map($post,$includeHeading=true)`
- `curatescape_street_address($post)`
- `curatescape_access_information($post)`
- `curatescape_official_website($post)`
- `curatescape_subtitle($post)`
- `curatescape_lede($post)`
- `curatescape_related_sources($post)`

The following functions require an array of files, which can be obtained using the `curatescape_get_story_media()` function:
```
$media = curatescape_get_story_media($post);
$images = $media['images'];
$audio = $media['audio'];
$video = $media['video'];
```
- `curatescape_image_gallery($images,$containerTag='section',$includeHeading=true)`
- `curatescape_audio_playlist($audio,$containerTag='section',$includeHeading=true)`
- `curatescape_video_playlist($video,$containerTag='section',$includeHeading=true)`

Alternately, you may use `curatescape_display_media_section()` to display all media components at once. See above.

### Tours
- `curatescape_tour_map($post)`
- `curatescape_stories_for_tour($post)`

### Global
- `curatescape_global_map()`
