<?php
namespace Craft;

/**
 * Fetch plugin class
 *
 * @author    Mike Pepper, Enovate Design Ltd <mike.pepper@enovate.co.uk>
 * @copyright Copyright (c) 2015, Enovate Design, Ltd.
 * @license   MIT
 * @package   craft.plugins.fetch
 * @since     0.1.0
 */
class FetchPlugin extends BasePlugin implements IPlugin
{
	/**
	 * Get the plugin name
	 *
	 * @since  0.1.0
	 * @return string The plugin name
	 */
	public function getName()
	{
		return Craft::t('Fetch');
	}

	/**
	 * Get the plugin version
	 *
	 * @since  0.1.0
	 * @return string The current version of the plugin
	 */
	public function getVersion()
	{
		return '1.2.0';
	}

	/**
	 * Get the plugin developer name
	 *
	 * @since  0.1.0
	 * @return string The developer name
	 */
	public function getDeveloper()
	{
		return 'Enovate Design';
	}

	/**
	 * Get the plugin developer url
	 *
	 * @since  0.1.0
	 * @return string The developer URL
	 */
	public function getDeveloperUrl()
	{
		return 'http://www.enovate.co.uk';
	}

	/**
	 * Returns true if the plugin should have a CP section link, false if not.
	 *
	 * @since  0.1.0
	 * @return boolean
	 */
	public function hasCpSection()
	{
		return false;
	}

	/**
	 * Plugin initialization
	 *
	 * Loads the Fetch_FetchedElementsBehavior
	 *
	 * @since 1.1.0
	 */
	public function init()
	{
		Craft::import('plugins.fetch.behaviors.Fetch_FetchedElementsBehavior');
	}
}