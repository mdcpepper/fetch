<?php
namespace Craft;

/**
 * Fetch Variable class
 *
 * @author    Mike Pepper, Enovate Design Ltd <mike.pepper@enovate.co.uk>
 * @copyright Copyright (c) 2015, Enovate Design, Ltd.
 * @license   MIT
 * @package   craft.plugins.fetch.variables
 * @since     0.1.0
 */
class FetchVariable
{
	/**
	 * Returns an array of {$elementType}Model instances related to the
	 * elements passed in as the second parameter.
	 *
	 * You can optionally restrict the results to particular fields, and override the source
	 * locale.
	 *
	 * @since  1.1.0
	 *
	 * @param array              $elementType     The element type for the relation
	 * @param array              $sourceElements  An array of source elements
	 * @param string|array|null  $fieldHandles    An optional string or array of field handles
	 * @param string|null        $sourceLocale    An optional source locale ID
	 *
	 * @return array             An array of BaseElementModel instances, populated from the results
	 */
	public function elements($elementType, array $sourceElements, $fieldHandles = null, $sourceLocale = null)
	{
		return craft()->fetch->elements($elementType, $sourceElements, $fieldHandles, $sourceLocale);
	}

	/**
	 * Returns an array of AssetFileModel instances related to the
	 * elements passed in as the second parameter.
	 *
	 * You can optionally restrict the results to particular fields, and override the source
	 * locale.
	 *
	 * @since  0.1.0
	 *
	 * @param array              $sourceElements  An array of source elements
	 * @param string|array|null  $fieldHandles    An optional string or array of field handles
	 * @param string|null        $sourceLocale    An optional source locale ID
	 *
	 * @return array             An array of AssetFileModel instances, populated from the results
	 */
	public function assets(array $sourceElements, $fieldHandles = null, $sourceLocale = null)
	{
		return craft()->fetch->elements(ElementType::Asset, $sourceElements, $fieldHandles, $sourceLocale);
	}

	/**
	 * Returns an array of CategoryModel instances related to the
	 * elements passed in as the second parameter.
	 *
	 * You can optionally restrict the results to particular fields, and override the source
	 * locale.
	 *
	 * @since  0.1.0
	 *
	 * @param array              $sourceElements  An array of source elements
	 * @param string|array|null  $fieldHandles    An optional string or array of field handles
	 * @param string|null        $sourceLocale    An optional source locale ID
	 *
	 * @return array             An array of CategoryModel instances, populated from the results
	 */
	public function categories(array $sourceElements, $fieldHandles = null, $sourceLocale = null)
	{
		return craft()->fetch->elements(ElementType::Category, $sourceElements, $fieldHandles, $sourceLocale);
	}

	/**
	 * Returns an array of EntryModel instances related to the
	 * elements passed in as the second parameter.
	 *
	 * You can optionally restrict the results to particular fields, and override the source
	 * locale.
	 *
	 * @since  0.1.0
	 *
	 * @param array              $sourceElements  An array of source elements
	 * @param string|array|null  $fieldHandles    An optional string or array of field handles
	 * @param string|null        $sourceLocale    An optional source locale ID
	 *
	 * @return array             An array of EntryModel instances, populated from the results
	 */
	public function entries(array $sourceElements, $fieldHandles = null, $sourceLocale = null)
	{
		return craft()->fetch->elements(ElementType::Entry, $sourceElements, $fieldHandles, $sourceLocale);
	}

	/**
	 * Returns an array of TagModel instances related to the
	 * elements passed in as the second parameter.
	 *
	 * You can optionally restrict the results to particular fields, and override the source
	 * locale.
	 *
	 * @since  0.1.0
	 *
	 * @param array              $sourceElements  An array of source elements
	 * @param string|array|null  $fieldHandles    An optional string or array of field handles
	 * @param string|null        $sourceLocale    An optional source locale ID
	 *
	 * @return array             An array of TagModel instances, populated from the results
	 */
	public function tags(array $sourceElements, $fieldHandles = null, $sourceLocale = null)
	{
		return craft()->fetch->elements(ElementType::Tag, $sourceElements, $fieldHandles, $sourceLocale);
	}

	/**
	 * Returns an array of UserModel instances related to the
	 * elements passed in as the second parameter.
	 *
	 * You can optionally restrict the results to particular fields, and override the source
	 * locale.
	 *
	 * @since  0.1.0
	 *
	 * @param array              $sourceElements  An array of source elements
	 * @param string|array|null  $fieldHandles    An optional string or array of field handles
	 * @param string|null        $sourceLocale    An optional source locale ID
	 *
	 * @return array             An array of UserModel instances, populated from the results
	 */
	public function users(array $sourceElements, $fieldHandles = null, $sourceLocale = null)
	{
		return craft()->fetch->elements(ElementType::User, $sourceElements, $fieldHandles, $sourceLocale);
	}
}