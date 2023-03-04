Feature: It can search a venue

  Scenario: It searches an existing venue
    Given the venue "Zandvoort" exists
    When a client searches for the venue "circuit-zandvoort"
    Then the id of the venue "Zandvoort" is returned

  Scenario: It searches a venue that does not exist
    Given the "venue" "Zandvoort" does not exist yet
    When a client searches for the venue "circuit-zandvoort"
    Then it does not receive any venue id
