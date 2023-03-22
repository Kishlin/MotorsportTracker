Feature: It can create Drivers

  Scenario: It saves a new entry
    Given the "Red Bull Racing" team exists
    And the driver "Max Verstappen" exists
    And the eventSession "Dutch Grand Prix 2022 Race" exists
    When a client creates the entry of "Max Verstappen" for "Red Bull Racing" at "Dutch Grand Prix 2022 Race" with number "33"
    Then the entry is saved
    And the id of the entry of "Max Verstappen" for "Red Bull Racing" at "Dutch Grand Prix 2022 Race" with number "33" is returned

  Scenario: It tries to create an existing entry
    Given the entry "Max Verstappen For Red Bull Racing At Dutch GP 2022 Race" exists
    When a client creates the entry with the same driver team session and number
    Then the entry is not duplicated
    And the id of the entry of "Max Verstappen" for "Red Bull Racing" at "Dutch Grand Prix 2022 Race" with number "33" is returned

  Scenario: It cannot have two entries for the same driver in session
    Given the entry "Max Verstappen For Red Bull Racing At Dutch GP 2022 Race" exists
    When a client creates the entry of the same driver for "Red Bull Racing" at "Dutch Grand Prix 2022 Race" with number "1"
    Then the entry creation is refused

  Scenario: It cannot have two entries for the same number in session
    Given the entry "Max Verstappen For Red Bull Racing At Dutch GP 2022 Race" exists
    And the driver "Sergio Perez" exists
    When a client creates the entry of "Sergio Perez" for "Red Bull Racing" at "Dutch Grand Prix 2022 Race" with the same number
    Then the entry creation is refused
