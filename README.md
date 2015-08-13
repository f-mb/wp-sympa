WP Sympa
========

WP Sympa is an easy-to-use Wordpress plugin to display a subscription/unsubcription form for Sympa-managed lists.

**Current version:** 0.1.6 (2015-08-13)

###### LICENSE

GNU GPL v2

If you add/improve some features, please pull a request!


***

###### REQUIREMENT

You server should be able to send emails.

###### INSTALLATION & CONFIGURATION

 <a href="https://github.com/f-mb/wp-sympa/archive/master.zip">Download</a> the plugin and upload it to your WordPress plugin directory. Then, activate it. Then... you're done.

###### USAGE

To add a subscription/unsubscription form to a Sympa list, you just have to add the following shortcode in one of your pages:

```
[wpsympa s=%SYMPA_HOST% l=%SYMPA_LIST%]
```

Where :
* ```%SYMPA_HOST%``` is the adresse of your Sympa host (usually, something like ```sympa@lists.yourdomain.ext```).
* ```%SYMPA_LIST%``` is the name of your Sympa list.


By default, WP Sympa use the 'subscribe' to subscribe to a list, and 'unsubscribe' to unsubscribe a list. As some Sympa server use different command (as 'signoff' for unsubscribe), you can change these by adding one of the following parameters:

```
[wpsympa s=%SYMPA_HOST% l=%SYMPA_LIST% r=%SYMPA_SUBSCRIBE_COMMAND% u=%SYMPA_UNSUBSCRIBE_COMMAND%]
```

Where :
* ```%SYMPA_SUBSCRIBE_COMMAND%``` is the subscribe command (like ```subscribe``` or ```register```)
* ```%SYMPA_UNSUBSCRIBE_COMMAND%``` is the unsubscribe command (like ```unsubscribe``` or ```signoff```)


