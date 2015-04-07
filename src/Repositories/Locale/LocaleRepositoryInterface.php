<?php

namespace Stevebauman\Localization\Repositories\Locale;

/**
 * Interface LocaleRepositoryInterface
 * @package Stevebauman\Localization\Repositories\Locale
 */
interface LocaleRepositoryInterface
{
	/**
	 * Returns a dataset compatible with data grid.
	 *
	 * @return \Stevebauman\Translation\Models\Locale
	 */
	public function grid();

	/**
	 * Returns all the localization entries.
	 *
	 * @return \Stevebauman\Translation\Models\Locale
	 */
	public function findAll();

	/**
	 * Returns a localization entry by its primary key.
	 *
	 * @param  int  $id
	 * @return \Stevebauman\Translation\Models\Locale
	 */
	public function find($id);

	/**
	 * Determines if the given localization is valid for creation.
	 *
	 * @param  array  $data
	 * @return \Illuminate\Support\MessageBag
	 */
	public function validForCreation(array $data);

	/**
	 * Determines if the given localization is valid for update.
	 *
	 * @param  int  $id
	 * @param  array  $data
	 * @return \Illuminate\Support\MessageBag
	 */
	public function validForUpdate($id, array $data);

	/**
	 * Creates or updates the given localization.
	 *
	 * @param  int  $id
	 * @param  array  $input
	 * @return bool|array
	 */
	public function store($id, array $input);

	/**
	 * Creates a localization entry with the given data.
	 *
	 * @param  array  $data
	 * @return \Stevebauman\Translation\Models\Locale
	 */
	public function create(array $data);

	/**
	 * Updates the localization entry with the given data.
	 *
	 * @param  int  $id
	 * @param  array  $data
	 * @return \Stevebauman\Translation\Models\Locale
	 */
	public function update($id, array $data);

	/**
	 * Deletes the localization entry.
	 *
	 * @param  int  $id
	 * @return bool
	 */
	public function delete($id);

}
