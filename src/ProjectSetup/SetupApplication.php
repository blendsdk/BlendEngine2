<?php

/*
 * This file is part of the BlendEngine framework.
 *
 * (c) Gevik Babakhani <gevikb@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Blend\ProjectSetup;

use Blend\Framework\Console\Application;
use Blend\ProjectSetup\Command\InitCommand;

/**
 * ProjectSetupApplication
 *
 * @author Gevik Babakhani <gevikb@gmail.com>
 */
class SetupApplication extends Application {

    public function __construct($script_dir) {
        parent::__construct($script_dir, 'BlendEngine Setup Utility', '1.0');
        $this->add(new InitCommand());
    }

}
