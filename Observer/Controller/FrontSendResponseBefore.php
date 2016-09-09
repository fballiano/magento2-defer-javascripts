<?php
/**
 * Fabrizio Balliano
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to info@idealiagroup.com so we can send you a copy immediately.
 *
 * @category   FabrizioBalliano
 * @package    FabrizioBalliano_DeferJavascripts
 * @copyright  Copyright (c) 2016 Fabrizio Balliano (http://fabrizioballiano.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace FabrizioBalliano\DeferJavascripts\Observer\Controller;

class FrontSendResponseBefore implements \Magento\Framework\Event\ObserverInterface
{
	public function execute(
		\Magento\Framework\Event\Observer $observer
	) {
	    $response = $observer->getEvent()->getResponse();
        if (!$response) return;

        $html = $response->getBody();
        if (stripos($html, "</body>") === false) return;

        preg_match_all('~<\s*\bscript\b[^>]*>(.*?)<\s*\/\s*script\s*>~is', $html, $scripts);
        if ($scripts and isset($scripts[0]) and $scripts[0]) {
            $html = preg_replace('~<\s*\bscript\b[^>]*>(.*?)<\s*\/\s*script\s*>~is', '', $response->getBody());
            $scripts = implode("", $scripts[0]);
            $html = str_ireplace("</body>", "$scripts</body>", $html);
            $response->setBody($html);
        }
	}
}
