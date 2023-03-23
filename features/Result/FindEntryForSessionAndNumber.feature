Feature: It can find entries by session and name

  Scenario: It finds an entry by session and name
    Given the entry "Max Verstappen For Red Bull Racing At Dutch GP 2022 Race" exists
    When a client searches for the entry at "Dutch Grand Prix 2022 Race" with number "33"
    Then the id of the entry of "Max Verstappen" for "Red Bull Racing" at "Dutch Grand Prix 2022 Race" with number "33" is returned

  Scenario: It cannot find it only with session
    Given the entry "Max Verstappen For Red Bull Racing At Dutch GP 2022 Race" exists
    When a client searches for the entry at "Dutch Grand Prix 2022 Race" with number "99999"
    Then it does not find the entry

  Scenario: It cannot find it only with number
    Given the eventSession "Australian Grand Prix 2022 Race" exists
    And the entry "Max Verstappen For Red Bull Racing At Dutch GP 2022 Race" exists
    When a client searches for the entry at "Australian Grand Prix 2022 Race" with number "33"
    Then it does not find the entry
