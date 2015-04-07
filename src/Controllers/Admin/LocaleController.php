<?php

namespace Stevebauman\Localization\Controllers\Admin;

use Illuminate\Config\Repository as Config;
use Stevebauman\Localization\Repositories\Locale\LocaleRepositoryInterface;
use Platform\Access\Controllers\AdminController;

/**
 * Class LocalesController
 * @package Stevebauman\Localization\Controllers\Admin
 */
class LocaleController extends AdminController
{
	/**
	 * {@inheritDoc}
	 */
	protected $csrfWhitelist = [
		'executeAction',
	];

    /**
     * The Localization repository.
     *
     * @var LocaleRepositoryInterface
     */
	protected $locales;

    /**
     * The configuration instance
     *
     * @var Config
     */
    protected $config;

	/**
	 * Holds all the mass actions we can execute.
	 *
	 * @var array
	 */
	protected $actions = [
		'delete',
		'enable',
		'disable',
	];

    /**
     * @param LocaleRepositoryInterface $locales
     * @param Config $config
     */
	public function __construct(LocaleRepositoryInterface $locales, Config $config)
	{
		parent::__construct();

		$this->locales = $locales;

        $this->config = $config;
	}

	/**
	 * Display a listing of locale.
	 *
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		return view('stevebauman/localization::locales.index');
	}

	/**
	 * Datasource for the locale Data Grid.
	 *
	 * @return \Cartalyst\DataGrid\DataGrid
	 */
	public function grid()
	{
		$data = $this->locales->grid();

		$columns = [
			'id',
            'code',
            'lang_code',
            'name',
            'display_name',
            'created_at',
		];

		$settings = [
			'sort'      => 'created_at',
			'direction' => 'desc',
		];

		$transformer = function($element)
		{
			$element->edit_uri = route('admin.localization.locales.edit', $element->id);

            $element->translations_uri = route('admin.localization.locales.translations.index', $element->id);

			return $element;
		};

		return datagrid($data, $columns, $settings, $transformer);
	}

	/**
	 * Show the form for creating new locale.
	 *
	 * @return \Illuminate\View\View
	 */
	public function create()
	{
		return $this->showForm('create');
	}

	/**
	 * Handle posting of the form for creating new locale.
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store()
	{
		return $this->processForm('create');
	}

	/**
	 * Show the form for updating locale.
	 *
	 * @param  int  $id
	 * @return mixed
	 */
	public function edit($id)
	{
		return $this->showForm('update', $id);
	}

	/**
	 * Handle posting of the form for updating locale.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function update($id)
	{
		return $this->processForm('update', $id);
	}

	/**
	 * Remove the specified locale.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function delete($id)
	{
		$type = $this->locales->delete($id) ? 'success' : 'error';

		$this->alerts->{$type}(
			trans("stevebauman/localization::locales/message.{$type}.delete")
		);

		return redirect()->route('admin.localization.locales.index');
	}

	/**
	 * Executes the mass action.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function executeAction()
	{
		$action = request()->input('action');

		if (in_array($action, $this->actions))
		{
			foreach (request()->input('rows', []) as $row)
			{
				$this->locales->{$action}($row);
			}

			return response('Success');
		}

		return response('Failed', 500);
	}

	/**
	 * Shows the form.
	 *
	 * @param  string  $mode
	 * @param  int  $id
	 * @return mixed
	 */
	protected function showForm($mode, $id = null)
	{
		// Do we have a locale identifier?
		if (isset($id))
		{
			if ( ! $locale = $this->locales->findNonCached($id))
			{
				$this->alerts->error(trans('stevebauman/localization::locales/message.not_found', compact('id')));

				return redirect()->route('admin.localization.locales.index');
			}
		}
		else
		{
			$locale = $this->locales->createModel();
		}

        $locales = $this->config->get('translation.locales', array());

		// Show the page
		return view('stevebauman/localization::locales.form', compact('mode', 'locale', 'locales'));
	}

	/**
	 * Processes the form.
	 *
	 * @param  string  $mode
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	protected function processForm($mode, $id = null)
	{
		// Store the locale
		list($messages) = $this->locales->store($id, request()->all());

		// Do we have any errors?
		if ($messages->isEmpty())
		{
			$this->alerts->success(trans("stevebauman/localization::locales/message.success.{$mode}"));

			return redirect()->route('admin.localization.locales.index');
		}

		$this->alerts->error($messages, 'form');

		return redirect()->back()->withInput();
	}

}
