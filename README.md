# Incognito Field plugin for Craft CMS

PlainText drop-in replacement that can be set to `disabled`, `hidden` or `readonly`.

## Requirements

This plugin requires Craft CMS 3.7.0 or later.

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

Create a new field (or convert an existing Plain Text field) as _Incognito Field_. Select the rendering mode you want in the _Mode_ setting to make the field `hidden`, `readonly`, `disabled` (or a regular, visible and editable PlainText field).  optionally override the mode in _Mode Override_ which you can find in the advanced field settings.

### Overriding the rendering mode with Twig logic

You can use Twig in the `Mode Override` setting field to optionally override the rendering mode. **A good use case example is to have the field render as an editable PlainText field for admin users, but be hidden, read only or disabled for everyone else.**  

#### Mode Override examples

Render as an editable PlainText field if the element is an unsaved draft:

```twig
{{ element.getIsUnsavedDraft() ? 'plain' }}
```

Render as an editable PlainText field for admin users:  

```twig
{{ currentUser.admin ? 'plain' }}
```

Render as a visible, read-only field for users in a particular user group:  

```twig
{{ currentUser.isInGroup('editors') ? 'readonly' }}
```

Render as an editable PlainText field for users that have _edit site permissions_ for a particular site:  

```twig
{% set site = craft.app.sites.getSiteByHandle('germany') %}
{{ currentUser.can('editSite:' ~ site.uid) ? 'plain' }}
```

## Disclaimer

This plugin is provided free of charge and you can do whatever you want with it. Incognito Field is unlikely to mess up your stuff, but just to be clear: the author is not responsible for data loss or any other problems resulting from the use of this plugin.

Please report any bugs, feature requests or other issues [here](https://github.com/mmikkel/IncognitoField-Craft3/issues). Note that this is a hobby project and no promises are made regarding response time, feature implementations or bug fixes.

**Pull requests are extremely welcome**
