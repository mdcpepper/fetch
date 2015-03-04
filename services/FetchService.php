<?php
namespace Craft;

/**
 * Fetch variable class
 *
 * @author    Mike Pepper, Enovate Design Ltd <mike.pepper@enovate.co.uk>
 * @copyright Copyright (c) 2015, Enovate Design, Ltd.
 * @since     0.1.0
 */
class FetchService extends BaseApplicationComponent
{
	/**
	 * Returns an array of {$elementType}Model instances related to the
	 * elements with the IDs passed in as the second parameter.
	 *
	 * You can optionally restrict the results to particular field Ids, and override the source
	 * locale.
	 *
	 * @param array              $elementType  The element type for the relation
	 * @param array              $sourceIds    An array of source element IDs
	 * @param integer|array|null $fieldId      An optional field ID, or array of field IDs
	 * @param string             $sourceLocale An optional locale ID
	 *
	 * @return array             An array of AssetFileModel instances, populated from the results
	 */
	public function elements($elementType, array $sourceIds, $fieldId = null, $sourceLocale = null)
	{
		// This allows for passing an array of BaseElementModels, or and array
		// of IDs for the source
		$sourceIds = $this->getIds($sourceIds);

		$query = craft()->db->createCommand()
			->from('relations')
			->select('fieldId')
			->addSelect('sourceId')
			->addSelect('targetId')
			->addSelect('sortOrder')
			->where(array('in', 'sourceId', $sourceIds));

		if (is_int($fieldId))
		{
			$query = $query->andWhere(array('fieldId' => $fieldId));
		}

		if (is_array($fieldId))
		{
			$query = $query->andWhere(array('in', 'fieldId', $fieldId));
		}

		if (is_string($sourceLocale))
		{
			$query = $query->andWhere(array('sourceLocale' => $sourceLocale));
		}

		// This first query returns everything we need to know about
		// what elements are involved. (1st query)
		$relations = $query->queryAll();

		$targetIds = array();

		// Collect the targetIds so we can fetch all the assets in one go later on
		$targetIds = array_map(function($relation){ return $relation['targetId']; }, $relations);

		// Fetch all the related elements (2nd query)
		$elements = craft()->elements->getCriteria($elementType)
						->id($targetIds)
						->locale($sourceLocale)
						->indexBy('id')
						->find();

		// Now we need to match the related elements to their sources and fields,
		// and return them in their default sort order.

		// first make sure all the sourceIds at least exist as empty arrays
		// $results = array_fill_keys($sourceIds, array());

		$results = array();

		foreach($relations as $relation)
		{
			$sourceId  = $relation['sourceId'];
			$fieldId   = $relation['fieldId'];
			$targetId  = $relation['targetId'];
			$sortOrder = ((int) $relation['sortOrder']) - 1;

			$results[$sourceId][$fieldId][$sortOrder] = $elements[$targetId];
		}

		return $results;
	}

	/**
	 * Returns an array of elements' IDs.
	 *
	 * @param  array $elements An array of elements
	 * @return array           An array of those elements' IDs
	 */
	public function getIds(array $elements)
	{
		// If the first element is an integer, we're already dealing
		// with an array of IDs, so return it immediately
		if (!empty($elements) && is_int($elements[0]))
		{
			return $elements;
		}

		$elementIds = array();

		foreach ($elements as $element)
		{
			$elementIds[] = $element->id;
		}

		return $elementIds;
	}

	/**
	 * Returns whether there are any related elements for the given source element ID and optional
	 * field ID.
	 *
	 * @param  int  $sourceElementId
	 * @param  int  $fieldId
	 * @return bool
	 */
	public function exists(array $collection, $sourceElementId, $fieldId = null)
	{
		// if sourceElementId doesnt exist, or there's nothing related, return false
		if (!array_key_exists($sourceElementId, $collection) || empty($collection[$sourceElementId]))
		{
			return false;
		}

		// if the fieldID is set but doesn't exist or is empty, return false
		if ($fieldId && (!array_key_exists($fieldId, $collection[$sourceElementId]) || empty($collection[$sourceElementId][$fieldId])))
		{
			return false;
		}

		return true;
	}
}