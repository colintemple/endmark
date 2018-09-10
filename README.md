# Endmark

Endmark is a Wordpress plugin that adds an end-of-article symbol to the end of your posts and/or 
pages. This little bit of typography adds a slight professional edge to your blog, making your
articles reminiscent of print magazines.

I decided to make this plugin when I updated my blog's design and wanted one of these symbols at 
the end of my posts, but I could not find anything out there to do this.

The plugin allows you to use any symbol or image as your Endmark.

## Installation

1. Download the [dist/endmark.zip](https://github.com/colintemple/endmark/raw/master/dist/endmark.zip) file and unzip it.

2. Upload the `endmark` folder to your `/wp-content/plugins/` directory in your Wordpress folder.

3. Activate the plugin through the Plugins menu in Wordpress.

4. Go to Endmark under Appearance to choose your symbol or image.

## Notes

- You can choose to publish Endmarks on posts or pages only. (Version 1.1+)

- If you choose to use an image for your Endmark, an actual <img> element is added to the end of 
your article. The class 'endmark' is assigned to the image so you can style it with CSS if desired.

- If you choose to use a symbol, a single non-breaking space is added before your Endmark.

- Symbols are automatically encoded into HTML entities where necessary.

- French language localization (for France and Canada) is included with the plugin.