<?php
/* Copyright (c) 1998-2009 ILIAS open source, Extended GPL, see docs/LICENSE */
/**
 * Class xvmpSearchVideosTableGUI
 *
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class xvmpSearchVideosTableGUI extends xvmpTableGUI {

	const ROW_TEMPLATE = 'tpl.search_videos_row.html';
	const THUMBSIZE = '170x108';

	protected $available_columns = array(
		'thumbnail' => array(
			'no_header' => true
		),
		'title' => array(
			'sort_field' => 'title'
		),
		'description' => array(
			'sort_field' => 'description'
		),
		'username' => array(
			'sort_field' => 'user'
		),
		'copyright' => array(
			'sort_field' => 'copyright'
		),
		'created_at' => array(
			'sort_field' => 'unix_time'
		)
	);

	protected $available_filters = array(
		'title' => array(
			'input_gui' => 'ilTextInputGUI',
			'post_var' => 'filterbyname'
		),
	);

	/**
	 * @var xvmpSearchVideosGUI
	 */
	protected $parent_obj;


	/**
	 * xvmpSearchVideosTableGUI constructor.
	 *
	 * @param int    $parent_gui
	 * @param string $parent_cmd
	 */
	public function __construct($parent_gui, $parent_cmd) {
		$this->addColumn('', '', 20, true);
		$this->addColumn('', '', 20, true);
		parent::__construct($parent_gui, $parent_cmd);
		$this->setExternalSorting(true);
	}


	/**
	 *
	 */
	public function parseData() {
		$filter = array('thumbsize' => self::THUMBSIZE);
		foreach ($this->filters as $filter_item) {
			$filter[$filter_item->getPostVar()] = $filter_item->getValue();
		}
		$this->setData(xvmpMedium::getFilteredAsArray($filter));
	}


	/**
	 * @param xvmpObject $a_set
	 */
	protected function fillRow($a_set) {
		foreach ($this->available_columns as $title => $props)
		{
			// DEV
			if (ilViMPPlugin::DEV && $title == 'thumbnail') {
				$a_set[$title] = str_replace('10.0.2.2', 'localhost', $a_set[$title]);
			}
			// DEV

			$this->tpl->setVariable('VAL_' . strtoupper($title), $a_set[$title]);
		}
	}
}