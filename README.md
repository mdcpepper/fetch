# Fetch Craft Plugin

A proof-of-concept plugin for basic eager loading of relationships in Craft.

NOTE: This is really just an experiment, hasn't been run in production anywhere, and the actual
performance gain you might get depends on a lot of other factors too.

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

But this code performs N+1 queries - one for the entries, and then another for each entry, that
grabs the entries.

With this plugin, if the `images` field had an ID of `3`, to fetch all the assets in one go, you
would instead use:

```twig
{% set entries  = craft.entries.section('news').find %}
{% set images   = craft.fetch.assets(entries, 3) %}
<ul>
	{% for entry in entries %}
		<li>Entry #{{ entry.id }}
			{% if craft.fetch.exists(images, entry.id, 3) %}
				<ul>
					{% for image in images[entry.id][3] %}
						<li>Asset #{{ image.id }}</li>
					{% endfor %}
				</ul>
			{% endif %}
		</li>
	{% endfor %}
</ul>
```

This way only 3 queries are executed - one for the initial entries, another to fetch the relations
information, and another to select all the related elements.

There's also `craft.fetch.entries`, `craft.fetch.categories` etc, that work the same way.