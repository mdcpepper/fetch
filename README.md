# Fetch Craft Plugin

A proof-of-concept plugin for basic eager loading of relationships in Craft.

NOTE: This is really just an experiment, hasn't been run in production anywhere, is kind of awkward,
and the actual performance gain you might get depends on a lot of other factors too. Please feel free to fork and improve!

## Example

To iterate over a collection of entries and output their assets, you might normally use code like
the following:

```twig
{% set entries = craft.entries.section('news').find %}

{% for entry in entries %}
	{% for image in entry.images %}
		{{ image.id }}
	{% endfor %}
{% endfor %}
```

But this code performs n+1 queries - one for the entries, and then another for each entry, that
grabs the related assets.

Using this plugin, if the `images` field had an ID of `3`, to fetch all the assets in one go you
would instead use:

```twig
{% set entries  = craft.entries.section('news').find %}
{% set images   = craft.fetch.assets(entries, 3) %}

{% for entry in entries %}
	{% if craft.fetch.exists(images, entry.id, 3) %}
		{% for image in images[entry.id][3] %}
			{{ image.id }}
		{% endfor %}
	{% endif %}
{% endfor %}
```

This way only 3 queries are executed - one for the initial entries, another to fetch the relations
information, and another to select all the related elements.

There's also `craft.fetch.entries`, `craft.fetch.categories` etc, that work the same way for the other element types.

The `craft.fetch.exists` function is just a helper that checks for the existance of the Entry and Field IDs in the array supplied as the first argument, so you don't have to use some horrible if statement in twig, like:

```twig
{% if (entry.id in images|keys) and (3 in images[entry.id]|keys) and images[entry.id][3]|length) %}
    {# there are related elements for this entry #}
{% endif %}
```

# License

MIT
