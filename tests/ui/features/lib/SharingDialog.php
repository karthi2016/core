<?php

/**
* ownCloud
*
* @author Artur Neumann
* @copyright 2017 Artur Neumann artur@jankaritech.com
*
* This library is free software; you can redistribute it and/or
* modify it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE
* License as published by the Free Software Foundation; either
* version 3 of the License, or any later version.
*
* This library is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU AFFERO GENERAL PUBLIC LICENSE for more details.
*
* You should have received a copy of the GNU Affero General Public
* License along with this library.  If not, see <http://www.gnu.org/licenses/>.
*
*/

namespace Page;

use SensioLabs\Behat\PageObjectExtension\PageObject\Page;

class SharingDialog extends OwnCloudPage
{
	/**
	 *
	 * @var string $path
	 */
	protected $path = '/index.php/apps/files/';

	protected $shareWithFieldXpath = ".//*[contains(@class,'shareWithField')]";
	protected $shareWithLoadingIndicatorXpath = ".//*[contains(@class,'shareWithLoading')]";
	protected $shareWithAutocompleteListXpath = ".//ul[contains(@class,'ui-autocomplete')]";
	protected $autocompleItemsTextXpath = "//*[@class='autocomplete-item-text']";

	 /**
	 * fills the "share-with" input field
	 * @param string $input
	 * @param number $timeout how long to wait till the autocomplete comes back
	 * @return array list of listed users
	 */
	public function fillShareWithField ($input, $timeout = 10)
	{
		$this->find("xpath", $this->shareWithFieldXpath)->setValue($input);
		$counter = 0;
		do {
			sleep(1);
			$counter++;
			$loadingIndicatorClass = $this->find(
				"xpath",
				$this->shareWithLoadingIndicatorXpath)->getAttribute("class");
		} while (strpos($loadingIndicatorClass, "hidden") === false && $counter <= $timeout);
		return $this->getAutocompleteUsersList();
	}

	/**
	 * gets the NodeElement of the autocomplete list
	 * @return \Behat\Mink\Element\NodeElement|NULL
	 */
	public function getAutocompleteNodeElement()
	{
		return $this->find("xpath", $this->shareWithAutocompleteListXpath);
	}

	/**
	 * gets the users listed in the autocomplete list as array
	 * @return array
	 */
	public function getAutocompleteUsersList()
	{
		$usersArray = array();
		$userElements = $this->getAutocompleteNodeElement()->findAll(
			"xpath", 
			$this->autocompleItemsTextXpath
		);
		foreach ( $userElements as $user ) {
			array_push($usersArray,$user->getText());
		}
		return $usersArray;
	}
}