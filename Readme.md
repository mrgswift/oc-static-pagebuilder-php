Static Page Builder plugin for OctoberCMS
=

This is the PHP code for a private plugin that was build for a client to work with OctoberCMS (a CMS built on Laravel). It creates an
interface between OctoberCMS and a proprietary drag & drop JS page builder application (not included).
This plugin extends a popular OctoberCMS plugin called Rainlab Pages to add easy-to-use drag & drop functionality
to allow dropping in pre-styled content blocks and snippets on to a canvas area to build a page.


## Client Requirements

1. Ability to create dynamically editable static html pages without the need to save page markup to a database or cache storage
2. Page builder output should be HTML and be CSS framework agnostic
3. Page builder should support Google fonts
4. Content blocks and snippets can be easily added by simply adding additional html files to a predetermined directory path/structure
5. Ability to load assets locally or remotely (using CDN)
6. Page changes should be trackable via version control (client handles version control portion)


