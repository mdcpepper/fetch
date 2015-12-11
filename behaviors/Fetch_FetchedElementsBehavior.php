<?php
namespace Craft;

/**
 * Fetched Elements Behavior class
 *
 * @author    Mike Pepper, Enovate Design Ltd <mike.pepper@enovate.co.uk>
 * @copyright Copyright (c) 2015, Enovate Design, Ltd.
 * @package   craft.plugins.fetch.behaviors
 * @license   MIT
 * @since     1.1.0
 */
class Fetch_FetchedElementsBehavior extends BaseBehavior
{
	protected $_elementsByFieldHandle = array();
	protected $_sortOrdersByFieldHandle = array();

	/**
	 * Returns an array of pre-fetched related elements, optionally restricted by one or more
	 * relations field handles.
	 *
	 * @param  array|null  $fieldHandles
	 *
	 * @return array
	 */
	public function fetched($fieldHandles = null)
	{
		$elements = array();

		if ($fieldHandles)
		{
			$fieldHandles = ArrayHelper::stringToArray($fieldHandles);
		}
		else
		{
			$fieldHandles = array_keys($this->_elementsByFieldHandle);
		}

		foreach ($fieldHandles as $fieldHandle)
		{
			if (array_key_exists($fieldHandle, $this->_elementsByFieldHandle))
			{
				$elements = array_merge($elements, $this->_elementsByFieldHandle[$fieldHandle]);
			}
		}

		return $elements;
	}

	/**
	 * Adds a related element to this element's fetched relations, by the handle of its Relations
	 * field.
	 *
	 * @param  BaseElementModel  $element
	 * @param  string            $fieldHandle
	 * @param  int               $sortOrder
	 * @throws Exception
	 *
	 * @return bool
	 */
	public function addFetchedElement(BaseElementModel $element, $fieldHandle, $sortOrder)
	{
		if (!is_string($fieldHandle))
		{
			throw new Exception(Craft::t('$fieldHandle must be a string'));
		}

		if (!is_int($sortOrder))
		{
			throw new Exception(Craft::t('$sortOrder must be an integer'));
		}

		if (!array_key_exists($fieldHandle, $this->_elementsByFieldHandle))
		{
			$this->_elementsByFieldHandle[$fieldHandle] = array();
		}

		$this->_elementsByFieldHandle[$fieldHandle][] = $element;
		$this->_sortOrdersByFieldHandle[$fieldHandle][] = $sortOrder;

		// Keep the elements sorted based on the sort order
		array_multisort($this->_sortOrdersByFieldHandle[$fieldHandle], $this->_elementsByFieldHandle[$fieldHandle]);

		return true;
	}
}
