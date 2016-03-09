# Fetch Craft Plugin

**DEPRECATED:** [Craft 2.6.2771](https://craftcms.com/changelog#build2771) added [eager-loading for elements](https://craftcms.com/docs/templating/eager-loading-elements) to the core, and you should definitely use that over this, which will no longer be maintained.

<hr>

A plugin for basic eager loading of relationships in Craft.

NOTE: This is experimental, hasn't been run in production anywhere, and the actual
performance gain you might get depends on a few other factors too so YMMV. Please feel free to fork and
improve!

## Installation

1. Clone or download this repository and extract to a `fetch` directory in your Craft plugins directory.
2. Install the plugin through the Craft control panel.

## Usage

To iterate over a collection of Entries and output their Assets, you would normally use something
like the following in your template:

```twig
{% set entries = craft.entries.section('portfolio') %}

{% for entry in entries %}
    {% for image in entry.images %}
        <img src="{{ image.url }}" alt="{{ image.title }}">
    {% endfor %}
{% endfor %}
```

But this code performs n+1 queries - one for the `craft.entries` call, and then another for each
call to `entry.images`, that fetches the related Assets.

Using this plugin, to fetch *all* the Assets for one or more fields in one go you
would instead use the `craft.fetch.assets` template variable, passing in the Entries you've already
loaded:

```twig
{% set entries = craft.entries.section('portfolio').find() %}
{% do craft.fetch.assets(entries, 'images, moreImages') %}
```

This way only three queries are executed - the one to get the initial Entries, another to fetch the
relations' information, and a final one to fetch all of the related Elements themselves.

Now, when you loop over your Entries, each one has a new `fetched` method that is used to access
the related Elements. You can pass in one or more field handles to limit the Elements that are
returned, or leave it out to get everything.

```twig
{% for entry in entries %}

    {% for image in entry.fetched('images, moreImages') %}
        <img src="{{ image.url }}" alt="{{ image.title }}">
    {% endfor %}

{% endfor %}
```

There are template variable methods for all the built-in Craft Element Selector Field Types:

* `craft.fetch.assets`
* `craft.fetch.categories`
* `craft.fetch.entries`
* `craft.fetch.tags`
* `craft.fetch.users`

And Fetch works with custom Element Types too:

```twig
{% do craft.fetch.elements('YourPlugin_CustomElementType', entries, 'customElementsField') %}
```

As with the Craft `ElementCriteriaModel`s, you can pass multiple field handles as a comma-separated
string or a Twig array.

## Full Example

```twig
{% set entries = craft.entries.section('products').find() %}
{% do craft.fetch.assets(entries, 'productImages') %}

<section class="products">
    {% for entry in entries %}
        <article>
            <h1>{{ entry.title }}</h1>

            {% if entry.fetched('productImages')|length %}
                <ul class="images">
                    {% for image in entry.fetched('productImages') %}
                        <li><img src="{{ image.url }}" alt="{{ image.title }}"></li>
                    {% endfor %}
                </ul>
            {% endif %}
        </article>
    {% endfor %}
</section>
```

## Reverse Relations

If you are primarily looping through the “target” elements in the context of a relationship, and want to eager-load their source elements, you set that up the same way you would otherwise, but prefix your field handle with `reverse:`.

```twig
{% set categories = craft.categories.group('productCategories').find() %}
{% do craft.fetch.entries(categories, 'reverse:productCategories') %}

<section class="categories">
    {% for category in categories %}
        <article>
            <h1>{{ category.title }}</h1>

            {% if category.fetched('reverse:productCategories')|length %}
                <ul class="products">
                    {% for entry in category.fetched('reverse:productCategories') %}
                        <li>{{ entry.getLink() }}</li>
                    {% endfor %}
                </ul>
            {% endfor %}
        </article>
    {% endfor %}
</section>
```

## Changelog

### 1.2.0

* Added support for adding a `reverse:` prefix to field handles, for eager-loading reverse relations.

### 1.1.0

* Access related Elements from a nicer `fetched` method dynamically added to the source Elements.
* Improved documentation and added LICENSE file.

### 1.0.0

* Limit relations by field handles rather than IDs.

### 0.1.0

* Fetch related Elements for multiple source elements by field IDs.

## Todo

* Tests.
* Make sure it works with localized setups.

## License

MIT
