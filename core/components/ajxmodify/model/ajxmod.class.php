<?php

/**
 * AjaxModX
 *
 * Copyright 2013 by Florian Gutwald <florian@frontend-mercenary.com>
 *
 * This file is part of AjaxModX.
 *
 * AjaxModX is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * AjaxModX is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * AjaxModX; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
 * Suite 330, Boston, MA 02111-1307 USA
 *
 * @package ajxmod
 */
class AjxMod {

  public $modx = null;
  public $map = array();
  public $config = array();


  /**
   * Constructs the AjxMod object
   *
   * @param modX &$modx A reference to the modX object
   * @param array $config An array of configuration options
   */
  function __construct(modX &$modx, array $config = array()) {
    $this->modx =& $modx;

    $basePath = $this->modx->getOption("ajxmod.core_path", null, $this->modx->getOption("core_path") . "components/ajxmod/");
    $assetsUrl = $this->modx->getOption("ajxmod.assets_url", null, $this->modx->getOption("assets_url") . "components/ajxmod/");

    $this->config = array_merge(array(
      "basePath" => $basePath,
      "corePath" => $basePath,
      "modelPath" => $basePath . "model/",
      "processorsPath" => $basePath . "processors/",
      "templatesPath" => $basePath . "templates/",
      "chunksPath" => $basePath . "elements/chunks/",
      "jsUrl" => $assetsUrl . "js/",
      "cssUrl" => $assetsUrl . "css/",
      "assetsUrl" => $assetsUrl,
      "connectorUrl" => $assetsUrl . "connector.php",
    ), $config);


    $this->modx->addPackage("ajxmod", $this->config["modelPath"]);
  }

  /**
   * Initializes the class into the proper context
   *
   * @param string $ctx
   * @return bool|string
   */
  public function initialize($ctx = "web") {
    switch ($ctx) {
      case "mgr":
        $this->modx->lexicon->load("ajxmod:default");

        if (!$this->modx->loadClass("ajxmodControllerRequest", $this->config["modelPath"] . "ajxmod/request/", true, true)) {
          return "Could not load controller request handler.";
        }
        $this->request = new AjxModControllerRequest($this);
        return $this->request->handleRequest();
        break;
    }

    return true;
  }


  // getRequestedResource

}