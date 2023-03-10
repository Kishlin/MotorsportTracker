Feature: It can create Entrys

  Scenario: It saves a new entry
    Given the event "Dutch Grand Prix 2022" exists
    And the driver "Max Verstappen" exists
    When a client creates the entry for event "Dutch Grand Prix 2022" driver "Max Verstappen" with number "1"
    Then the entry is saved
    And the id of the new entry for car number "1" is returned

  Scenario: It tries to create an existing entry
    Given the entry "Max Verstappen At Dutch GP" exists
    When a client creates an entry for the same event, driver, and number
    Then the entry is not duplicated
    And the id of the existing entry for car number "1" is returned
