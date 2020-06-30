<?php
/* Copyright (c) 1998-2009 ILIAS open source, Extended GPL, see docs/LICENSE */

/**
 * Class xvmpConfFormGUI
 *
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class xvmpConfFormGUI extends xvmpFormGUI {

	/**
	 * @var ilViMPConfigGUI
	 */
	protected $parent_gui;
	/**
	 * @var ilDB
	 */
	protected $db;

	/**
	 * xvmpConfFormGUI constructor.
	 *
	 * @param $parent_gui
	 */
	public function __construct($parent_gui) {
		global $DIC;
		$tpl = $DIC['tpl'];
		parent::__construct($parent_gui);

		$this->db = $DIC['ilDB'];
		$tpl->addJavaScript($this->pl->getDirectory() . '/js/xvmp_config.js');
	}

    /**
     *
     */
    protected function initForm(){
		$this->setFormAction($this->ctrl->getFormAction($this->parent_gui));

		// *** API SETTINGS ***
		$header = new ilFormSectionHeaderGUI();
		$header->setTitle($this->pl->confTxt('api_settings'));
		$this->addItem($header);

		// API User
		$input = new ilTextInputGUI($this->pl->confTxt(xvmpConf::F_API_USER), xvmpConf::F_API_USER);
		$input->setInfo($this->pl->confTxt(xvmpConf::F_API_USER . '_info'));
		$input->setRequired(true);
		$this->addItem($input);

		// API Password
		$input = new ilTextInputGUI($this->pl->confTxt(xvmpConf::F_API_PASSWORD), xvmpConf::F_API_PASSWORD);
		$input->setInfo($this->pl->confTxt(xvmpConf::F_API_PASSWORD . '_info'));
		$input->setRequired(true);
		$this->addItem($input);

		// API Key
		$input = new ilTextInputGUI($this->pl->confTxt(xvmpConf::F_API_KEY), xvmpConf::F_API_KEY);
		$input->setInfo($this->pl->confTxt(xvmpConf::F_API_KEY . '_info'));
		$input->setRequired(true);
		$this->addItem($input);

		// API Url
		$input = new ilTextInputGUI($this->pl->confTxt(xvmpConf::F_API_URL), xvmpConf::F_API_URL);
		$input->setInfo($this->pl->confTxt(xvmpConf::F_API_URL . '_info'));
		$input->setRequired(true);
		$this->addItem($input);

		// Test Connection Button
		$input = new ilCustomInputGUI();
		$input->setTitle('');
		$input->setHtml(
			"<a class='btn btn-default' id='xvmp_test_connection' onclick='VimpConfig.test_connection(event)' href='".$this->ctrl->getLinkTargetByClass(array('ilAdministrationGUI', 'ilObjViMPGUI'), 'testConnectionAjax', '', true)."'>Test Connection</a>
					<span id='xvmp_connection_status' style='margin-left: 5px'></span>");
		$this->addItem($input);

		// ignore ssl
	    $input = new ilCheckboxInputGUI($this->pl->confTxt(xvmpConf::F_DISABLE_VERIFY_PEER), xvmpConf::F_DISABLE_VERIFY_PEER);
	    $input->setInfo($this->pl->confTxt(xvmpConf::F_DISABLE_VERIFY_PEER . '_info'));
	    $this->addItem($input);

		// External User Mapping
		$input = new ilTextInputGUI($this->pl->confTxt(xvmpConf::F_USER_MAPPING_EXTERNAL), xvmpConf::F_USER_MAPPING_EXTERNAL);
		$input->setInfo($this->pl->confTxt(xvmpConf::F_USER_MAPPING_EXTERNAL . '_info'));
		$input->setRequired(true);
		$this->addItem($input);

		// Local User Mapping
		$input = new ilTextInputGUI($this->pl->confTxt(xvmpConf::F_USER_MAPPING_LOCAL), xvmpConf::F_USER_MAPPING_LOCAL);
		$input->setInfo($this->pl->confTxt(xvmpConf::F_USER_MAPPING_LOCAL . '_info'));
		$input->setRequired(true);
		$this->addItem($input);

		// Mapping Priority
		$input = new ilRadioGroupInputGUI($this->pl->confTxt(xvmpConf::F_MAPPING_PRIORITY), xvmpConf::F_MAPPING_PRIORITY);
		$input->setInfo($this->pl->confTxt(xvmpConf::F_MAPPING_PRIORITY . '_info'));
		$opt = new ilRadioOption($this->pl->confTxt(xvmpConf::F_MAPPING_PRIORITY . '_' . xvmpConf::PRIORITIZE_EMAIL), xvmpConf::PRIORITIZE_EMAIL);
		$input->addOption($opt);
		$opt = new ilRadioOption($this->pl->confTxt(xvmpConf::F_MAPPING_PRIORITY . '_' . xvmpConf::PRIORITIZE_MAPPING), xvmpConf::PRIORITIZE_MAPPING);
		$input->addOption($opt);
		$this->addItem($input);

		// *** GENERAL SETTINGS ***
		$header = new ilFormSectionHeaderGUI();
		$header->setTitle($this->pl->confTxt('general_settings'));
		$this->addItem($header);

		// Upload Limit
        $input = new ilNumberInputGUI($this->pl->confTxt(xvmpConf::F_UPLOAD_LIMIT), xvmpConf::F_UPLOAD_LIMIT);
        $input->setInfo($this->pl->confTxt(xvmpConf::F_UPLOAD_LIMIT . '_info'));
        $this->addItem($input);

		// Object Title
		$input = new ilTextInputGUI($this->pl->confTxt(xvmpConf::F_OBJECT_TITLE), xvmpConf::F_OBJECT_TITLE);
		$input->setRequired(true);
		$input->setInfo($this->pl->confTxt(xvmpConf::F_OBJECT_TITLE . '_info'));
		$this->addItem($input);


		// Default Publication
                $input = new ilSelectInputGUI($this->pl->confTxt(xvmpConf::F_DEFAULT_PUBLICATION), xvmpConf::F_DEFAULT_PUBLICATION);
                $input->setOptions(array(
                        9 => $this->pl->txt('not_set'),
                        0 => $this->pl->txt('public'),
                        1 => $this->pl->txt('private'),
                        2 => $this->pl->txt('hidden'),
                ));

                $input->setInfo($this->pl->confTxt(xvmpConf::F_DEFAULT_PUBLICATION . '_info'));
                $this->addItem($input);

		// Allow Public Upload
		$input = new ilCheckboxInputGUI($this->pl->confTxt(xvmpConf::F_ALLOW_PUBLIC), xvmpConf::F_ALLOW_PUBLIC);
		$input->setInfo($this->pl->confTxt(xvmpConf::F_ALLOW_PUBLIC . '_info'));

        $input2 = new ilCheckboxInputGUI($this->pl->confTxt(xvmpConf::F_ALLOW_PUBLIC_UPLOAD), xvmpConf::F_ALLOW_PUBLIC_UPLOAD);
        $input2->setInfo($this->pl->confTxt(xvmpConf::F_ALLOW_PUBLIC_UPLOAD . '_info'));
        $input->addSubItem($input2);

        $this->addItem($input);


        // Form Fields
		$input = new srGenericMultiInputGUI($this->pl->confTxt(xvmpConf::F_FORM_FIELDS), xvmpConf::F_FORM_FIELDS);
		$input->setAllowEmptyFields(true);
		$input->setRequired(false);
		$input->setInfo($this->pl->confTxt(xvmpConf::F_FORM_FIELDS . '_info'));
		$subinput = new ilTextInputGUI('', xvmpConf::F_FORM_FIELD_ID);
		$input->addInput($subinput);
		$subinput = new ilTextInputGUI('', xvmpConf::F_FORM_FIELD_TITLE);
		$input->addInput($subinput);
		$subinput = new ilCheckboxInputGUI('', xvmpConf::F_FORM_FIELD_REQUIRED);
		$input->addInput($subinput);
		$subinput = new ilCheckboxInputGUI('', xvmpConf::F_FORM_FIELD_FILL_USER_DATA);
		$input->addInput($subinput);
		$subinput = new ilSelectInputGUI('', xvmpConf::F_FORM_FIELD_TYPE);
		$subinput->setOptions(array(
		    0 => $this->pl->confTxt('form_field_type_' . xvmpConf::F_FORM_FIELD_TYPE_TEXT),
		    1 => $this->pl->confTxt('form_field_type_' . xvmpConf::F_FORM_FIELD_TYPE_CHECKBOX)
        ));
		$input->addInput($subinput);
		$this->addItem($input);

		// Filter Fields
		$input = new srGenericMultiInputGUI($this->pl->confTxt(xvmpConf::F_FILTER_FIELDS), xvmpConf::F_FILTER_FIELDS);
		$input->setRequired(false);
		$input->setAllowEmptyFields(true);
		$input->setInfo($this->pl->confTxt(xvmpConf::F_FILTER_FIELDS . '_info'));
		$subinput = new ilTextInputGUI('', xvmpConf::F_FILTER_FIELD_ID);
		$input->addInput($subinput);
		$subinput = new ilTextInputGUI('', xvmpConf::F_FILTER_FIELD_TITLE);
		$input->addInput($subinput);
		$this->addItem($input);


		// Media Permission
		$input = new ilRadioGroupInputGUI($this->pl->confTxt(xvmpConf::F_MEDIA_PERMISSIONS), xvmpConf::F_MEDIA_PERMISSIONS);
		$input->setInfo($this->pl->confTxt(xvmpConf::F_MEDIA_PERMISSIONS . '_info'));

		$radio_option = new ilRadioOption($this->lng->txt('no'), xvmpConf::MEDIA_PERMISSION_OFF);
		$input->addOption($radio_option);

		$radio_option = new ilRadioOption($this->pl->txt('all'), xvmpConf::MEDIA_PERMISSION_ON);
		$input->addOption($radio_option);

		$radio_option = new ilRadioOption($this->pl->txt('selection'), xvmpConf::MEDIA_PERMISSION_SELECTION);
		$sub_selection = new ilMultiSelectSearchInputGUI('', xvmpConf::F_MEDIA_PERMISSIONS_SELECTION);
		$options = $this->getMediaPermissionOptions();
		$sub_selection->setOptions($options);
		$sub_selection->setDisabled(empty($options));
		$radio_option->addSubItem($sub_selection);
		$input->addOption($radio_option);

		$this->addItem($input);


		// Media Permission Preselection
		$input = new ilCheckboxInputGUI($this->pl->confTxt(xvmpConf::F_MEDIA_PERMISSIONS_PRESELECTED), xvmpConf::F_MEDIA_PERMISSIONS_PRESELECTED);
		$input->setInfo($this->pl->confTxt(xvmpConf::F_MEDIA_PERMISSIONS_PRESELECTED . '_info'));
		$this->addItem($input);


		// Embedded Player
		$input = new ilCheckboxInputGUI($this->pl->confTxt(xvmpConf::F_EMBED_PLAYER), xvmpConf::F_EMBED_PLAYER);
		$input->setInfo($this->pl->confTxt(xvmpConf::F_EMBED_PLAYER . '_info'));
		$this->addItem($input);


		// *** NOTIFICATION ***
		$header = new ilFormSectionHeaderGUI();
		$header->setTitle($this->pl->txt('notification'));
		$this->addItem($header);

		// Noticiation Subject
		$input = new ilTextInputGUI($this->pl->confTxt(xvmpConf::F_NOTIFICATION_SUBJECT_SUCCESSFULL), xvmpConf::F_NOTIFICATION_SUBJECT_SUCCESSFULL);
		$input->setInfo($this->pl->confTxt(xvmpConf::F_NOTIFICATION_SUBJECT_SUCCESSFULL . '_info'));
		$input->setRequired(true);
		$this->addItem($input);

		// Noticiation Body
		$input = new ilTextAreaInputGUI($this->pl->confTxt(xvmpConf::F_NOTIFICATION_BODY_SUCCESSFULL), xvmpConf::F_NOTIFICATION_BODY_SUCCESSFULL);
		$input->setInfo($this->pl->confTxt(xvmpConf::F_NOTIFICATION_BODY_SUCCESSFULL . '_info'));
		$input->setRequired(true);
		$this->addItem($input);

		// Noticiation Subject
		$input = new ilTextInputGUI($this->pl->confTxt(xvmpConf::F_NOTIFICATION_SUBJECT_FAILED), xvmpConf::F_NOTIFICATION_SUBJECT_FAILED);
		$input->setInfo($this->pl->confTxt(xvmpConf::F_NOTIFICATION_SUBJECT_FAILED . '_info'));
		$input->setRequired(true);
		$this->addItem($input);

		// Noticiation Body
		$input = new ilTextAreaInputGUI($this->pl->confTxt(xvmpConf::F_NOTIFICATION_BODY_FAILED), xvmpConf::F_NOTIFICATION_BODY_FAILED);
		$input->setInfo($this->pl->confTxt(xvmpConf::F_NOTIFICATION_BODY_FAILED . '_info'));
		$input->setRequired(true);
		$this->addItem($input);


		// *** CACHE ***
		$header = new ilFormSectionHeaderGUI();
		$header->setTitle($this->pl->confTxt('cache'));
		$header->setInfo($this->pl->confTxt('cache_info'));
		$this->addItem($header);

		// Video Cache TTL
		$input = new ilNumberInputGUI($this->pl->confTxt(xvmpConf::F_CACHE_TTL_VIDEOS), xvmpConf::F_CACHE_TTL_VIDEOS);
		$input->setInfo($this->pl->confTxt(xvmpConf::F_CACHE_TTL_VIDEOS . '_info'));
		$this->addItem($input);

		// User Cache TTL
		$input = new ilNumberInputGUI($this->pl->confTxt(xvmpConf::F_CACHE_TTL_USERS), xvmpConf::F_CACHE_TTL_USERS);
		$input->setInfo($this->pl->confTxt(xvmpConf::F_CACHE_TTL_USERS . '_info'));
		$this->addItem($input);

		// Category Cache TTL
		$input = new ilNumberInputGUI($this->pl->confTxt(xvmpConf::F_CACHE_TTL_CATEGORIES), xvmpConf::F_CACHE_TTL_CATEGORIES);
		$input->setInfo($this->pl->confTxt(xvmpConf::F_CACHE_TTL_CATEGORIES . '_info'));
		$this->addItem($input);

		// Token Cache TTL
		$input = new ilNumberInputGUI($this->pl->confTxt(xvmpConf::F_CACHE_TTL_TOKEN), xvmpConf::F_CACHE_TTL_TOKEN);
		$input->setInfo($this->pl->confTxt(xvmpConf::F_CACHE_TTL_TOKEN . '_info'));
		$this->addItem($input);

		// Config TTL
		$input = new ilNumberInputGUI($this->pl->confTxt(xvmpConf::F_CACHE_TTL_CONFIG), xvmpConf::F_CACHE_TTL_CONFIG);
		$input->setInfo($this->pl->confTxt(xvmpConf::F_CACHE_TTL_CONFIG . '_info'));
		$this->addItem($input);


		// Buttons
		$this->addCommandButton(ilViMPConfigGUI::CMD_UPDATE,$this->lng->txt('save'));
	}


    /**
     * @return array
     */
    protected function getMediaPermissionOptions() {
		$options = array();
		if ($this->pl->hasConnection()) {
			$roles = xvmpUserRoles::getAll();
			foreach ($roles as $role) {
				$options[$role->getId()] = $role->getName();
			}
		}

		return $options;
	}

    /**
     *
     */
    public function fillForm() {
		$array = array();
		foreach ($this->getItems() as $item) {
			$this->getValuesForItem($item, $array);
		}
		$this->setValuesByArray($array);
	}


	/**
	 * @param $item
	 * @param $array
	 *
	 * @internal param $key
	 */
	private function getValuesForItem($item, &$array) {
		if (self::checkItem($item)) {
			$key = rtrim($item->getPostVar(), '[]');
			if ($key == xvmpConf::F_OBJECT_TITLE) {
				$sql = $this->db->query('select value from lng_data where module = "rep_robj_xvmp" and identifier = "rep_robj_xvmp_obj_xvmp"');
				$value = $this->db->fetchObject($sql)->value;
			} else {
				$value = xvmpConf::getConfig($key);
			}
			$array[$key] = $value;
			if (self::checkForSubItem($item)) {
				foreach ($item->getSubItems() as $subitem) {
					$this->getValuesForItem($subitem, $array);
				}
				if ($item instanceof ilRadioGroupInputGUI) {
					foreach ($item->getOptions() as $option) {
						foreach ($option->getSubItems() as $subitem) {
							$this->getValuesForItem($subitem, $array);
						}
					}
				}
			}
		}
	}


	/**
	 * @param $item
	 */
	private function saveValueForItem($item) {
		if (self::checkItem($item)) {
			$key = rtrim($item->getPostVar(), '[]');
			$value = $this->getInput($key);

			// exception: object title is stored in lng_data, not in config table
			if ($key == xvmpConf::F_OBJECT_TITLE) {
				// obj
				$sql = $this->db->query('select value from lng_data where module = "rep_robj_xvmp" and identifier = "rep_robj_xvmp_obj_xvmp"');
				$existing = $this->db->fetchObject($sql);

				if ($existing) {
					$this->db->update('lng_data',array(
						'value' => array('text', $value)
					), array(
						'module' => array('text', 'rep_robj_xvmp'),
						'identifier' => array('text', 'rep_robj_xvmp_obj_xvmp'),
					));
				} else {
					$this->db->insert('lng_data',array(
						'lang_key' => array('text', 'de'),
						'module' => array('text', 'rep_robj_xvmp'),
						'identifier' => array('text', 'rep_robj_xvmp_obj_xvmp'),
						'value' => array('text', $value)
					));
					$this->db->insert('lng_data',array(
						'lang_key' => array('text', 'en'),
						'module' => array('text', 'rep_robj_xvmp'),
						'identifier' => array('text', 'rep_robj_xvmp_obj_xvmp'),
						'value' => array('text', $value)
					));
				}

				// objs
				$sql = $this->db->query('select value from lng_data where module = "rep_robj_xvmp" and identifier = "rep_robj_xvmp_objs_xvmp"');
				$existing = $this->db->fetchObject($sql);

				if ($existing) {
					$this->db->update('lng_data',array(
						'value' => array('text', $value)
					), array(
						'module' => array('text', 'rep_robj_xvmp'),
						'identifier' => array('text', 'rep_robj_xvmp_objs_xvmp'),
					));
				} else {
					$this->db->insert('lng_data',array(
						'lang_key' => array('text', 'de'),
						'module' => array('text', 'rep_robj_xvmp'),
						'identifier' => array('text', 'rep_robj_xvmp_objs_xvmp'),
						'value' => array('text', $value)
					));
					$this->db->insert('lng_data',array(
						'lang_key' => array('text', 'en'),
						'module' => array('text', 'rep_robj_xvmp'),
						'identifier' => array('text', 'rep_robj_xvmp_objs_xvmp'),
						'value' => array('text', $value)
					));
				}
				return;
			}

			// remove empty value for multi input gui
			if ($key == xvmpConf::F_FORM_FIELDS || $key == xvmpConf::F_FILTER_FIELDS) {
				foreach ($value as $k => $v) {
					if (!$v[xvmpConf::F_FORM_FIELD_ID] && !$v[xvmpConf::F_FILTER_FIELD_ID]) {
						unset($value[$k]);
					}
				}
			}

			xvmpConf::set($key, $value);
			if (self::checkForSubItem($item)) {
				foreach ($item->getSubItems() as $subitem) {
					$this->saveValueForItem($subitem);
				}
				if ($item instanceof ilRadioGroupInputGUI) {
					foreach ($item->getOptions() as $option) {
						foreach ($option->getSubItems() as $subitem) {
							$this->saveValueForItem($subitem);
						}
					}
				}
			}
		}
	}

	/**
	 * @return bool
	 */
	public function saveObject() {
		if (!$this->checkInput()) {
			return false;
		}
		foreach ($this->getItems() as $item) {
			$this->saveValueForItem($item);
		}
		xvmpConf::set(xvmpConf::F_CONFIG_VERSION, xvmpConf::CONFIG_VERSION);

		return true;
	}


	/**
	 * @param $item
	 *
	 * @return bool
	 */
	public static function checkForSubItem($item) {
		return !$item instanceof ilFormSectionHeaderGUI AND !$item instanceof ilMultiSelectInputGUI;
	}


	/**
	 * @param $item
	 *
	 * @return bool
	 */
	public static function checkItem($item) {
		return !$item instanceof ilFormSectionHeaderGUI;
	}
}
