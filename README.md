# Incognito Field plugin for Craft CMS 3.x

PlainText drop-in replacement that can be set to `disabled`, `hidden` or `readonly.

## Requirements

This plugin requires Craft CMS 3.0.0-beta.23 or later.

[Looking for the Craft 2 version?](https://github.com/mmikkel/IncognitoField-Craft)  

## Installation

To install the plugin, follow these instructions.

1. Open your terminal and go to your Craft project:

        cd /path/to/project

2. Then tell Composer to load the plugin:

        composer require mmikkel/incognito-field

3. In the Control Panel, go to Settings → Plugins and click the “Install” button for Incognito Field.

## Incognito Field Overview

Incognito Field adds a custom FieldType to Craft CMS, called Incognito. Incognito fields work exactly like regular PlainText fields, except that they can be configured to be `disabled`, `readonly` or `hidden` for element editor forms.

A good example use case for Incognito is whenever you need a field that shouldn't be editable via the CP – e.g. if you want to save some data from a feed or external API on your element model.

_Incognito Field works both standalone and inside Matrix blocks._

## Configuring Incognito Field

Install it, create a new field (or convert an existing one) as _Incognito Field_. Select the rendering mode you want in the _Mode_ settings field, profit.  

## Disclaimer

This plugin is provided free of charge and you can do whatever you want with it. Incognito Field is unlikely to mess up your stuff, but just to be clear: the author is not responsible for data loss or any other problems resulting from the use of this plugin.

Please report any bugs, feature requests or other issues [here](https://github.com/mmikkel/IncognitoField-Craft3/issues). Note that this is a hobby project and no promises are made regarding response time, feature implementations or bug fixes.

**Pull requests are extremely welcome**
