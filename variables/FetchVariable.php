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
	public function assets(array $elementIds, $fieldHandles = null, $sourceLocale = null)
	{
		return craft()->fetch->elements(ElementType::Asset, $elementIds, $fieldHandles, $sourceLocale);
	}

	public function categories(array $elementIds, $fieldHandles = null, $sourceLocale = null)
	{
		return craft()->fetch->elements(ElementType::Category, $elementIds, $fieldHandles, $sourceLocale);
	}

	public function entries(array $elementIds, $fieldHandles = null, $sourceLocale = null)
	{
		return craft()->fetch->elements(ElementType::Entry, $elementIds, $fieldHandles, $sourceLocale);
	}

	public function matrixBlocks(array $elementIds, $fieldHandles = null, $sourceLocale = null)
	{
		return craft()->fetch->elements(ElementType::MatrixBlock, $elementIds, $fieldHandles, $sourceLocale);
	}

	public function tags(array $elementIds, $fieldHandles = null, $sourceLocale = null)
	{
		return craft()->fetch->elements(ElementType::Tag, $elementIds, $fieldHandles, $sourceLocale);
	}

	public function users(array $elementIds, $fieldHandles = null, $sourceLocale = null)
	{
		return craft()->fetch->elements(ElementType::User, $elementIds, $fieldHandles, $sourceLocale);
	}

	public function getIds(array $elements)
	{
		return craft()->fetch->getIds($elements);
	}

	public function exists(array $collection, $sourceElementId, $fieldHandles = null)
	{
		return craft()->fetch->exists($collection, $sourceElementId, $fieldHandles);
	}
}