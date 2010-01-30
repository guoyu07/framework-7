<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2010, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace lithium\console;

use \lithium\util\String;

/**
 * Holds current request from console
 *
 *
 **/
class Response extends \lithium\core\Object {

	/**
	 * Output stream, STDOUT
	 *
	 * @var stream
	 **/
	public $output = null;

	/**
	 * Error stream, STDERR
	 *
	 * @var stream
	 **/
	public $error = null;

	/**
	 * Status code, most often used for setting an exit status.
	 *
	 * It should be expected that only status codes in the range of 0-255
	 * can be properly evalutated.
	 *
	 * @var integer
	 * @see lithium\console\Command
	 */
	public $status = 0;

	/**
	 * Construct Request object
	 *
	 * @param array $config
	 *              - request object lithium\console\Request
	 *              - output stream
	 *              _ error stream
	 * @return void
	 */
	public function __construct($config = array()) {
		$defaults = array('output' => null, 'error' => null);
		$config += $defaults;

		$this->output = $config['output'];

		if (!is_resource($this->output)) {
			$this->output = fopen('php://stdout', 'r');;
		}

		$this->error = $config['error'];

		if (!is_resource($this->error)) {
			$this->error = fopen('php://stderr', 'r');;
		}
		parent::__construct($config);
	}

	/**
	 * Writes string to output stream
	 *
	 * @param string $string
	 * @return mixed
	 */
	public function output($string) {
		fwrite($this->output, String::insert($string, $this->styles()));
	}

	/**
	 * Writes string to error stream
	 *
	 * @param string $string
	 * @return mixed
	 */
	public function error($error) {
		return fwrite($this->error, $error);
	}

	/**
	 * Destructor to close streams
	 *
	 * @return void
	 *
	 **/
	public function __destruct() {
		fclose($this->output);
		fclose($this->error);
	}
	
	/**
	 * Handles styling output.
	 *
	 * @param array $styles 
	 * @return array
	 */
	public function styles($styles = array()) {
		$defaults = array(
			'heading1' => "\033[1;30;46m",
			'heading2' => "\033[1;35m",
			'heading3' => "\033[1;34m",
			'option'   => "\033[40;37m",
			'command'  => "\033[1;40;37m",
			'black'  => "\033[0;30m",
			'red'    => "\033[0;31m",
			'green'  => "\033[0;32m",
			'yellow' => "\033[0;33m",
			'blue'   => "\033[0;34m",
			'purple' => "\033[0;35m",
			'cyan'   => "\033[0;36m",
			'white'  => "\033[0;37m",
			'end'    => "\033[0m",
			);
		$styles += $defaults;
		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
			$styles = array_flip(array_keys($styles));
		}
		return $styles;
	}
}

?>