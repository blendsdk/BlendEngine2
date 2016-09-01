<?php

/*
 * This file is part of the BlendEngine framework.
 *
 * (c) Gevik Babakhani <gevikb@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Blend\Component\Session;

use Symfony\Component\HttpFoundation\Request;

/**
 * @author Gevik Babakhani <gevikb@gmail.com>
 */
interface SessionProviderInterface {

    public function getSession();

    public function initializeSession(Request $request);
}