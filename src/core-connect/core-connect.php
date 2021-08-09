<?php
/**
 * Base file for CoreConnect component of CommonsConnect
 *
 * @package MESHResearch\CommonsConnect
 * @subpackage CoreConnect
 * @author Mike Thicke
 *
 * @since 0.3.0
 */

namespace MESHResearch\CommonsConnect\CoreConnect;

const HC_BASE_URL = 'https://hcommons.org/deposits/'; // Default Base URL for repository.

require_once 'class-remote-repository.php';
require_once 'class-hcommons-repository.php';
require_once 'class-json-schema.php';
require_once 'rest.php';
