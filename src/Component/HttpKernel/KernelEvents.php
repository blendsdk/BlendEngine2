<?php

/*
 * This file is part of the BlendEngine framework.
 *
 * (c) Gevik Babakhani <gevikb@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Blend\Component\HttpKernel;

/**
 * Description of KernelEvents
 *
 * @author Gevik Babakhani <gevikb@gmail.com>
 */
class KernelEvents {

    const REQUEST = 'http.request';
    const REQUEST_EXCEPTION = 'http.request.exception';
    const CONTROLLER_RESPONSE = 'http.controller.response';
    const FINALIZE_RESPONSE = 'http.finalize.response';

    const PRIORITY_HIGHT = 1500;
    const PRIORITY_MEDIUM = 1000;
    CONST PRIORITY_LOW = 500;

}
