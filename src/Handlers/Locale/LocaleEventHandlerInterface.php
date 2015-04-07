<?php namespace Stevebauman\Localization\Handlers\Locale;

use Stevebauman\Localization\Models\Locale;
use Cartalyst\Support\Handlers\EventHandlerInterface as BaseEventHandlerInterface;

interface LocaleEventHandlerInterface extends BaseEventHandlerInterface {

	/**
	 * When a locale is being created.
	 *
	 * @param  array  $data
	 * @return mixed
	 */
	public function creating(array $data);

	/**
	 * When a locale is created.
	 *
	 * @param  \Stevebauman\Localization\Models\Locale  $locale
	 * @return mixed
	 */
	public function created(Locale $locale);

	/**
	 * When a locale is being updated.
	 *
	 * @param  \Stevebauman\Localization\Models\Locale  $locale
	 * @param  array  $data
	 * @return mixed
	 */
	public function updating(Locale $locale, array $data);

	/**
	 * When a locale is updated.
	 *
	 * @param  \Stevebauman\Localization\Models\Locale  $locale
	 * @return mixed
	 */
	public function updated(Locale $locale);

	/**
	 * When a locale is deleted.
	 *
	 * @param  \Stevebauman\Localization\Models\Locale  $locale
	 * @return mixed
	 */
	public function deleted(Locale $locale);

}
