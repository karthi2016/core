Feature: Sharee - autocompletion

	Scenario: autocompletion of regular existing users
		Given regular users exist
		And a regular user exists
		And I am logged in as a regular user
		And I am on the files page
		And the share dialog for the folder "simple-folder" is open
		And I type "user" in the share-with-field
		Then all users that contain the string "user" in their username should be listed in the autocomplete list
		And my own name should not be listed in the autocomplete list