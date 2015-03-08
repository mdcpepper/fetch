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
	 * You can optionally restrict the results to particular fields, and override the source
	 * locale.
	 *
	 * @param array              $elementType   The element type for the relation
	 * @param array              $sourceIds     An array of source element IDs
	 * @param string|array|null  $fieldHandles  An optional field ID, or array of field IDs
	 * @param string             $sourceLocale  An optional locale ID
	 *
	 * @return array             An array of BaseElementModel instances, populated from the results
	 */
	public function elements($elementType, array $sourceIds, $fieldHandles = null, $sourceLocale = null)
	{
		// This allows for passing an array of BaseElementModels, or an array
		// of IDs for the source
		$sourceIds = $this->getIds($sourceIds);

		$query = craft()->db->createCommand()
			->from('relations relations')
			->select('fieldId')
			->addSelect('sourceId, targetId, sortOrder, fields.handle')
			->join('fields fields', 'fields.id=relations.fieldId')
			->where(array('in', 'sourceId', $sourceIds));

		if (is_string($fieldHandles))
		{
			$query = $query->andWhere(array('fields.handle' => $fieldHandles));
		}

		if (is_array($fieldHandles))
		{
			$query = $query->andWhere(array('in', 'fields.handle', $fieldHandles));
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

		$results = array();

		foreach($relations as $relation)
		{
			$sourceId    = $relation['sourceId'];
			$fieldHandle = $relation['handle'];
			$targetId    = $relation['targetId'];
			$sortOrder   = ((int) $relation['sortOrder']) - 1;

			$results[$sourceId][$fieldHandle][$sortOrder] = $elements[$targetId];
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
	public function exists(array $collection, $sourceElementId, $fieldHandle = null)
	{
		// if sourceElementId doesnt exist, or there's nothing related, return false
		if (!array_key_exists($sourceElementId, $collection) || empty($collection[$sourceElementId]))
		{
			return false;
		}

		// if the fieldID is set but doesn't exist or is empty, return false
		if ($fieldHandle && (!array_key_exists($fieldHandle, $collection[$sourceElementId]) || empty($collection[$sourceElementId][$fieldHandle])))
		{
			return false;
		}

		return true;
	}
}