<?php
namespace Psecio\Iniscan\Rule;

/**
 * Custom operation - Checks to see if the session path
 * 	is world writeable
 */
class CheckSessionPath extends \Psecio\Iniscan\Rule
{
	public function __construct($config, $section)
	{
		parent::__construct($config, $section);
		$this->setTest(array('key' => 'session.save_path'));
	}

	public function evaluate(array $ini)
	{
		if (!isset($ini['Session']['session.save_path'])) {
			$this->setDescription('Path not set, default (/tmp) is world writeable');
			$this->fail();
			return false;
		}
		$savePath = $ini['Session']['session.save_path'];
		$perms = substr(sprintf('%o', fileperms($savePath)), - 3);
		if ($perms == 777) {
			$this->fail();
			$this->setDescription('Path '.$savePath.' is world writeable');
			return false;
		} else {
			$this->pass();
			return true;
		}
	}
}
