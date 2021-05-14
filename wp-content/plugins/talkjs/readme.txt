=== TalkJS ===
Contributors: TalkJS
Donate link: https://talkjs.com/
Tags: chat, messaging, marketplace, peer to peer, buyer, seller, p2p, multivendor, multi-vendor, wc marketplace, buyer seller chat, customer support, multi-vendor chat, on-demand chat, social network chat, woocommerce, wc vendors
Requires at least: 4.4
Tested up to: 5.6
Requires PHP: 5.3
Stable tag: 0.1.15
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

TalkJS is the messaging tool for platforms. Add buyer seller chat to your marketplace, on-demand app, or peer-to-peer chat to your social platform. Similar to what Airbnb, Upwork, or LinkedIn are offering in messaging.

== Description ==

The TalkJS plugin allows you to add user-to-user chat to your marketplace, on-demand app, or social platform. You'll have it up and running in 5 minutes. It integrates fully with WooCommerce and marketplace/multi-vendor plugins like WCVendors.


This plugin offers you shortcodes and template tags for our three basic views: The inbox, the chatbox and the chat pop-up. It also adds a chatbox widget and an inbox-page.


TalkJS is the messaging tool for platforms. Add buyer seller chat to your marketplace, on-demand app, or peer-to-peer chat to your social platform. Similar to what Airbnb, Upwork, or LinkedIn are offering in messaging.


By providing direct contact between your users, you're saving on customer support time, increase user interaction and retention.


With this plugin, you can add TalkJS to your Wordpress installation in just a few clicks and allow your users to chat with each other inside of your website or app right away!


NOTE: This plugin is free, but you'll need a TalkJS subscription to be able to use it in a production environment. TalkJS offers unlimited testing and a 14 day free trial when you go live.


== Installation ==

Follow these simple steps to get started:

1. Upload `talkjs` to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Follow the on-screen instructions to initiate the test version of the plugin.
4. If you're satisfied with the plugin in development mode, please consider updating to a paid account.


== Screenshots ==

1. The TalkJS settings page


== Changelog ==


= 0.1.14 =

* Minor SVN fix merged into git

= 0.1.14 =

* Minor SVN fix merged to git
* Fixes various minor issues in preperation of a big new version


= 0.1.13 =

* Tested the code with latest versions of WordPress.
* Fixes various minor issues in preperation of a big new version


= 0.1.12 =

* Fixed bug that caused to popup to remain open
* Renamed loadOpen property to keepOpen for the [talkjs_popup] shortcode
* Minor UX improvements

= 0.1.11 =

* Fixed a bug where the Popup could not be closed.

= 0.1.10 =

* Fixed a bug where shortcodes wouldn't create a conversation with user with certain id

= 0.1.9 =

* Fixed a bug where the notifications badge is always shown

= 0.1.8 =

* Fixed a bug where a "hidden" class was added to the menus when there is no logged in user

= 0.1.7 =

* Fixed a bug where auto show popup feature shows the current user to chat instead of the post author

= 0.1.6 =

* Support for older PHP versions, down to 5.3
* Fixed various bugs
* Use `display_name` instead of `user_nicename`

= 0.1.5 =

* Fixed a bug with setting user ID for chats

= 0.1.4 =

* Added property "loadOpen" for [talkjs_popup] shortcode
* Fixed PHP errors

= 0.1.3 =

* Updated README

= 0.1.2 =

* Adds `width`, `height` and `style` properties
* Fixes links

= 0.1.1 =

* Adds automatic Identity Validation
* Adds option to pass `topicId` and `subject` to \[talkjs_chat\], \[talkjs_inbox\], \[talkjs_popup\] shortcodes
* Removes Publishable Keys since they are deprecated

= 0.1.0 =

* First public release
* Adds \[talkjs_chat\], \[talkjs_inbox\], \[talkjs_popup\] shortcodes
* Adds a chatbox widget
* Adds template tags for talkjs_chat() talkjs_inbox() and talkjs_popup()


== For developers ==

If you're looking to integrate the TalkJS messaging platform to your WordPress site, this is the easiest way to do it.
You can use three methods of implementation:

1. Shortcodes
2. Widgets
3. Template tags

=== Shortcodes ===
You can use shortcodes on a post-by-post basis. The following three shortcodes allows you to easily use any TalkJS UI:

* `[talkjs_chat]`
* `[talkjs_inbox]`
* `[talkjs_popup]`

You can pass along the default conversation user for the chat and the popup. You do this by adding a user ID to the shortcode: [talkjs_chat user="2"]

If you wish to get the author of the post as your conversation user you can use the following: [talkjs_chat userType="author"]. You shouldn't fill in the user ID in this case since it overwrites the `userType`.

With all three you can pass along a custom welcome message like this: [talkjs_chat welcomeMessage="Hi there!"]

For Inbox and Chatbox, you can also customize the width and height of the shortcodes by passing `width` and `height` properties like this: [talkjs_chat height="500px" width="250px"]

There are some predefined styles to make integration easier, if you want full control over the style of the TalkJS UI container, you can pass `style` property to overwrite the default styles. `width` and `height` properties are ignored when `style` is used.

The popup also has an option to keep it open between pages by using the shortcode with the keepOpen property e.g. [talkjs_popup keepOpen="true"]

=== Widgets ===
There's currently only one widget available: The TalkJS Chatbox widget. In it you can set a title, a welcome message and the conversation user (the current post author if blank)

=== Template tags ===
You can use template tags if you want to display the chatbox, inbox or popup on certain templates. You use them in your code like so:

`<?php talkjs_chat();?>`
`<?php talkjs_popup();?>`
`<?php talkjs_inbox();?>`

You can pass along the same attributes as in the shortcode-version. You add them in an array, like this:
`<?php
talkjs_chat([
    'welcomeMessage' => "Hi there, let\'s chat!",
    'userType' => 'author'
]);
?>
`

=== Welcome Message customization ===

You can add custom variables to your welcome message. These variables are available:

* {{first_name}} -> logged in users' first name.
* {{last_name}} -> logged in users' last name.
* {{name}} -> logged in users' full name.


=== Available filters ===

We currently have one filter available for developers. Feel free to contact us if you need any other filters.

==== talkjs_hidden_post_types_for_chat_popup ====
Hide your custom post-type as a viable chat option.

`<?php
add_filter( 'talkjs_hidden_post_types_for_chat_popup', function( $postTypes ){

    $postTypes[] = 'my-custom-post-type';
    return $postTypes;

});
?>`
