<?php
namespace Craft;

/**
 * Fetch variable class
 *
 * @author    Mike Pepper, Enovate Design Ltd <mike.pepper@enovate.co.uk>
 * @copyright Copyright (c) 2015, Enovate Design, Ltd.
 * @license   MIT
 * @package   craft.plugins.fetch.services
 * @since     0.1.0
 */
class FetchService extends BaseApplicationComponent
{
	/**
	 * Returns an array of {$elementType}Model instances related to the
	 * elements passed in as the second parameter.
	 *
	 * You can optionally restrict the results to particular fields, and override the source
	 * locale.
	 *
	 * @since 0.1.0
	 *
	 * @param array              $elementType     The element type for the relation
	 * @param array              $sourceElements  An array of source elements
	 * @param string|array|null  $fieldHandles    An optional string or array of field handles
	 * @param string|null        $sourceLocale    An optional locale ID
	 *
	 * @return array             An array of BaseElementModel instances, populated from the results
	 */
	public function elements($elementType, array &$sourceElements, $fieldHandles = null, $sourceLocale = null)
	{
		$sourceElementsById = array();
		$targetIds          = array();

		// Cast the field handles to an array
		$fieldHandles = ArrayHelper::stringToArray($fieldHandles);

		// reorder the source elements by ID
		foreach($sourceElements as $sourceElement)
		{
			$sourceElementsById[$sourceElement->id] = $sourceElement;
		}

		// Attach the behavior to each of the source elements
		foreach($sourceElementsById as $sourceElement)
		{
			$sourceElement->attachBehavior('fetched_elements', new Fetch_FetchedElementsBehavior());
		}

		// Perform the first query to get the information from the craft_relations table
		$relations = $this->_getRelations($sourceElementsById, $fieldHandles, $sourceLocale);

		// Collect the targetIds so we can fetch all the assets in one go later on
		$targetIds = array_map(function($relation){ return $relation['targetId']; }, $relations);

		// Perform the second query to fetch all the related elements by their IDs
		$elements = craft()->elements->getCriteria($elementType)
						->id($targetIds)
						->indexBy('id')
						->locale($sourceLocale)
						->find();

		// Add each related element to it's source element, using the Fetch_FetchedElementsBehavior
		foreach($relations as &$relation)
		{
			$sourceId    = $relation['sourceId'];
			$fieldHandle = $relation['handle'];
			$targetId    = $relation['targetId'];
			$sortOrder   = ((int) $relation['sortOrder']) - 1;

			$sourceElementsById[$sourceId]->addFetchedElement($elements[$targetId], $fieldHandle, $sortOrder);
			unset($sourceId, $fieldHandle, $targetId, $sourceOrder, $relation);
		}

		// Return the modified entries in their original order
		foreach($sourceElements as &$sourceElement)
		{
			$sourceElement = $sourceElementsById[$sourceElement->id];
			unset($sourceElementsById[$sourceElement->id]);
		}

		return $sourceElements;
	}

	/**
	 * Returns the results of the database query that fetches all the relations information from the
	 * `craft_relations` table for the source elements supplied as the first parameter, and
	 * optionally by an array of field handles and a source locale.
	 *
	 * @since 1.0.0
	 *
	 * @param array       $sourceElementsByID  An array of source element models, indexed by ID
	 * @param array       $fieldHandles
	 * @param string|null $sourceLocale
	 *
	 * @return array
	 */
	private function _getRelations(array $sourceElementsById, array $fieldHandles = array(), $sourceLocale = null)
	{
		$sourceElementIds = array_keys($sourceElementsById);

		$query = craft()->db->createCommand()
			->from('relations relations')
			->select('fieldId')
			->addSelect('sourceId, targetId, sortOrder, fields.handle')
			->join('fields fields', 'fields.id=relations.fieldId')
			->where(array('in', 'sourceId', $sourceElementIds));

		if (1 == count($fieldHandles))
		{
			$query = $query->andWhere(array('fields.handle' => $fieldHandles[0]));
		}
		elseif (!empty($fieldHandles))
		{
			$query = $query->andWhere(array('in', 'fields.handle', $fieldHandles));
		}

		if (is_string($sourceLocale))
		{
			$query = $query->andWhere(array('sourceLocale' => $sourceLocale));
		}

		return $query->queryAll();
	}
}