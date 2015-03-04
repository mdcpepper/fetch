<?php
namespace Craft;

/**
 * Fetch variable class
 *
 * @author    Mike Pepper, Enovate Design Ltd <mike.pepper@enovate.co.uk>
 * @copyright Copyright (c) 2015, Enovate Design, Ltd.
 * @since     0.1.0
 */
class FetchVariable
{
	public function assets(array $elementIds, $fieldId = null, $sourceLocale = null)
	{
		return craft()->fetch->elements(ElementType::Asset, $elementIds, $fieldId, $sourceLocale);
	}

	public function categories(array $elementIds, $fieldId = null, $sourceLocale = null)
	{
		return craft()->fetch->elements(ElementType::Category, $elementIds, $fieldId, $sourceLocale);
	}

	public function entries(array $elementIds, $fieldId = null, $sourceLocale = null)
	{
		return craft()->fetch->elements(ElementType::Entry, $elementIds, $fieldId, $sourceLocale);
	}

	public function matrixBlocks(array $elementIds, $fieldId = null, $sourceLocale = null)
	{
		return craft()->fetch->elements(ElementType::MatrixBlock, $elementIds, $fieldId, $sourceLocale);
	}

	public function tags(array $elementIds, $fieldId = null, $sourceLocale = null)
	{
		return craft()->fetch->elements(ElementType::Tag, $elementIds, $fieldId, $sourceLocale);
	}

	public function users(array $elementIds, $fieldId = null, $sourceLocale = null)
	{
		return craft()->fetch->elements(ElementType::User, $elementIds, $fieldId, $sourceLocale);
	}

	public function getIds(array $elements)
	{
		return craft()->fetch->getIds($elements);
	}

	public function exists(array $collection, $sourceElementId, $fieldId = null)
	{
		return craft()->fetch->exists($collection, $sourceElementId, $fieldId);
	}
}